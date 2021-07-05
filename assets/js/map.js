// On initialise la latitude et la longitude de Paris (centre de la carte)
var lat = 48.852969;
var long = 2.349903;
var myMap = null;
var markerClusters; 

// Fonction d'initialisation de la carte
function initMap(cities) {
    // Initialisation de la liste des marqueurs
    var markers = []; 
    // Créer l'objet "myMap" et l'insèrer dans l'élément HTML qui a l'ID "map"
    myMap = L.map('map').setView([lat, long], 10);
    // Nous initialisons les groupes de marqueurs
    markerClusters = L.markerClusterGroup(); 
    // Récupération des cartes (tiles) sur openstreetmap.fr
    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        // Précision du lien vers la source des données
        attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
        minZoom: 5,
        maxZoom: 20
    }).addTo(myMap);

    // Nous parcourons la liste des villes
	for (city in cities) {
        var marker = L.marker([cities[city].lat, cities[city].long]);
        // Ajout du pop up de l'annonce
        marker.bindPopup(city);
        //l'affichage est géré par la bibliothèque des clusters / on ajoute le marqueur aux groupes
        markerClusters.addLayer(marker);
        // Ajout du marqueur à la liste des marqueurs
        markers.push(marker);
    }

    if (markers.length > 0) {
        // Création du groupe des marqueurs pour adapter le zoom
        var group = new L.featureGroup(markers); 
        // Demande à ce que tous les marqueurs soient visibles, et ajout d'un padding (pad(0.5)) pour que les marqueurs ne soient pas coupés
        myMap.fitBounds(group.getBounds().pad(0.5)); 
    }
    
    myMap.addLayer(markerClusters);
}

window.onload = function(){
    // Nous initialisons une liste de coordonnées
    var map = document.querySelector('#map');
    var cities = JSON.parse(map.dataset.citiesCoord);

    // Fonction d'initialisation qui s'exécute lorsque le DOM est chargé
    initMap(cities); 
};
// Fin de chargement du DOM