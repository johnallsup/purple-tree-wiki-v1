<?php

include('../gencookie.php');
$cookie = gencookie();
// set cookie
setcookie($cookie['name'],$cookie['value'],time()+$cookie['duration'],"/");
?>Bumbles are going snarf.
