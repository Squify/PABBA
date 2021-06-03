import SearchForm from './components/SearchForm';

let initForm = () => {
    let $map = document.querySelector("#map");

    let map = L.map($map).setView([47.843601, 1.939258], 7);


    map.addLayer(new L.StamenTileLayer('terrain', {
        // detectRetina: true
    }))


    Array.from(document.querySelectorAll(".js-marker")).forEach((item) => {
        addMarker(map, item)
    })

    function addMarker(map, item) {
        let color, hexa = ""
        let {lat, lng, type, addr, comments} = item.dataset
        switch (type) {
            case "Jardin":
                color = "green";
                hexa = color;
                break;

            case "Point de collecte":
                color = "yellow";
                hexa = "#ecec00"
                break;

            case "Événement":
                color = "blue";
                hexa = color;
                break;

            default:
                color = "black";
                hexa = color;
                break;
        }
        item.querySelector('h5').style.color = hexa
        let icon = L.icon({
            iconUrl: `./images/${color}-marker.svg` ,
            iconSize: [38, 95],
            iconAnchor: [22, 94],
            popupAnchor: [-3, -76],
        });
        let html = `
        <h4>Détails</h4>
        <h6>${type}</h6>
        Adresse: ${addr}<br>
        Commentaire: <br>${comments}
                `
        L.marker()
        .setLatLng([lat, lng])
        .setIcon(icon)
        .bindPopup(html)
        .addTo(map)
    }
}

document.addEventListener('DOMContentLoaded', () => {
    let s = new SearchForm('/lieu/search', 'place', initForm)
    s.loadData();
});
