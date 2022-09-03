# The Purple Tree Wiki version 1

There are a small number of compoments to a Purple Tree Wiki (PTW) site. Let's go from the top down, which means starting with wiki.php.

## Overview

It's quite simple. A PTW has four main components:
1. A storage backend to store and retrieve pages. This could (as we do here) use flat files, or it could use e.g. MySQL. I think that MySQL is overkill, but writing a backend to use that instead of files in a pages directory wouldn't be hard.
2. Something to authenticate writes and uploads.
3. A renderer to turn the page source into output. We use Parsedown here, but it is easy enough to use a different renderer, possibly with a different language to write source in.
4. An Engine to coordinate the other three;

Let's look at them in order.

## Storage

A WikiStorage backend is an implementation of the WikiStorage interface. The base classes are in BaseClasses.php.

A WikiStorage backend has five methods to implement:
1. `get()` to fetch a saved page;
2. `store()` to store a saved page;
3. `count_pages()` to see how many pages there are;
4. `get_versions()` to get a list of versions of a particular page; and
5. `get_version()` to get a particular version of a file.
Storing requires authentication, which is the role of the WikiAuth subclass. 

## Authentication

An instance if WikiAuth has one job, and one method to do that job: `auth_ok()`. This returns true if and only if the user is authenticated. 
In this example, in `WikiAuthByCookie.php`, we check for a cookie. The expected value can change e.g. daily, hourly or weekly, depending on how often you want users to reauthenticate. (see `gencookie.php`).

## Renderer

The WikiRenderer subclass implements three methods:
1. `render_view()`
2. `render_edit()`
3. `render_versions()`
depending on whether the user is requesting to view, edit or see previous versions of a page respectively.

The renderer in this example uses Parsedown to render the Markdown source into HTML, and uses simple template files to frame the output of Parsedown. (See `view_template_md.html`, `edit_template.html` and `versions_template_md.html`.)

## Engine

The WikiEngine coordinates the other components. It has a single method: `go()`.
In this example, the engine, `WikiEngine2` takes instances of `WikiStorage`, `WikiRenderer` and `WikiAuth`.

The engine then looks at the URI, extracts the page name, and the action, if one is provided (default is *view*). It then fetches the source, unless POSTing, and uses the WikiRenderer instance to render either a view of the page, a simple editing page for it, or compiles a list of previous versions of a page.

In case of a POST, the engine uses the WikiAut h instance to see whether a write is allowed (and prints an error message if not).

## Cookie Authentication

This wiki, being designed for a personal wiki, assumes that there is either a single person, of a small group of friends editing. There are no accounts, and two example ways to authenticate. One is by typing a password into an HTML input box; the other is by visiting a PHP script with an obscure and unlinked location (so the URI functions as a password of sorts). Either of these sets a cookie, and the `auth_ok()` method simply looks to see if thta cookie is defined and has the correct value.

The generation of the cookie is factored into the file `gencookie.php` so that all authentication code refers to the same cookie. By basing the cookie value upon the date, e.g. upon the week in the year, users can be forced to reauthenticate. It is easy enough to plumb in any authentication you like. The code that sets the cookie is separate from the Wiki itself. In this case, there is `auth.php` and `LetMeInPrettyPlease/index.php` as examples.

The `can_i_edit.php` file shows how to write a simple PHP script that lets a user know if they are authenticated.

## Image Upload

You include images by putting `[alt text](url)` in your Markdown source. Image uploading is independent of the main code of the Wiki. In this case, the only thing they have in common is authentication (the file at `image_upload/index.php` checks for the cookie, and prints an error if it's not there). The rest of the code for uploading, modulo a few minor adaptations, comes from the blog article at: https://artisansweb.net/drag-drop-file-upload-using-javascript-php/

## Conclusion

So that's it. No React, no virtual DOM, no 100000s lines of Wordpress, nor 100000s of lines of Mediawiki. Just a simple Markdown based wiki that is *very* quick to edit: press shift-backtick, make your edits, and press shift-backtick to save. That's it.