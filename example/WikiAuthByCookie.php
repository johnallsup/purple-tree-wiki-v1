<?php
include_once("BaseClasses.php");
include_once("gencookie.php");

class WikiAuthByCookie extends WikiAuth {
  public function __construct() {
    $cookie = gencookie();
    $this->cookie_name = $cookie["name"];
    $this->cookie_period = $cookie["duration"];
    $this->cookie_value = $cookie["value"];
  }
  public function auth_ok($vars): bool {
    if( $this->check_cookie() ) {
      return true;
    }
    return false;
  }
  protected function check_cookie(): bool {
    return (bool)( 
        array_key_exists($this->cookie_name,$_COOKIE)
        && $this->cookie_value == $_COOKIE[$this->cookie_name]
      );
  }  
}
