<?php
function get_client_ip() {
     $ipaddress = '';
     if (getenv('HTTP_CLIENT_IP'))
         $ipaddress = getenv('HTTP_CLIENT_IP');
     else if(getenv('HTTP_X_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
     else if(getenv('HTTP_X_FORWARDED'))
         $ipaddress = getenv('HTTP_X_FORWARDED');
     else if(getenv('HTTP_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_FORWARDED_FOR');
     else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
     else if(getenv('REMOTE_ADDR'))
         $ipaddress = getenv('REMOTE_ADDR');
     else
         $ipaddress = 'UNKNOWN';
     return $ipaddress; 
}

// Accept latitude, longitude and location overrides.
$lat = isset($_GET['lat']) ? $_GET['lat'] : null;
$lon = isset($_GET['lon']) ? $_GET['lon'] : null;
$location = isset($_GET['location']) ? $_GET['location'] : null;

if(($lat == null || $lon == null) && $location == null)
{
  $ip = isset($_GET['ip']) ? $_GET['ip'] : get_client_ip(); // the IP address to query
  $query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
  if($query && $query['status'] == 'success') {
    $lat = $query['lat'];
    $lon = $query['lon'];
  }
}

if($lat == 0 && $lon == 0 && $location == null) {
  $location = 'Paris';
}

if($location != null) {
  $url = "http://api.openweathermap.org/data/2.5/weather?q={$location}";
} else {
  $url = "http://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}";
}

$djson = file_get_contents($url);
echo $djson;
?>