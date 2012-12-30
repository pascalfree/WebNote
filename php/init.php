<?php
/*
/  WebNote - see readme.md for details
/  Copyright (C) 2012 David Glenck - <dev@ihdg.ch>
/  Published under GNU GPLv3 
/  - see licences/gpl-3.0.txt or <http://www.gnu.org/licenses/>
*/

/*//Debug
error_reporting(E_ALL);
ini_set('display_errors', '1');
//*/

// includes
require('manager.php');
require('dep/php/markdown.php');

// The Initializer:
// loads notes and saves them into an array

$load = new manager('admin'); // only one user (for now)
$load->read_note();           // load all notes
$WN_notes = $load->get();     // get loaded notes
?>
