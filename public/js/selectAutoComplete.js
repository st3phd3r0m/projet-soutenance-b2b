let numbOfSuggestion = 5;

$(document).ready(function() {
    $('.actSect').select2();
    $('#announcements_city').on('keyup', getCityList);

});


function getCityList() {

    let city = this.value;

    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = () => {
        // On attend la fin de la requete et la reception d'une reponse
        if (xmlhttp.readyState == 4) {
            //Ici la requete est terminée et on a une reponse
            if (xmlhttp.status == 200) {
                //Ici on a une reponse
                let villes = JSON.parse(xmlhttp.responseText);

                let selectPannel = document.querySelector("#selectPannel");

                selectPannel.innerHTML="";
                
                for (let ville of villes) {
                    let option = document.createElement("option");
                    selectPannel.appendChild(option);
                    option.value = ville.nom+"---"+ville.departement.code+"---"+ville.departement.nom;
                }

            }
        }
    }

    //On ouvre la requête
    //"https://geo.api.gouv.fr/communes?nom="+city+"&fields=code,nom,codeDepartement,departement,centre,codesPostaux&boost=population&limit="+numbOfSuggestion
    xmlhttp.open("GET", "https://geo.api.gouv.fr/communes?nom="+city+"&fields=code,nom,departement,centre,codesPostaux,population&limit="+numbOfSuggestion);
    //On envoie la requete
    xmlhttp.send();

}