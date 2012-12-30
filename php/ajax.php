<?php
/*
/  WebNote - see readme.md for details
/  Copyright (C) 2012 David Glenck - <dev@ihdg.ch>
/  Published under GNU GPLv3 
/  - see licences/gpl-3.0.txt or <http://www.gnu.org/licenses/>
*/

// answer in json
header('Content-Type: application/json  charset=UTF-8');

/*//  ERROR CODES  ////
  1xx: general ajax errors
  2xx: error in writing
  3xx: error in reading
  4xx: error while deleting
*///

/*Debug*
error_reporting(E_ALL);
ini_set('display_errors', '1');
//*/

//includes
require('../config.php');
require('manager.php');

// check if action is set (read/write/delete)
// return error if not.
if( !isset( $_POST['action'] ) ) {
  echo '{"error": 100, "message": "Action must be set."}'; die;
} else {
  $arg_action = $_POST['action'];
}

// start the manager
// only one user for now. Use path prefix
$manager = new manager('admin', '../');

//## Write a note ##//
if( "write" == $arg_action ):
  // create new note if no id is given
  if( !isset($_POST['id']) || $_POST['id'] == '' ) { $arg_id = $manager->generate_id(); }
  else{ $arg_id = $_POST['id']; }

  // title must not be empty -> space
  if( !isset($_POST['title']) ) { $title = ' '; }
  else{ $title = $_POST['title']; }

  if( !isset($_POST['text']) ) { $text = ''; }
  else{ $text = $_POST['text']; }

  //construct note
  $note = new note( $arg_id, $title, $text);

  // save the note
  if( !$manager->write_note($note) ) {
    echo '{"error": 200, "message": "Could not write the note."}'; die;
  }
  echo '{"id": ',$arg_id,'}';


//## Read a note ##//
;elseif( "read" == $arg_action ):
  // return error if 'id' is not set
  if( !isset($_POST['id']) || $_POST['id'] == '' ) {
    echo '{"error": 301, "message": "\'id\' must be set for action (read)."}'; die;
  } else { 
    $arg_id = $_POST['id']; 
  }

  // read note
  $manager->read_note($arg_id);
  $note = $manager->get($arg_id);

  // return note (or error).
  if( empty($note) ) {
    echo '{"error": 302, "message": "Note with id (',$arg_id,') could not be found."}'; die;
  } else {
    echo json_encode( $note );
  }


//## Delete a note ##//
;elseif( "delete" == $arg_action ):
  // return error if 'id' is not set
  if( !isset($_POST['id']) || $_POST['id'] == '' ) {
    echo '{"error": 401, "message": "\'id\' must be set for action (read)."}'; die;
  } else { 
    $arg_id = $_POST['id']; 
  }

  // delete the note
  // return id of note if successful or an error otherwise
  if( !$manager->delete_note($arg_id) ) {
    echo '{"error": 402, "message": "Could not delete the note."}'; die;
  }
  echo '{"id": ',$arg_id,'}';


//## Action not found ##//
;else:
  echo '{"error": 101, "message": "Action not found (',$arg_action,')"}'; die;
endif;
?>
