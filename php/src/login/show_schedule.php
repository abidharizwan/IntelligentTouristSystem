<?php
session_start();
include('connection.php');

$id = $_SESSION['id'];
$likes = $_SESSION['ulike'];
$city = $_SESSION['ucity'];
$str = "";
$_SESSION['ival'] = 1;
$lat_long="";

for($i = 1; $i < $_SESSION['index']; $i++){
	//$lat_long=$lat_long+$_SESSION['lat'][$_SESSION['ival']]." ".$_SESSION['lng'][$_SESSION['ival']]."<br>";
  if($i==$_SESSION['index']-1)
  {
     $lat_long .= $_SESSION['lat'][$_SESSION['ival']] . ','.$_SESSION['lng'][$_SESSION['ival']];
  }
  else
  {
    $lat_long .= $_SESSION['lat'][$_SESSION['ival']] . ','.$_SESSION['lng'][$_SESSION['ival']].',';
  }
	$_SESSION['ival'] = $_SESSION['ival'] + 1;
}

$str = "";
$venues = ",";
for($i = 0; $i < strlen($likes) - 1; $i++){
	if($likes[$i] == ","){
		$venues .= $_SESSION[$str].",";
		$str = "";
	}
	else{
		$str .= $likes[$i];
	}
}

// $venues .= $_SESSION[$str];
$str = "";

$_SESSION['ival'] = 1;
?>
<?php
//index.php

$subject = 'smart city suggestions';
$message = '';
$gmap="https://www.google.com/maps/search/?api=1&query=";
$rest=explode(',', $venues);
for($i = 1; $i < $_SESSION['index']; $i++){
  
  $gmap="<tabel border=\"1\"><tr><th>".$rest[$i]."</th><th></th></tr><tr><td></td></tr><tr><td>"."https://www.google.com/maps/search/?api=1&query=".$_SESSION['lat'][$_SESSION['ival']].','.$_SESSION['lng'][$_SESSION['ival']].PHP_EOL.'</tabel>';
	//$gmap="hello";
  $message.=$gmap.' ';
	$_SESSION['ival'] = $_SESSION['ival'] + 1;
}


?>
<!DOCTYPE html>
        <html lang="en">
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <meta http-equiv="X-UA-Compatible" content="ie=edge">
		  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
		<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha384-V0VeXlN5Lcd5vqy9reR0dLoZ6jHn8L4OtcCE4aw78ZfOLk5b54JC3R9BQlrYHiQD" crossorigin="anonymous">
          <title>Intelligent Tourist Management System (ITMS)</title>
          <style>
           #container {
      display: flex;
      height: 100vh;
    }

    #map {
      flex: 1;
      height: 100%;
    }

    /* #locations {
      flex: 1;
      padding: 20px;
      box-sizing: border-box;
    } */
	#locations {
    flex: 1;
    padding: 20px;
    box-sizing: border-box;
    overflow-y: auto; /* Add this line to enable vertical scroll */
    max-height: 100vh; /* Set a maximum height for the div */
  }
 

    .location-card {
      margin-bottom: 10px;
      cursor: pointer;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
	  background-color: #CBD6CA
    }

    .location-card:hover {
      background-color: #f0f0f0;
    }

    .location-card.selected {
      background-color: #b3d9ff; /* Light blue background for selected card */
    }

    .location-name {
      font-weight: bold;
    }
          </style>
		  <style>
			.column1 {
				float: left;
				width: 50%;
			}

			.column2 {
				float: right;
				width: 40%;
			}
		  </style>
		<!--===============================================================================================-->
		<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
		<!--===============================================================================================-->
		<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
		<!--===============================================================================================-->
		<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
		<!--===============================================================================================-->
		<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
		<!--===============================================================================================-->
		<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
		<!--===============================================================================================-->
		<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
		<!--===============================================================================================-->
		<link rel="stylesheet" type="text/css" href="css/util.css">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<!--===============================================================================================-->
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        </head>
        <body>
		
		<!-- Header -->
		<div class="header">
			<a href="home.php" class="logo">Intelligent Tourist Management System (ITMS)</a>
			<div class="header-right">
				<a class="active" href="home.php">Home</a>
				<a href="signout.php">signout<img src="images/icons/user_ironman.png"/></a>
				<a href="../aboutus/aboutus.html">About us</a>
			</div>
		</div>


<!-- Content -->
		
		
		<div class="bg-contact3" style="background-image: url('images/home_slider.jpg');">
		<div id="container">
		<div id="map"></div>
		<div id="locations"></div>
		</div>
		</div>
		<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
		<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
		<script>
			var storedData = localStorage.getItem('venueData');
      function getCookie(name) {
  // Split the document.cookie string into individual cookies
  var cookies = document.cookie.split(';');

  // Loop through each cookie to find the one with the specified name
  for (var i = 0; i < cookies.length; i++) {
    var cookie = cookies[i].trim();

    // Check if the cookie starts with the specified name
    if (cookie.indexOf(name + '=') === 0) {
      // Extract and return the value of the cookie
      return decodeURIComponent(cookie.substring(name.length + 1));
    }
  }

  // Return null if the cookie with the specified name is not found
  return null;
}
			if (storedData) {
			// Parse the JSON string into an array
			var dataArray = JSON.parse(storedData);

			// Access a specific key, for example, the first element's name
			if (dataArray.length > 0) {
				var firstName = dataArray[0].name;
			} else {
				console.log('No data in the array.');
			}
			} else {
			console.log('No data stored in local storage.');
			}

      var my_lat=getCookie('my_lat');
      var my_lon=getCookie('my_lon');

			var map = L.map('map').setView([my_lat, my_lon], 6); // Centered on India
			var routingControl; // Variable to store the routing control
			var selectedCard = null; // Variable to store the selected card

			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '© OpenStreetMap contributors'
			}).addTo(map);
			var locationsDiv = document.getElementById('locations');
			var locationsDiv = document.getElementById('locations');
  dataArray.forEach(function(location) {
    var card = document.createElement('div');
    card.className = 'location-card';
    card.innerHTML = `
      <div class="location-name">${location.name}</div>
      <div>Latitude: ${location.lat}</div>
      <div>Longitude: ${location.lng}</div>
    `;
    locationsDiv.appendChild(card);

    // Add click event listener to show directions and mark the card as selected
    card.addEventListener('click', function() {
      // Remove previous route
      if (routingControl) {
        map.removeControl(routingControl);
      }

      // Remove selected style from the previously selected card
      if (selectedCard) {
        selectedCard.classList.remove('selected');
      }

      // Show directions for the new location
      showDirections(location.lat, location.lng);

      // Mark the current card as selected
      selectedCard = card;
      selectedCard.classList.add('selected');
    });
  });

  function showDirections(lat, lng) {
    // Set up routing control with blue route color
    routingControl = L.Routing.control({
      waypoints: [L.latLng(my_lat,my_lon), L.latLng(lat, lng)],
      routeWhileDragging: true,
      lineOptions: {
        styles: [{ color: 'blue', opacity: 0.5, weight: 7 }]
      }
    }).addTo(map);
  }
            </script>
        </body>
        </html>