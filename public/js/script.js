// Global variables
let map;
let marker;

function getCoordinates() {
    let address = document.getElementById("address").value.trim();

    if (address.length < 3) {
        document.getElementById("result").innerText = "⚠️ Please enter a valid address!";
        return;
    }

    fetch(`/get-location?address=${encodeURIComponent(address)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById("result").innerText = `📍 Lat: ${data.latitude}, Lng: ${data.longitude}`;
                showMap(data.latitude, data.longitude);
            } else {
                document.getElementById("result").innerText = "❌ Location not found!";
            }
        }).catch(error => {
            console.error("Error fetching location:", error);
            document.getElementById("result").innerText = "🚨 Error fetching location!";
        });
}

function showMap(lat, lng) {
    let mapContainer = document.getElementById("map");

    if (!mapContainer) {
        console.error("Map container not found!");
        return;
    }

    if (!map) {
        map = L.map('map').setView([lat, lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
    } else {
        map.setView([lat, lng], 13);
    }

    // Remove previous marker if exists
    if (marker) {
        map.removeLayer(marker);
    }

    // Add marker with pop-up
    marker = L.marker([lat, lng]).addTo(map)
        .bindPopup(`<b>Coordinates:</b><br>Lat: ${lat}<br>Lng: ${lng}`)
        .openPopup();
}

function fetchSuggestions(query) {
    if (query.length < 3) {
        document.getElementById("suggestions").innerHTML = "";
        return;
    }

    fetch(`/autocomplete?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            let suggestionsBox = document.getElementById("suggestions");
            suggestionsBox.innerHTML = "";
            data.forEach(item => {
                let listItem = document.createElement("li");
                listItem.className = "list-group-item";
                listItem.textContent = item.address_input;
                listItem.onclick = function () {
                    document.getElementById("address").value = item.address_input;
                    suggestionsBox.innerHTML = "";
                    getCoordinates(); // Auto-fetch location on click
                };
                suggestionsBox.appendChild(listItem);
            });
        }).catch(error => {
            console.error("Error fetching suggestions:", error);
        });
}

function loadHistory() {
    fetch('/search-history')
        .then(response => response.json())
        .then(data => {
            let historyBox = document.getElementById("history");
            historyBox.innerHTML = "";
            data.forEach(item => {
                let listItem = document.createElement("li");
                listItem.className = "list-group-item";
                listItem.textContent = item.address_input;
                listItem.onclick = function () {
                    document.getElementById("address").value = item.address_input;
                    getCoordinates();
                };
                historyBox.appendChild(listItem);
            });
        }).catch(error => {
            console.error("Error loading search history:", error);
        });
}

// Load search history when page loads
document.addEventListener("DOMContentLoaded", loadHistory);