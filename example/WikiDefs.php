<?php
define("HOMEPAGE","HomePage");
define("WIKIWORD_REGEX","/[A-Z][A-Za-z0-9]*[A-Z][A-Za-z0-9]*/");
define('URL_REGEX',"/[a-z]+:\/\/[-a-zA-Z0-9@:%._\\+~#=]{1,256}\\.[a-zA-Z0-9()]{1,6}\\b([-a-zA-Z0-9()@:%_\\+.~#?&\\/=]*)/");
define("DEFAULT_ACTION","view");
define('PASSWORD','wiki');
define('MAX_PAGES',1024);
define('PROTECTED_PAGES',array("NotAWikiWord","TooManyPages"));
