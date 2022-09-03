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
?><!DOCTYPE html>
<!-- image uploader code from https://artisansweb.net/drag-drop-file-upload-using-javascript-php/ -->
<html>
<head>
<meta charset='utf8'/>
<title>Image upload</title>
</head>
<body>
<link rel="stylesheet" href="style.css" />
<div>
Name: <input id="drop_file_name" cols="80"/>
</div>
<div id="drop_file_zone" ondrop="upload_file(event)" ondragover="return false">
    <div id="drag_upload_file">
        <p>Drop file here</p>
        <p>or</p>
        <p><input type="button" value="Select File" onclick="file_explorer();" /></p>
        <input type="file" id="selectfile" />
    </div>
</div>
<div class="img-content"></div>
<script src="uploader.js"></script>
</body>
</html>
