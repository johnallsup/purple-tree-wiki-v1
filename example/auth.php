<?php
#
# Purple Tree Wiki v1
#
# (c) John Allsup 2021-2022
# https://john.allsup.co
#
# Distributed under the MIT License.
#
# For the full license information, view the LICENSE file that was distributed
# with this source code.
#
define('PASSWORD','DontPanicPrettyPlease');
include('gencookie.php');
if( $_SERVER['REQUEST_METHOD'] == "POST" ) {
  $pw = $_POST['password'];
  if( $pw == PASSWORD ) {
    $cookie = gencookie();
    setcookie($cookie['name'],$cookie['value'],time()+$cookie['duration'],"/");
    echo "Authentication successful.";
  } else {
    echo "Incorrect password.";
  }
}
?><!DOCTYPE html>
<html>
<head>
<meta charset='utf8'/>
<title>Simple password example</title>
</head>
<body>
<h1>Enter Password</h1>
<form method="post">
<input cols="80" name="password"/>
<input type="submit"/>
</form>
</body>
</html>
