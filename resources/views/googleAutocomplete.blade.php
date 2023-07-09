<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Google Autocomplete Address Example</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
</head>

<body>
<div class="container mt-5">
    <h2>Laravel Google Autocomplete Address Example</h2>

    <div class="form-group">
        <label>Location/City/Address</label>
        <input type="text" name="autocomplete" id="autocomplete" class="form-control" placeholder="Choose Location">
    </div>

    <div class="form-group" id="latitudeArea">
        <label>Latitude</label>
        <input type="text" id="latitude" name="latitude" class="form-control">
    </div>

    <div class="form-group" id="longitudeArea">
        <label>Longitude</label>
        <input type="text" name="longitude" id="longitude" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script async defer type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ $_ENV['GOOGLE_MAP_KEY'] }}&libraries=places"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var input = document.getElementById('autocomplete');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            document.getElementById('latitude').value = place.geometry.location.lat();
            document.getElementById('longitude').value = place.geometry.location.lng();

            document.getElementById('latitudeArea').classList.remove('d-none');
            document.getElementById('longitudeArea').classList.remove('d-none');
        });
    });

</script>
</body>
</html>
