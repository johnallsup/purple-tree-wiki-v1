<?php
include_once("WikiEngine2.php");
include_once("WikiStorageFlatFileVersions.php");
include_once("WikiAuthByCookie.php");
include_once("WikiRendererParsedown1.php");
$storage = new WikiStorageFlatFileVersions();
$auth = new WikiAuthByCookie();
$renderer = new WikiRendererParsedown1();
$engine = new WikiEngine2($storage,$renderer,$auth);
$engine->go();
