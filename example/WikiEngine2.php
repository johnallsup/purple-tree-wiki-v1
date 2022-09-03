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
  // WikiEngine1 but with versions
  public function __construct(WikiStorage $storage, WikiRenderer $renderer, WikiAuth $auth = null) {
    // storage extends WikiStorage
    // renderer extends WikiRenderer
    // auth extends WikiAuth, default to null
    // if auth is null, auth_ok is assume to always return true
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
   // extract word from query string
    // var_dump($this->options);
    if( array_key_exists("word",$this->options) ) {
      $word = $this->options["word"];
      // var_dump($word);
      if( ! preg_match(WIKIWORD_REGEX,$word) ) {
        // echo "Not a wiki word";
        header("Location: NotAWikiWord", true, 303);
        exit();      
      }
    } else {
      $redirect = "Location: ".HOMEPAGE;
      // echo "redirect: $uri";
      header($redirect, true, 303);
      exit();
    }
  
    // save word to $this->vars['WIKI_WORD']
    $this->vars["WIKI_WORD"] = $word;

    // extract action from query string
    if( array_key_exists("action",$this->options) ) {
      $action = $this->options["action"];
    } else {
      $action = DEFAULT_ACTION;
    }
    $this->vars["WIKI_ACTION"] = $action;

    // see if we have a method for the action
    $method = "action_$action";
    if( method_exists($this,$method) ) {
      // if we have an action, call it
      $this->$method();
      return;
    } else {
      // if not, present an error   
      echo "Invalid action";
      exit();
    }
  }
  private function get_source(): void {
    $word = $this->vars['WIKI_WORD'];
    if( array_key_exists("version",$this->options) ) {
      $src = $this->storage->get_version($word,$this->options['version']);
    } else {
      $src = $this->storage->get($word);
    }
    // empty string is valid page content, so can't use if($src)
    if( $src == null ) $src = $this->default_src($word);
    $this->vars['WIKI_PAGE_SOURCE'] = $src;
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
    // See if this is a POST (i.e. save) request
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
    // we have a number of things to check
    // 1. did the sender include source
    // 2. is our wiki full?
    // 3. Has the page changed
    // 4. Is the page protected
    // 5. Are we authorised to save

    // 1. did the sender include source
    if( array_key_exists("source", $_POST)) {
      $this->vars["WIKI_PAGE_SOURCE"] = trim($_POST["source"]);
    } else {
      $this->vars["WIKI_PAGE_SOURCE"] = "No source POSTed";
      $this->save_error("No source POSTed");
      return;
    }
    
    // 2. is our wiki full?
    if( 
      ($this->storage->count_pages() > $this->max_pages)
      && (!$this->storage->page_exists($word)))
    {
      // $this->vars["ERROR_MESSAGE"] = "Too many pages in wiki";
      // return $renderer->render_edit($this->vars);
      // again this is getting messy
      // how an error message is returned
      // should not be specified in this function
      // thus
      $this->save_error("Too many pages.");    
      return;
    }
    
    // back in 1. (note that this will only happen if a script kiddie is playing with Postman)
    // if( array_key_exists("source", $_POST)) {
    //   $this->vars["WIKI_PAGE_SOURCE"] = $_POST["source"];
    // } else {
    //   $this->vars["WIKI_PAGE_SOURCE"] = "No source POSTed";
    //   return $this->save_error("No source POSTed");
    // }
    
    // 3. Has the page changed
    if( strcmp($this->vars["WIKI_PAGE_SOURCE"],$old_src) == 0) {
      // redirect back to view page
      // $uri = strtok($_SERVER["REQUEST_URI"],"?");
      // header("Location: $uri", true, 303);
      // exit();
      // again getting messy -- we do this twice
      $this->redirect_to_view();
    }

    // 4. Is the page protected
    // where do we initialise protected_pages
    if( in_array($word, $this->protected_pages)) {
      $this->save_error("Page $word is protected.");
      return;
    }

    // 5. Are we authorised to save
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
