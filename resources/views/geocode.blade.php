<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geocoding Service</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map { height: 400px; }
        .container { max-width: 600px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container text-center">
        <h2 class="mb-4">Location-Based Geocoding</h2>
        <input type="text" id="address" class="form-control mb-2" placeholder="Enter address" onkeyup="fetchSuggestions(this.value)">
        <ul id="suggestions" class="list-group mt-1"></ul>

        <button class="btn btn-primary" onclick="getCoordinates()">Find Location</button>
        <p id="result" class="mt-3 text-success"></p>
        <div id="map" class="mt-4"></div>
        <h3 class="mt-4">Recent Searches</h3>
        <ul id="history" class="list-group"></ul>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>

</body>
</html>