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
include_once("BaseClasses.php");
include_once("Parsedown.php");

class WikiRendererParsedown1 extends WikiRenderer {
  public function __construct() {
  }
  public function render_view(array $vars): void {
    $word = $vars["WIKI_WORD"];
    $source = $vars["WIKI_PAGE_SOURCE"];
    
    $result = preg_replace_callback_array(array(WIKIWORD_REGEX => [$this,"WikiWord_to_link"]),$source);
    $vars['WIKI_PAGE_SOURCE'] = $result;
    $this->render_view_real($vars);
  }
  public function render_view_real(array $vars): void {
    $source = $vars['WIKI_PAGE_SOURCE'];

    $parsedown = new Parsedown();
    $result = $parsedown->text($source);
    
    $vars["WIKI_PAGE_BODY"] = $result;
    $template = file_get_contents("view_template_md.html");
    $this->render_template($template,$vars);
  }
  public function render_edit(array $vars): void {
    if( $vars["WIKI_ERROR_STRING"] ) {
      $vars["WIKI_ERROR_MESSAGE"] = $this->format_error_message($vars["WIKI_ERROR_STRING"]);
    } else {
      $vars["WIKI_ERROR_MESSAGE"] = "";
    }
    
    $template = file_get_contents("edit_template.html");
    $this->render_template($template,$vars);
  }
  public function render_versions(array $vars): void {
    $versions = $vars['WIKI_VERSIONS'];
    $word = $vars['WIKI_WORD'];
    $body = "";
    if( count($versions) == 0) {
      $this->vars['WIKI_PAGE_SOURCE'] = "Word $word has no versions.";
    } else {
      foreach( $versions as $version) {
        $body .= "1. [".date(DATE_COOKIE,intval($version))."]($word?version=$version)\n";
      }
      $body .= "1. [current]($word)\n";
    }
    $parsedown = new Parsedown();
    $rendered = $parsedown->text($body);
    
    $vars["WIKI_PAGE_BODY"] = $rendered;
    $template = file_get_contents("versions_template_md.html");
    $this->render_template($template,$vars);
  }

  private function protect(string $content): string {
    $key = sprintf("WIKI_PROTECT_%08d",$this->protect_count++);
    $this->protected[$key] = $content;
    return $key;
  }
  private function WikiWord_to_link(array $match): string {
    $word = $match[0];
    return "[$word]($word)";
  }
  private function edit_link(string $word): string {
    return "<div class='edit_link'><a href='$word?edit'>Edit</a></div>";
  }
  private function render_template(string $template, array $vars): void {
    foreach($vars as $key => $value) {
      if( is_string($value) ) { # silently ignore array vars
        $template = str_replace($key,$value,$template);
      }
    }
    echo $template;
    exit();
  }
  private function format_error_message(string $err_msg): string {
    return "<p class='error'>$err_msg</p>\n";
  }
}
