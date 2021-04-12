let $map = document.querySelector("#map");

let map = L.map($map).setView([47.843601, 1.939258], 13);

// L.tileLayer('//{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
//     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
//     maxZoom: 18,
// }).addTo(map);

map.addLayer(new L.StamenTileLayer('terrain', {
    // detectRetina: true
}))


Array.from(document.querySelectorAll(".js-marker")).forEach((item) => {
    addMarker(map, item.dataset.lat, item.dataset.lng, item.dataset.type)
})

function addMarker(map, lat, lng, type) {
    let color = ""
    switch (type) {
        case "jardin":
            color = "green";
            break;

        case "point de collecte":
            color = "yellow";
            break;

        case "evenement":
            color = "blue";
            break;
    
        default:
            color = "black";
            break;
    }
    let icon = L.icon({
        iconUrl: `./images/${color}-marker.svg` ,
        iconSize: [38, 95],
        iconAnchor: [22, 94],
        popupAnchor: [-3, -76],
    });
    L.marker()
        .setLatLng([lat, lng])
        .setIcon(icon)
        .addTo(map)
}