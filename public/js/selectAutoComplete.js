let numbOfSuggestion = 5;

$(document).ready(function() {
    $('.actSect').select2();
    $('#announcements_city').on('keyup', getCityList);
    $('#announcements_city').on('change', sendGPS);
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

                cities=[];
                let compt = 0;
                for (let ville of villes) {
                    for (let cp of ville.codesPostaux){
                        cities[compt]={
                            'name':ville.nom,
                            'dept':ville.departement.nom,
                            'cp':cp,
                            'long': ville.centre.coordinates[0],
                            'lat': ville.centre.coordinates[1]
                        }
                        compt++;
                    }
                }

                let selectPannel = document.querySelector("#selectPannel");
                selectPannel.innerHTML="";
                
                for (let ville of cities) {
                    let option = document.createElement("option"); 
                    option.dataset.ville = JSON.stringify(ville);
                    option.value = ville.name+"---"+ville.cp+"---"+ville.dept;
                    selectPannel.appendChild(option);
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

function sendGPS(){
    let datalist = $(this).parent().next();
    let option = datalist.find("[value='"+$(this).val()+"']");
    $("#announcements_coordinates").val(JSON.stringify(option.data('ville')));
}