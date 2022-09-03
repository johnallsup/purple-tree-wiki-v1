<?php
#
# Code adapted from https://artisansweb.net/drag-drop-file-upload-using-javascript-php/
#
include_once("../WikiAuthByCookie.php");
$auth = new WikiAuthByCookie();
if( ! $auth->auth_ok(array()) ) {
  echo "Invalid cookie";
  die;
}

$arr_file_types = ['image/png', 'image/gif', 'image/jpg', 'image/jpeg'];
  
if (!(in_array($_FILES['file']['type'], $arr_file_types))) {
    echo "false";
    return;
}

define('IMAGE_DIR','../images');
if (!file_exists(IMAGE_DIR)) {
    mkdir(IMAGE_DIR, 0755);
}
  
$filename = $_POST['filename'];
if( strlen($filename) == 0 ) {
  # default to filename
  $filename = $_FILES['file']['name'];
}
$filename = preg_replace("/\.(jpeg|jpg|png|gif)$/","",$filename);

if( ! preg_match("/^[a-zA-Z0-9_\.-]+$/",$filename) ) {
  echo "Invalid filename '$filename'";
  file_put_contents("error.txt","Invalid filename '$filename'");
  die;
}
$ext = preg_replace("/^.*\.([^\.]*)$/","\\1",$_FILES['file']['name']);
  
# enusre that no matter what extension is provided via the text input, the file gets the right one.
$ofn = IMAGE_DIR."/$filename.$ext";
if( file_exists($ofn) ) {
  unlink($ofn);
}

if( move_uploaded_file($_FILES['file']['tmp_name'], $ofn) ) {
  echo $ofn."?".time();
  die;
} else {
  echo "Failed to move";
  die;
}

