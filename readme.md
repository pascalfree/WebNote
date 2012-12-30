# WebNote #

WebNote is a simple webapp that allows you to save notes on your webserver and access them through your browser. It requires PHP and the permission to read/write files on the server side. The frontend is written in HTML5 and has been tested in Firefox 17 and Chrome 22.

It uses [hallo.js 1.0](https://github.com/bergie/hallo) to make the notes editable. A list of all dependencies is found at the bottom.

WebNote is [available on GitHub](https://github.com/pascalfree/WebNote)

# Demo #

[Demo](http://webnote.ihdg.ch/)  
username: demo  
password: webnote

# Installation #

1. Change username and password in the `.htpasswd` file in the root directory. Find a htpasswd generator or use `htpasswd -n username` in a linux terminal. Also Adjust the path to your `.htpasswd` file in `.htaccess`.
2. Adjust the settings in `config.php`.
3. Upload all files to your Server
4. Set the permissions of the folder "notes" and all its sub-folders and files to 777
5. Open the app in a browser.

# Known Issues #

## Password protection & Multiuser ##
For now it is not possible to have multiple user on a single installation of WebNote, but it is planned for future releases.
Also in the current version, the login is a simple `.htpasswd` protection. Without the protection WebNote is available (readable and editable) for everybody, which is not recommended.
A better login feature is planned for future releases.

## Sorting Notes ##
Currently, notes are displayed in alphabetical order. If a new note is created or renamed its position might change when reloading the page. In future releases it should be possible to position the notes freely on the screen.

## Markdown support ##
Markdown support is experimental and its not enabled by default (you can change it in `config.php`). One aim is to have stable markdown support some day. But it is already usable in the current state.
Currently, notes are saved as html by default. Saving as .txt is also possible but text formatting will be lost.

## Other ##
* inserted notes don't have animated buttons, because events are not copied from jquery-ui element.

# Roadmap #

* Check support for other browsers.
* CTRL + S shortcut to save notes
* autosave every 5 seconds (if modified)
* Custom note colors (save in SQLight database)
* Custom note position (SQLight)
* Resizable notes (SQLight)
* Custom note file format
* Export notes!
* Multi language support
* Search function
* Notifications


# Copyright & Licenses #

Copyright 2012 David Glenck - <dev@ihdg.ch>

WebNote is published under the GNU GPLv3. See license.txt.

Other authors and Licenses are listed under [Dependencies](#Dependencies).


## Dependencies ##

All files belonging to the dependencies are in the folder "dep".

* [Jquery](http://jquery.com/) : MIT License
* [Jquery UI](http://jqueryui.com/) : MIT License
* [hallo.js](http://hallojs.org/) : MIT License
* [Font-Awesome](http://fortawesome.github.com/Font-Awesome/) :  CC BY 3.0
* [to-markdown](https://github.com/domchristie/to-markdown) : MIT License
* [showdown](https://github.com/coreyti/showdown) : BSD-style open source license
* [php-markdown](https://github.com/michelf/php-markdown/) : BSD-style open source license
