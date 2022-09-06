<?php
#
# Some code adapted from https://artisansweb.net/drag-drop-file-upload-using-javascript-php/
# No copyright claimed on that. License for this code is unclear.
#
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
define('IMAGE_LIMIT',1024);
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
define('PUBLIC_IMAGE_DIR','images');
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
$pfn = PUBLIC_IMAGE_DIR."/$filename.$ext";
if( file_exists($ofn) ) {
  unlink($ofn);
}

$existing_images = 
  glob(IMAGE_DIR."/*.png") + 
  glob(IMAGE_DIR."/*.jpg") + 
  glob(IMAGE_DIR."/*.jpeg") + 
  glob(IMAGE_DIR."/*.gif");
$n_existing_images = count($existing_images);
if( $n_existing_images >= IMAGE_LIMIT ) {
  echo "Too many images";
  die;
}
if( move_uploaded_file($_FILES['file']['tmp_name'], $ofn) ) {
  echo "$pfn?".time();
  die;
} else {
  echo "Failed to move";
  die;
}

