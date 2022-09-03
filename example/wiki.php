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
include_once("WikiEngine2.php");
include_once("WikiStorageFlatFileVersions.php");
include_once("WikiAuthByCookie.php");
include_once("WikiRendererParsedown1.php");
$storage = new WikiStorageFlatFileVersions();
$auth = new WikiAuthByCookie();
$renderer = new WikiRendererParsedown1();
$engine = new WikiEngine2($storage,$renderer,$auth);
$engine->go();
