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

// set cookie
if(isset($_COOKIE[$cookie['name']])) {
//  echo "Have cookie";
  $got = $_COOKIE[$cookie['name']];
  if( strcmp($got,$cookie['value']) == 0 ) {
    echo "Du bist willkommen!";
  } else {
    echo "The heavy door does not budge.";
  }
} else {
  echo "You need to find the magic cookie.";
}
