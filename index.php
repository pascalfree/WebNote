<!DOCTYPE HTML>
<?php
/*
/  WebNote - see readme.md for details
/  Copyright (C) 2012 David Glenck - <dev@ihdg.ch>
/  Published under GNU GPLv3 
/  - see licences/gpl-3.0.txt or <http://www.gnu.org/licenses/>
*/

  require('config.php');
  require('php/init.php');
?>
<html>
  <head>
    <title>WebNote</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="dep/css/font-awesome.css" type="text/css">
    <!--[if IE 7]>
	    <link rel="stylesheet" href="dep/css/font-awesome-ie7.css" type="text/css">
    <![endif]-->  

    <script src="dep/js/jquery-1.8.3.js"></script>
    <script src="dep/js/jquery-ui-1.8.24.custom.min.js"></script>
    <link rel="stylesheet" href="dep/css/smoothness/jquery-ui-1.8.24.custom.css" type="text/css">

    <script src="dep/js/hallo.js"></script>
    <script src="dep/js/to-markdown.js"></script>
    <script src="dep/js/showdown.js"></script>

    <script src="js/run.js"></script>
    <link rel="stylesheet" href="css/index.css" type="text/css">
    <script>
      var WN_FILE_FORMAT = "<?=WN_FILE_FORMAT ?>";
    </script>

    <link rel="icon" type="image/x-icon" href="css/icons/favicon32.ico">
  </head>

  <body>

    <div id="toolbar">
      <span id="page_title">WebNote 0.1</span>
    </div>

    <div id="statusbar">
       <span id="status_text"> Welcome! </span>
    </div>

    <div id="content_frame">
      <div id="content_body">
        <?php foreach( $WN_notes as $note ): 
          if( 'md' == WN_FILE_FORMAT ) {
            $note->text = markdown($note->text); //convert markdown to html
          } 
          ?>
          <div class="note" id="note_<?=$note->id ?>" data-id="<?=$note->id ?>">
            <button class="delete_button" title="Double-click to delete">x</button>
            <div class="title"><?=$note->title ?></div>
            <<?=('txt'==WN_FILE_FORMAT)?'pre':'div'; ?> class="text"><?=$note->text ?></<?=('txt'==WN_FILE_FORMAT)?'pre':'div'; ?>>
            <button class="save_button">save</button>
          </div>
        <?php endforeach; ?>
        <div id="note_template" class="note">
          <button class="delete_button" title="Double-click to delete">x</button>
          <div class="title"></div>
          <<?=('txt'==WN_FILE_FORMAT)?'pre':'div'; ?> class="text"></<?=('txt'==WN_FILE_FORMAT)?'pre':'div'; ?>>
          <button class="save_button">save</button>    
        </div>
        <button id="new_note" class="add_button">+</button>
      </div>
    </div>

    <div id="dialog_delete" title="Delete?"></div>

    <div id="dialog_about" title="WebNote 0.1">
      <h3>About WebNote</h3>
      <p>WebNote is a simple webapp that allows you to save notes on your webserver and access them through your browser.</p>
      <a href="https://github.com/pascalfree/WebNote">Visit WebNote on GitHub for details</a>
      <h3>Settings</h3>
      <p>File Format: <?=WN_FILE_FORMAT ?><br>
      Backup: <?=WN_BACKUP?'yes':'no' ?></p>
      <h3>Dependencies</h3>
      <ul>
        <li><a href="http://jquery.com/">Jquery</a> : MIT License</li>
        <li><a href="http://jqueryui.com/">Jquery UI</a> : MIT License</li>
        <li><a href="http://hallojs.org/">hallo.js</a> : MIT License</li>
        <li><a href="http://fortawesome.github.com/Font-Awesome/">Font-Awesome</a> :  CC BY 3.0</li>
        <li><a href="https://github.com/domchristie/to-markdown">to-markdown</a> : MIT License</li>
        <li><a href="https://github.com/coreyti/showdown">showdown</a> : BSD-style open source license</li>
        <li><a href="https://github.com/michelf/php-markdown/">php-markdown</a> : BSD-style open source license</li>
      </ul>
      <h3>License</h3>
      <p>Copyright (C) 2012 David Glenck</p>
      <p>This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.</p>
      <p>This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.</p>
      <p>You should have received a copy of the GNU General Public License
      along with this program.  If not, see <a href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/</a>.</p>
    </div>

  </body>
</html>
