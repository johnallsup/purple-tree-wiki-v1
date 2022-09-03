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

function gencookie() {
  $Sugar = "vixafT#TJ8JqAjzB";
  $Cookie_Duration = 60*60*24*30;
  $Cookie_Name="ToastRiseExitOrchestra";
  $date = date("W");
  $hash = hash("sha256",$Sugar.$date,true);
  $Cookie_Value = base64_encode($hash);
  return array(
    'name' => $Cookie_Name,
    'value' => $Cookie_Value,
    'duration' => $Cookie_Duration
  );
}

// gen cookie value -- changes every week
