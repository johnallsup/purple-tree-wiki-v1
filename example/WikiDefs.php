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
define("HOMEPAGE","HomePage");
define("WIKIWORD_REGEX","/[A-Z][A-Za-z0-9]*[A-Z][A-Za-z0-9]*/");
define("DEFAULT_ACTION","view");
define('MAX_PAGES',1024);
define('PROTECTED_PAGES',array("NotAWikiWord","TooManyPages"));
