<?php
//** CONFIG **//

/*
/  WebNote - see readme.md for details
/  Copyright (C) 2012 David Glenck - <dev@ihdg.ch>
/  Published under GNU GPLv3 
/  - see licences/gpl-3.0.txt or <http://www.gnu.org/licenses/>
*/

//** File Format
// Format in which the Notes will be saved on the server.
// VALUES
// 'html' (default) : allows formatting text. Depends on Browser. May result in messy html code.
// 'txt' : No formatting, but easily readable and editable files on server.
// 'md' (EXPERIMENTAL) : markdown will be converted from and to html. Allows some formatting, but is no stable yet.
// Warning: Changing this value will not convert existing notes, it just changes the way they are loaded and displayed.
//          File endings of old notes will only be changed after resaving them
define("WN_FILE_FORMAT", 'html');


//** Backup
// WebNote will save a backup of the notes, if they are edited (or resaved) or deleted. 
// This backup files will have the file ending *.bak and will be kept until, they are overwritten by another backup of a note with the same title and id.
// These backups won't be deleted automatically for now.
// VALUES
// 1 (default) : enable backups
// 0 : disable backups (If a file is deleted it's gone)
define("WN_BACKUP", "1");

?>
