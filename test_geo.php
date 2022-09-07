<?php
function getLatLong($address){
    $api_key = "AIzaSyAC7Rj163G5vNrR5_1AEncatw3OHcjTock";
    $formatted_address = str_replace(' ','+',$address);
    // $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA&key='.$api_key);
    $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formatted_address.'&key='.$api_key);
    $output = json_decode($geocodeFromAddr);
    $data['latitude'] = $output->results[0]->geometry->location->lat;
    $data['longitude'] = $output->results[0]->geometry->location->lng;
    if(!empty($data)){
        return $data;
    } else {
        return false;
    }
}

// print_r(getLatLong("44 E 161st St, Bronx, NY 10451"));

// 16.817382078153482, 96.15690557081344
// print_r(getLatLong("Lotteria, No. G-07, Pearl Condo, Kabar Aye Pagoda Road, Yangon, Myanmar (Burma)"));

// 16.81721551836253, 96.156581868968
// print_r(getLatLong("Pearl Condo C, Kabar Aye Pagoda Road, Yangon, Myanmar (Burma)"));
?>

<iframe
  width="600"
  height="450"
  style="border:0"
  loading="lazy"
  allowfullscreen
  referrerpolicy="no-referrer-when-downgrade"
  src="https://www.google.com/maps/embed/v1/view?key=AIzaSyAC7Rj163G5vNrR5_1AEncatw3OHcjTock&center=16.847,96.1804&zoom=18">
</iframe>
