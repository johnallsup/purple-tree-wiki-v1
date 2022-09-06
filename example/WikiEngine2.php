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
include_once("WikiDefs.php");
include_once("BaseClasses.php");
include_once("NullAuth.php");

class WikiEngine2 extends WikiEngine {
  public function __construct(WikiStorage $storage, WikiRenderer $renderer, WikiAuth $auth = null) {
    $this->storage = $storage;
    $this->renderer = $renderer;
    $this->auth = $auth === null ? new NullAuth() : $auth;
    $this->options = $_GET;
    $this->action = "invalid";
    $this->max_pages = MAX_PAGES;
    $this->protected_pages = PROTECTED_PAGES;
    $this->vars = array(
      "WIKI_PAGE_SOURCE" => "",
      "WIKI_ERROR_STRING" => "",
    );
  }
  public function go(): void {
    if( array_key_exists("word",$this->options) ) {
      $word = $this->options["word"];
      if( ! preg_match(WIKIWORD_REGEX,$word) && $word != "Recent" && $word != "Index" ) {
        header("Location: NotAWikiWord", true, 303);
        exit();      
      }
    } else {
      $redirect = "Location: ".HOMEPAGE;
      header($redirect, true, 303);
      exit();
    }
  
    $this->vars["WIKI_WORD"] = $word;
    $this->vars["WIKI_SUBDIR"] = trim(dirname($_SERVER['PHP_SELF']),"/"); 
    $this->get_navbar();

    if( array_key_exists("action",$this->options) ) {
      $action = $this->options["action"];
    } else {
      $action = DEFAULT_ACTION;
    }
    $this->vars["WIKI_ACTION"] = $action;

    if( $word == "Index" ) {
      $this->render_index();
      return;
    }
    if( $word == "Recent" ) {
      $this->render_recent();
      return;
    }
    $method = "action_$action";
    if( method_exists($this,$method) ) {
      $this->$method();
      return;
    } else {
      echo "Invalid action";
      exit();
    }
  }
  private function format_navbar($navbar_source): string {
    $src = trim($navbar_source);
    if( strlen($src) > 0 ) {
      $links = preg_split("/\\s+/",$src);
      $t = "<ul class='navbar'>\n";
      foreach($links as $link) {
        if( preg_match("/^(.*)\\[([a-z0-9])\\]$/",$link,$m) ) {
          $w = $m[1];
          $k = $m[2];
          $t .= "<li><a href='$w' shortcut='$k'>$link</a></li>\n";
        } else {
          $t .= "<li><a href='$w'>$link</a></li>\n";
        }
      }
      $t .= "</ul>";
      return $t;
    } else {
      return "";
    }
  }
  private function get_navbar(): void {
    if( $this->storage->page_exists("NavBar") ) {
      $navbar_source = $this->storage->get("NavBar");
      $navbar_html = $this->format_navbar($navbar_source);
      $this->vars['NAVBAR'] = $navbar_html;
    } else {
      $this->vars['NAVBAR'] = "";
    }
  }
  private function get_source(): void {
    $word = $this->vars['WIKI_WORD'];
    if( array_key_exists("version",$this->options) ) {
      $src = $this->storage->get_version($word,$this->options['version']);
    } else {
      $src = $this->storage->get($word);
    }
    if( $src == null ) $src = $this->default_src($word);
    $this->vars['WIKI_PAGE_SOURCE'] = $src;
  }
  private function render_index(): void {
    $words = $this->storage->get_all_words();
    foreach($words as $word) {
      $md .= "* $word\n";
    }
    $this->vars['WIKI_PAGE_SOURCE'] = $md;
    $this->renderer->render_view($this->vars);
  }
  private function render_recent(): void {
    $words = $this->storage->get_recent(50);
    foreach($words as $word) {
      $mtime = $this->storage->get_mtime($word);
      $fmtime = strftime("%c",$mtime);
      $md .= "* $word *($fmtime)*\n";
    }
    $this->vars['WIKI_PAGE_SOURCE'] = $md;
    $this->renderer->render_view($this->vars);
  }
  private function action_view(): void {
    $this->get_source();
    $this->renderer->render_view($this->vars);
  }
  private function action_versions(): void {
    $word = $this->vars['WIKI_WORD'];
    $versions = 
    $this->vars['WIKI_VERSIONS'] = $this->storage->get_versions($word);
    $this->renderer->render_versions($this->vars);
  }
  private function action_edit(): void {
    if( $_SERVER["REQUEST_METHOD"] == "POST") {
      $this->handle_save();
      return;
    }
    $this->get_source();
    $this->renderer->render_edit($this->vars);
  }
  private function handle_save(): void {
    $word = $this->vars["WIKI_WORD"];
    $this->get_source();
    $old_src = trim($this->vars["WIKI_PAGE_SOURCE"]);
    if( array_key_exists("source", $_POST)) {
      $this->vars["WIKI_PAGE_SOURCE"] = trim($_POST["source"]);
    } else {
      $this->vars["WIKI_PAGE_SOURCE"] = "No source POSTed";
      $this->save_error("No source POSTed");
      return;
    }
    
    if( 
      ($this->storage->count_pages() > $this->max_pages)
      && (!$this->storage->page_exists($word)))
    {
      $this->save_error("Too many pages.");    
      return;
    }
    
    if( strcmp($this->vars["WIKI_PAGE_SOURCE"],$old_src) == 0) {
      $this->redirect_to_view();
    }

    if( in_array($word, $this->protected_pages)) {
      $this->save_error("Page $word is protected.");
      return;
    }

    if( $this->auth->auth_ok($this->vars) ) {
      $this->storage->store($word,$this->vars["WIKI_PAGE_SOURCE"]);
      $this->redirect_to_view();
    } else {
      $this->save_error("Permission denied.");
      return;
    }
  }
  private function redirect_to_view() : void {
    $uri = strtok($_SERVER["REQUEST_URI"],"?");
    header("Location: $uri", true, 303);
    exit();
  }
  private function save_error(string $err_msg): void {
    $this->vars["WIKI_ERROR_STRING"] = $err_msg;
    $this->renderer->render_edit($this->vars);
    return;
  }
  private function invalid_action(): void {
    echo "Invalid action.";
    exit();
  }
  private function default_src(string $word): string {
    return "Page $word does not exist yet.";
  }
}
