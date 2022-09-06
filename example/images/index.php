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
include_once("../WikiAuthByCookie.php");
$auth = new WikiAuthByCookie();
if( ! $auth->auth_ok(array()) ) {
  echo "Invalid cookie";
  die;
}

$files = glob("*.jpg") + glob("*.png") + glob("*.gif") + glob("*.jpeg");
sort($files);?>
<!DOCTYPE html>
<html>
<meta charset='utf8'/>
<title>MyWiki - Images</title>
<style>
#content {
  color: black;
  padding: 1em;
  margin-left: auto;
  margin-right: auto;
  width: 80%;
  min-width: 480px;
  border: 1px solid black;
  box-shadow: 5px 5px 5px;
  background-color: white;
}
body {
  background-color: hsl(200,70%,30%);
}
footer {
  margin-top: 2em;
  color: hsl(0,0%,80%);
  text-align: center;
}
footer a, footer a:visited {
  color: hsl(0,0%,90%);
  font-style: italic;
}
ul {
  list-style-type: none;
}
</style>
<script>
window.addEventListener('keydown', e => {
  const q = x => document.querySelector(x)
  if(e.key === "Escape") {
    e.preventDefault()
    window.location.href = ".."
  }
})
</script>
</head>
<body>
<div id='content'>
  <h1>Images</h1>
  <hr />
<ul>
<?php
foreach($files as $file) {
  echo "<li><a href='$file'>$file</a></li>\n";
}
?>
</ul>
</div>
<footer>
<a href='http://thepurpletree.uk/'>The Purple Tree</a> 2022<br/>
Press shift-` to edit/save. Previous <a id='versions_link' href='WIKI_WORD?action=versions'>versions</a>.
</footer>
</body>
</html>
