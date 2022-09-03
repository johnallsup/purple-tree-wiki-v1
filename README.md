# purple-tree-wiki-v1
The first proper version of The Purple Tree Wiki -- a very simple, quick to edit WikiClone

A while back, on Youtube, I made a tutorial video showing how to write a *very*
minimalist WikiClone in around 100 lines of PHP.

Having done that, I worked on enhancements, essentially arriving at what I now
use for my personal wikis, instead of the previous, very hacky and messy thing
I made out of a WikiClone called WabiSabi (which appears to be no longer
online). So I came up with this, which I call the PurpleTreeWiki.

An example is online [here](http://thepurpletree.uk/v1/).

## Attribution
Everything except for [Parsedown](https://github.com/erusev/parsedown),
[Highlight.js](https://highlightjs.org/), and the [image uploader](https://artisansweb.net/drag-drop-file-upload-using-javascript-php/) was written by me and is released under the MitLicense.

Parsedown, like the PurpleTreeWiki, is released under the MitLicense [(see here)](https://github.com/erusev/parsedown/blob/master/LICENSE.txt).

Highlight.js is available under the BsdThreeClauseLicense [(see here)](https://github.com/highlightjs/highlight.js/blob/main/LICENSE).

I have no idea what license, if any, the image uploader code is under. I'll update this bit of the Readme once I know.

Those two packages, rather less minimalist than this Wiki, comprise around three-quarters
of the total number of lines of source code in this wiki (approximately 3000 lines out of 4000).
The remainder that I wrote is 800-1000 lines of PHP, HTML, CSS and JavaScript.
It is not heavily commented, but is small enough, and hopefully well-organised enough,
that any competent web programmer could read the whole thing (apart from Parsedown and Hightlight.js)
and understand how it works.

## Authentication
It is up to use how you do authentication. I have some straightforward method
that sets a cookie, and the WikiEngine then looks for this cookie to see if you
are authorised. It comes down to a class that implements the `auth_ok()`
method. There are two examples: `NullAuth` leaves you wide open and always
permits saving, like the original WikiWikiWeb. Then `WikiAuthByCookie` looks for
an appropriate cookie value. One way is to have a random string that changes every hour,
day, week, or whatever. Then have a PHP script somewhere that, given acceptable input,
sets such a cookie. The `gencookie.php` script generates the expected cookie.

## Markdown markup
Pages are written in Markdown. Code is highlighted via Highlight.js.

## Htaccess
The `.htaccess` file sets up redirection so that you can use nice urls.
It looks like
```
RewriteEngine On
RewriteBase /example/
RewriteRule ^/?$ wiki.php [L]
RewriteCond %{QUERY_STRING} ^$
RewriteRule ^([A-Z][a-zA-Z0-9]*[A-Z][a-zA-Z0-9]*)$ wiki.php?word=$1 [L]
RewriteRule ^([A-Z][a-zA-Z0-9]*[A-Z][a-zA-Z0-9]*)$ wiki.php?word=$1&%{QUERY_STRING} [L]
```
It is important that you modify the `RewriteBase` line to the root of wherever you put the wiki.
You can have as many of these wikis on a domain as you like. Just put each in a different directory.
But if you make a new wiki by cloning an old one, and the deleting the pages for a fresh start,
and you *don't* update `RewriteBase` in the new wiki, redirection will cause things to point to the old wiki.
This can be an obscure thing if you don't know about this. (This had me headscratching a few times when
writing this Wiki.)
