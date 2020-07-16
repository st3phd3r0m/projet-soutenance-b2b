let numbOfSuggestion = 7;

$(document).ready(function() {

    //Elements de home/index.html.twig
    // $('#categoryFilter').select2();

    // Elements de home/new.html.twig
    $('.actSect').select2();
    $('#announcements_city').on('keyup', getCityList);
    $('#announcements_city').on('change', sendGPS);
});


function getCityList() {
    //Récupération valeur input
    let city = this.value;
    //Instanciation objet requête xmlhttp
    let xmlhttp = new XMLHttpRequest();
    //Ecouteur d'evenements sur la requete
    xmlhttp.onreadystatechange = () => {
        // Attente requete et reception reponse
        if (xmlhttp.readyState == 4) {
            //requete terminée 
            if (xmlhttp.status == 200) {
                //reponse
                let villes = JSON.parse(xmlhttp.responseText);
                //Traitement données entrantes
                cities=[];
                let compt = 0;
                for (let ville of villes) {
                    for (let cp of ville.codesPostaux){
                        cities[compt]={
                            'name':ville.nom,
                            'dept':ville.departement.nom,
                            'deptCode':ville.departement.code,
                            'cp':cp,
                            'long': ville.centre.coordinates[0],
                            'lat': ville.centre.coordinates[1]
                        }
                        compt++;
                    }
                }
                //On vide le panneau de selection (<datalist>)
                let selectPannel = document.querySelector("#selectPannel");
                selectPannel.innerHTML="";
                //Ajout de balises <option> dans <datalist>
                for (let ville of cities) {
                    let option = document.createElement("option"); 
                    option.dataset.ville = JSON.stringify(ville);
                    option.value = ville.name+"---"+ville.cp+"---"+ville.dept;
                    selectPannel.appendChild(option);
                }
            }
        }
    }
    //Ouverture requête
    xmlhttp.open("GET", "https://geo.api.gouv.fr/communes?nom="+city+"&fields=code,nom,departement,centre,codesPostaux,population&limit="+numbOfSuggestion);
    //Envoie de la requete
    xmlhttp.send();
}

function sendGPS(){
    let datalist = $(this).parent().next();
    let option = datalist.find('[value="'+$(this).val()+'"]');
    $("#announcements_coordinates").val(JSON.stringify(option.data('ville')));
}