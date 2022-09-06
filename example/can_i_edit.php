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
