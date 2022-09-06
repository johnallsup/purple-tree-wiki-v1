<?php

include("gencookie.php");
$cookie = gencookie();

if(isset($_COOKIE[$cookie['name']])) {
  $got = $_COOKIE[$cookie['name']];
  if( strcmp($got,$cookie['value']) == 0 ) {
    echo "You may edit.";
  } else {
    echo "Your cookie has expired.";
  }
} else {
  echo "You need to authenticate.";
}
