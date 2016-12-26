<?php

$locations = array("tr", "en");
$default_location = 'tr';

function is_location_cookie_exist(){
  global $locations;
  return isset($_COOKIE['stardom-wp-location']) && in_array($_COOKIE['stardom-wp-location'], $locations);
}

function set_location_cookie($location){
  setcookie('stardom-wp-location', $location, time() + 3600  * 24 * 365, '/');
  $_COOKIE['stardom-wp-location'] = $location;
}

function get_location_from_cookie(){
  if(is_location_cookie_exist()){
    return $_COOKIE['stardom-wp-location'];
  } else {
    return null;
  }
}

function delete_all_cookies(){
  $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
  foreach($cookies as $cookie) {
      $parts = explode('=', $cookie);
      $name = trim($parts[0]);

      $_COOKIE[$name]  = '';
      setcookie($name, '', 1, '/');
      setcookie($name, '', 1, '/wp-core/');
  }
}
function parse_location_from_url(){
  $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $location = explode('/', $path)[1];
  return $location;
}

function get_location_from_url(){
  global $locations;
  $location = parse_location_from_url();
  if(isset($location) && in_array($location, $locations)){
    return $location;
  } else {
    return null;
  }
}

$location_url = get_location_from_url(); //tr //en //null
$location_cookie = get_location_from_cookie(); //tr //en //null

if($location_url == null && $location_cookie == null){
  $location_url = $default_location;

} else if($location_url == null && $location_cookie != null){
  $location_url = $location_cookie;
} else if($location_url != null && $location_cookie != null && $location_url != $location_cookie){
  //delete_all_cookies();
}

set_location_cookie($location_url);
require_once( dirname(__FILE__) . '/' . $location_url . '/wp-config.php' );
