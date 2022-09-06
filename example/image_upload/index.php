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
<link rel="icon" type="image/x-icon" href="/purple_tree_wiki_favicon.png"/>
<script>
window.addEventListener("keydown",e => {
  if( e.ctrlKey || e.altKey || e.metaKey ) return true
  if( e.key == "Escape" ) {
    e.preventDefault()
    history.back()
    return false
  }
})
</script>
<link rel="stylesheet" href="style.css" />
</head>
<body>
<div class='content'>
<h1>Image Upload</h1>
<p>Name will default to uploaded filename if blank.</p>
<p>Extension will always match the uploaded filename, so typing 'hello.png' and uploading a .jpg will result in 'hello.jpg' being the uploaded filename.</p>
<div class='input-area'>
<span class="label">Name:</span> <input autofocus id="drop_file_name" cols="80"/>
</div>
<div id="drop_file_zone" ondrop="upload_file(event)" ondragover="return false">
    <div id="drag_upload_file">
        <p>Drop file here</p>
        <p>or</p>
        <p><input type="button" value="Select File" onclick="file_explorer();" /></p>
        <input type="file" id="selectfile" />
    </div>
</div>
<div class="img-upload-result">
<div class="img-upload-text"><span class="message"></span><span class="filename"></span></span></div>
<div class="img-content"></div>
<script src="uploader.js"></script>
</div>
</div>
</body>
</html>
