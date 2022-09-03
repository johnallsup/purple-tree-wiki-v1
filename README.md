# purple-tree-wiki-v1
The first proper version of The Purple Tree Wiki -- a very simple, quick to edit WikiClone

A while back, on Youtube, I made a tutorial video showing how to write a *very*
minimalist WikiClone in around 100 lines of PHP.

Having done that, I worked on enhancements, essentially arriving at what I now
use for my personal wikis, instead of the previous, very hacky and messy thing
I made out of a WikiClone called WabiSabi (which appears to be no longer
online). So I came up with this, which I call the PurpleTreeWiki.

## Attribution
Everything except for [Parsedown](https://github.com/erusev/parsedown) and
[Highlight.js](https://highlightjs.org/) was written by me and is released under the MitLicense.

Parsedown, like the PurpleTreeWiki, is released under the MitLicense [(see here)](https://github.com/erusev/parsedown/blob/master/LICENSE.txt).

Highlight.js is available under the BsdThreeClauseLicense [(see here)](https://github.com/highlightjs/highlight.js/blob/main/LICENSE).

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
