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

include('../gencookie.php');
$cookie = gencookie();
// set cookie
setcookie($cookie['name'],$cookie['value'],time()+$cookie['duration'],"/");
?>You have acccess.
