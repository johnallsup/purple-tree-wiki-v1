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

class WikiStorageFlatFileVersions extends WikiStorage {
  public function __construct($vars = array()) {
    if( array_key_exists("path",$vars) ) {
      $this->pages_path = $vars['path'];
    } else {
      $this->pages_path = "pages";
    }
    if( array_key_exists("versions_path",$vars) ) {
      $this->versions_path = $vars['versions_path'];
    } else {
      $this->versions_path = "versions";
    }
  }
  public function page_exists($word): bool {
    $page_filename = $this->path_for_word($word);
    return file_exists($page_filename);
  }
  public function get($word): ?string {
    $page_filename = $this->path_for_word($word);
    if( file_exists($page_filename)) {
      return @file_get_contents($page_filename);
    } else {
      return null;
    }
  }
  public function store($word,$src): void {
    $src = preg_replace('~\R~u', "\r\n", $src); # Fix CRLF

    $page_filename = $this->path_for_word($word);
    file_put_contents($page_filename,trim($src));

    $page_version_filename = $this->path_for_version($word,time());
    file_put_contents($page_version_filename,trim($src));
  }
  public function count_pages(): int {
    $pages = glob($this->pages_path."/*.txt");
    return count($pages);
  }
  private function path_for_version($word,$version) {
    return $this->versions_path."/$word.$version.txt";
  }
  private function path_for_word($word): string {
    return $this->pages_path."/$word.txt";
  }
  public function get_versions($word): array {
    $files = glob($this->versions_path."/$word.*.txt");
    $versions = array();
    foreach( $files as $file ) {
      preg_match("/[^\\.]\\.(\\d+)\\.txt/", $file, $m);
      array_push($versions, $m[1]);
    }
    return $versions;
  }
  public function get_version($word,$version) {
    $files = glob("versions/$word.$version.txt");
    if( count($files) > 0 ) { # array has 0 or 1 entries
      return file_get_contents($files[0]);
    } else {
      echo "Version $version of word $word does not exist.";
      exit();
    }
  }
  private function revmtime($a,$b) { 
    $at = filemtime($this->path_for_word($a));
    $bt = filemtime($this->path_for_word($b));
    if( $at == $bt ) return 0;
    return ($at < $bt) ? 1 : -1;
  }
  public function get_recent(int $n) {
    $words = $this->get_all_words();
    usort($words,[$this,"revmtime"]);
    $recent = array_slice($words,0,$n);
    return $recent;
  }
  public function get_all_words() {
    $paths = glob($this->pages_path."/*.txt");
    $words = array();
    foreach($paths as $path) {
      preg_match("@([^/]*)\\.txt$@",$path,$m);
      array_push($words,$m[1]);
    }
    return $words;  
  }
  public function get_mtime(string $word) {
    $path = $this->path_for_word($word);
    if( !file_exists($path) ) {
      return 0;
    }
    return filemtime($path);
  }
}
