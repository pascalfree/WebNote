<?php
/*
/  WebNote - see readme.md for details
/  Copyright (C) 2012 David Glenck - <dev@ihdg.ch>
/  Published under GNU GPLv3 
/  - see licences/gpl-3.0.txt or <http://www.gnu.org/licenses/>
*/

// class note creates objects which contain all information about a note
class note {
  // properties
  public $id;
  public $title;
  public $text;

  // constructor
  public function note($id, $title, $text) {
    $this->id = $id;
    $this->title = $title;
    $this->text = $text;
  } 

  // print content of note
  public function __toString() {
    $ret =  "Note \n";
    $ret .= "  id: " . $this->id;
    $ret .= "\n  title: " . $this->title;
    $ret .= "\n  text: " . $this->text;
    return $ret . "\n";
  }
}

// class loader, will load notes (files and later database information) and will make them accessible
class manager {
  const NOTEDIR = "./notes/"; //dir to notes from root directory of app

  private $path_prefix_ = ""; //prefix to root, if this is not included from a file in the root directory of the app
  private $user_;
  private $notes_ = array();
  
  // return path to notes
  private function note_path_() {
    return $this->path_prefix_.self::NOTEDIR.$this->user_;
  } 

  // return single element array of path of a note with id
  // return array of path of all notes if id == '*'
  // return false if no note was found or in case of an error
  private function get_note_list_( $id = '*' ) {
     $paths = glob( $this->note_path_() .'/*.'. $id . '.{txt,html,md}', GLOB_BRACE );
     if( empty($paths) || false === $paths ) {
       return false;
     } else {
       return $paths;
     }
  } 

  //** constructor
  public function manager( $user, $path_prefix='' ) {
    $this->user_ = $user;
    $this->path_prefix_ = $path_prefix;
  }

  //** generate and return a non-existing id
  public function generate_id() {
    $list = $this->get_note_list_();
    if( false == $list ) {
      $candidate = 0;
    } else {
      $candidate = count($this->get_note_list_());
    }
    while( false != $this->get_note_list_($candidate) ) {
      $candidate++;
    }
    return $candidate;
  }

  //** read: load note(s) from files
  public function read_note( $id = '*' ) {
    // get list of txt files of user
    $list = $this->get_note_list_($id);
    if( false == $list ) { return false; }
    // loop over list to fill notes
    $count_list = count($list);
    for( $i=0; $i<$count_list; $i++ ) {
      // extract id and title form filename
      preg_match('/(.*)\.([0-9]+).[a-zA-Z]+$/', basename( $list[$i] ), $result);
      // $result[1] == title, $result[2] == id
      $this->notes_[$result[2]] = new note( $result[2], $result[1], file_get_contents( $list[$i] ) );
    }
  }

  //** write: save a single note to file
  public function write_note( $note ) {
    $path = $this->get_note_list_( $note->id );

    // if backups are enabled and file with same id exists already -> create backup
    if( 1 == WN_BACKUP && $path != false ) {
      if( false === rename( $path[0], $path[0].".bak" )) {
        //return false on error;
        return false;
      }
    }

    //save file
    $new_path = $this->note_path_() . "/" . $note->title . "." . $note->id . "." . WN_FILE_FORMAT;
    $return = file_put_contents( $new_path , $note->text );

    //delete old file if filename has changed in any way. 
    //This is done after creating the new file, in case that fails.
    //will fail if file has been backed up. -> nobody cares
    if( $path != false  && $new_path != $path[0] ) {
      @unlink( $path[0] );
    }

    //return false if error occurs, on writing file
    return false !== $return;
  }


  //** delete: delete a single note file
  public function delete_note( $id ) {
    $path = $this->get_note_list_( $id );
    //return false if note with id does not exists
    if( false == $path ) {
      return false;
    }

    //"delete" file. Return true if successful, false otherwise
    if( 1 == WN_BACKUP ) {
      return false !== rename( $path[0], $path[0].".bak" ); // with backup
    } else {
      return false !== unlink( $path[0] ); //without backup
    }
  }


  //** accessor, get a note object.
  // return note matching id
  // return complete array if id == *
  // return empty array, if no note with given id was found
  public function get($id = '*') {
    if( $id == '*' ) { return $this->notes_; }
    if( !isset($this->notes_[$id]) ) { return array(); }
    return $this->notes_[$id];
  }

  // print all loaded notes
  public function __toString() {
    $ret = "";
    foreach( $this->notes_ as $note ) {
      $ret.= (string)$note;
    }
    return $ret;
  }
}

?>
