
//On attend que le DOM soit chargé
window.onload = () => {

    let slider = document.getElementById('slider');

    if (slider) {

        let range = noUiSlider.create(slider, {

            // Position des curseurs au chargement de la page
            start: [1000, 1e5],
            // Déplacement du curseurs par 1€
            step: 1000,
            connect: true,

            // Fourchette de prix min/max du budget
            range: {
                'min': 0,
                'max': 2e5
            },
        });
        let min = document.getElementById('announcements_price_min')
        let max = document.getElementById('announcements_price_max')
        
        range.on('slide', function (values, handle) {
            if (handle === 0) {
                min.value = Math.round(values[0])
            }
            if (handle === 1) {
                max.value = Math.round(values[1])
            }
            // console.log(values, handle)
        })
    }

}

