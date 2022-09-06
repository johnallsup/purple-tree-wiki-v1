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
abstract class WikiEngine {
  public abstract function go();
}
abstract class WikiAuth {
  public abstract function auth_ok(array $vars) : bool;
}
abstract class WikiRenderer {
  public abstract function render_view(array $vars): void;
  public abstract function render_edit(array $vars): void;
  public abstract function render_versions(array $vars): void;
}
abstract class WikiStorage {
  public abstract function get(string $word): ?string;
  public abstract function store(string $word, string $src): void;
  public abstract function count_pages(): int;
  public abstract function get_versions(string $word): array;
  public abstract function get_version(string $word, int $version);
  public abstract function page_exists(string $word);
  public abstract function get_recent(int $n);
  public abstract function get_all_words();
  public abstract function get_mtime(string $word);
}
