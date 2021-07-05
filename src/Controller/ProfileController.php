<?php

namespace App\Controller;

use App\Entity\Announcements;
use App\Entity\Subscription;
use App\Entity\SubscriptionPurchases;
use App\Entity\Users;
use App\Form\AnnouncementsType;
use App\Form\UsersType;
use App\Repository\AnnouncementsRepository;
use App\Repository\SubscriptionPurchasesRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\UsersRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        if (!$this->session->has('cart')) {
            $this->session->set('cart', []);
        }
    }

    /**
     * @Route("/index", name="profile_index")
     */
    public function index()
    {
        return $this->render('profile/index.html.twig');
    }

    /**
     * @Route("/list", name="profile_list", methods={"GET"})
     */
    public function list(AnnouncementsRepository $announcementsRepository, PaginatorInterface $paginator, Request $request)
    {
        //Affichage, dans l'espace utilisateur, des annonces qu'il a publiées
        $announcements = $paginator->paginate(
            $announcementsRepository->findBy(['user' => $this->getUser()], ['created_at' => 'DESC']),
            //Le numero de la page, si aucun numero, on force la page 1
            $request->query->getInt('page', 1),
            //Nombre d'élément par page
            5
        );
        return $this->render('profile/listAnnouncements.html.twig', [
            'announcements' => $announcements
        ]);
    }

    /**
     * @Route("/orders/record", name="profile_orders_record")
     */
    public function ordersRecord(SubscriptionPurchasesRepository $subscriptionPurchasesRepository, SubscriptionRepository $subscriptionRepository)
    {
        $user = $this->getUser();
        $subscriptionPurchases = $subscriptionPurchasesRepository->findBy(['user' => $user], ['ordered_at' => 'DESC']);
        $subscriptions = $subscriptionRepository->findAll();
        return $this->render('profile/ordersRecord.html.twig', [
            'subscriptionPurchases' => $subscriptionPurchases,
            'subscriptions'=>$subscriptions
        ]);
    }

    /**
     * @Route("/edit/{slug}", name="profile_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Announcements $announcement
     * @return Response
     */
    public function edit(Announcements $announcement, Request $request)
    {
        //Création de formulaire
        $form = $this->createForm(AnnouncementsType::class, $announcement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Récupération des mots-clés en tant que chaine de caractères et séparation en array avec un délimiteur ";"
            $location = json_decode($form->get("coordinates")->getData());
            $announcement->setCity($location->name);
            $announcement->setPostalCode($location->cp);
            $announcement->setDeptCode($location->deptCode);
            $announcement->setDepartememt($location->dept);
            $gps = [
                'long' => $location->long,
                'lat' => $location->lat
            ];
            $announcement->setGpsLocation($gps);
            //Récupération des mots-clés en tant que chaine de caractères et séparation en array avec un délimiteur ";"
            $key_words = $form->get("key_words")->getData();
            $key_words = explode(";", $key_words);
            $key_words = array_filter($key_words);
            $announcement->setKeyWords($key_words);
            //Récupération de la délimitation du budget
            $price_range[0] = $form->get("price_min")->getData();
            $price_range[1] = $form->get("price_max")->getData();
            $announcement->setPriceRange($price_range);
            //Ré-initialisation du nombre de clics de déblocage de l'annonce
            $announcement->setUnlockCount(0);
            //On fait persister les données en bdd
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($announcement);
            $entityManager->flush();
            //Envoi d'un message de succès
            $this->addFlash('success', 'Votre annonce a bien été modifiée.');
            //Redirection
            return $this->redirectToRoute('profile_list');
        }
        return $this->render('home/new.html.twig', [
            'announcement' => $announcement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account", name="profile", methods={"GET"})
     */
    public function account(UsersRepository $usersRepository, SubscriptionRepository $subscriptionRepository, Request $request)
    {
        $subscriptions = $subscriptionRepository->findAll();
        $user = $usersRepository->find($this->getUser());

        return $this->render('profile/account.html.twig', [
            'user' => $user,
            'subscriptions' => $subscriptions
        ]);
    }

    /**
     * @Route("/details/change/{id}", name="profile_change_details", methods={"GET","POST"})
     * @param Request $request
     * @param Users $user
     * @return Response
     */
    public function changeDetails(Request $request, Users $user): Response
    {
        $form = $this->createForm(UsersType::class, $user);
        $form->remove('roles');
        $form->remove('plainPassword');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            //Envoi d'un message de succès
            $this->addFlash('success', 'Vos informations personnelles ont bien été modifiées.');
            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/changeDetails.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/confirm/purchase/{id}", name="profile_confirm_subscription", methods={"GET"})
     * @param Subscription $subscription
     * @return Response
     */
    public function confirmPurchase(Subscription $subscription): Response
    {
        return $this->render('profile/confirmPurchase.html.twig', [
            'subscription' => $subscription
        ]);
    }

    /**
     * @Route("/payment/{id}", name="profile_subscription_payment", methods={"GET"})
     */
    public function cartPayment(Subscription $subscription)
    {
        //Chargement id pach d'abonnement et quantité
        $this->session->set('cart', [
            'subscriptionId' => $subscription->getId(),
            'quantity' => 1
        ]);
        // Stripe - Create a PaymentIntent on the server
        Stripe::setApiKey('sk_test_51H02NpD7y6oTPe9NYnY22AFmxD034fdn0ndOVbOe63fGV5hQLUfHVhlIi59PGsFQWVvyfefK3c6MNKoBihYojpBT00Qo2t4tvx');
        // Prix en centimes !!!
        $intent = PaymentIntent::create([
            'amount' => $subscription->getPrice() * 100,
            'currency' => 'eur'
        ]);
        return $this->render('profile/cartPayment.html.twig', ['stripe' => $intent]);
    }

    /**
     * @Route("/store/transaction", name="store_transaction", methods={"GET","POST"})
     * @return Response
     */
    public function storeTransaction(SubscriptionRepository $subscriptionRepository, UsersRepository $usersRepository): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        //On appel la variable globale de session
        $cartSession = $this->session->get('cart');

        $subscription = $subscriptionRepository->find($cartSession['subscriptionId']);

        //On calcule le montant total de la transaction
        $total = $subscription->getPrice() * $cartSession['quantity'];

        //Instanciation de Orders et "hydratation"
        $subscriptionPurchase = new SubscriptionPurchases;
        $subscriptionPurchase->setOrderedAt(new \DateTime());
        $subscriptionPurchase->setUser($this->getUser());
        $subscriptionPurchase->setSubscription($subscription);
        $subscriptionPurchase->setQuantity($cartSession['quantity']);
        $subscriptionPurchase->setAmount($total);
        $subscriptionPurchase->setActive(true);
        $entityManager->persist($subscriptionPurchase);
        $entityManager->flush();

        //Rechargement en crédits du compte utilisateur
        $user = $usersRepository->find($this->getUser());
        $currentBalance = $user->getAccount();
        $currentBalance += $subscription->getCredit();
        $user->setAccount($currentBalance);
        $entityManager->persist($user);
        $entityManager->flush();

        //On vide le panier de la variable globale de session
        $cartSession = [];
        $this->session->set('cart', $cartSession);

        return $this->redirectToRoute('thankforyourorder', ['id' => $subscriptionPurchase->getId()]);
    }


    /**
     * @Route("/order/bill/{id}", name="profile_bill", methods={"GET","POST"})
     * @param Request $request
     * @param Orders $order
     * @return Response
     */
    public function billDownload(SubscriptionPurchases $subscriptionPurchase)
    {
        //Conversion préalable en base64 du fichier logo.png pour inclure l'image
        //dans la facture en pdf

        $path = 'images/logo_Yeah.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('profile/billDownload.html.twig', [
            'subscriptionPurchase' => $subscriptionPurchase,
            'base64' => $base64
        ]);
        //Concatenation pour inclure les chemins des fichiers css
        // $html .= '<link rel="stylesheet" href="' . $publicDirectory . '/css/styles.css">';
        // $html .= '<link rel="stylesheet" href="' . $publicDirectory . '/css/stylesPDF.css">';

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    }


    /**
     * @Route("/thank/for/your/order/{id}", name="thankforyourorder", methods={"GET","POST"})
     * @param Request $request
     * @param SubscriptionPurchases $subscriptionPurchase
     * @return Response
     */
    public function thankForYourOrder(SubscriptionPurchases $subscriptionPurchase)
    {
        return $this->render('profile/thankForYourOrder.html.twig', [
            'subscriptionPurchase' => $subscriptionPurchase
        ]);
    }
}
