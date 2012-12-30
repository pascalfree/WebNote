/*
/  WebNote - see readme.md for details
/  Copyright (C) 2012 David Glenck - <dev@ihdg.ch>
/  Published under GNU GPLv3 
/  - see licences/gpl-3.0.txt or <http://www.gnu.org/licenses/>
*/

//## INITIALIZE ##//
$(function() {
  hallo_init(); // initialize hallo toolbar

  ///// initialize button actions
  $('.save_button').button().hide();
  $('.add_button').button({ icons: { primary: 'ui-icon-plus' }, text: false })
  $('.delete_button').button({ icons: { primary: 'ui-icon-close' }, text: false });
  //$('.delete_button').tooltip(); //not until hallo.js runs on jquery-ui.1.9

  //show "about" when clicking page title
  $('#page_title').on('click', about);

  // save button
  $('#content_body').on('click','.save_button', function() {
    note.write( $(this).parent('.note').data('id') );
  })

  // delete button
  $('#content_body').on('dblclick','.delete_button', function() {
    note.delete( $(this).parent('.note').data('id') );
  })
  .on('touchend','.delete_button', function() { //support mobile devices
    var id = $(this).parent('.note').data('id');
    $('#dialog_delete').dialog({
      buttons: [
        { text: "Cancel", click: function() { $( this ).dialog( "close" ); } },
        { text: "Delete", color: "red", click: function() { 
          note.delete( id );
          $( this ).dialog( "close" );
        }}
      ],
      show: 'fade',
      dialogClass: 'delete_dialog',
      resizable: false
    });
  });

  // add button
  $('.add_button').on('click', function() {
    note.write( null, function(data) { //create new note
      note.read( data.id );            // display note
    });
  });

  // note hover (show delete button)
  $('#content_body').on('mouseenter', '.note', function() {
    $(this).children('.delete_button').stop(true,true).fadeIn(200);
  })
  .on('mouseleave', '.note', function() {
    $(this).children('.delete_button').stop(true,true).fadeOut(200);
  })
  // show save button if something is edited
  .on('hallomodified', '.note', function() {
    $(this).find('.save_button:hidden').stop(true,true).show('blind');
  })
  // highlight save button, if unsaved input is blurred
  .on('blur', '.text, .title', function() {
    $(this).parent().find('.save_button:visible').stop(true,true).effect('highlight', {color: '#ff4444'}, 6000);
  })
});

// initializes hallo editor
function hallo_init() {
  // activate hello on title and text elements
  $('.title').hallo();

  // choose toolbar plugins depending on format
  var plugins = {}
  if( 'html' == WN_FILE_FORMAT ) {
    plugins.plugins = {
        'halloformat': {},
        'halloblock': {},
        'hallojustify': {},
        'hallolists': {}
      }
  } else if( 'md' == WN_FILE_FORMAT ) {
    plugins.plugins = {
        'halloformat': {},
        'halloblock': {},
        'hallolists': {}
      }
  }
  $('.text').hallo(plugins);
}

//#end INITIALIZE ##//

//## NOTE FUNCTIONS ##//
var note = {};

// convert note from html to object
note.serialize = function(id) {
  return  {
            id : id || null,
            title : $('#note_'+id+' .title').text() || 'Fresh Note', //title must never be empty
            text : file_format.get('#note_'+id+' .text')
          }
}

// save note to server
note.write = function(id, callback) {
  // prepare params
  var d = note.serialize(id);
  d.action = 'write';
  // send to server
  $.post('./php/ajax.php', d, function(data) {
    // notify user
    if(data.error) { status('Could not save note. Sorry.'); return; }
    status( 'Note saved' );
    // call callback function (if any)
    if( callback ) {
      callback(data); 
    }
  });
}

// get and show note from server
note.read = function(id) {
  $.post('./php/ajax.php', { action: 'read', id: id }, function(data) {
    var add_button = $('#new_note').hide();      // hide button temporarily
    var newnote = $('#note_template').clone();   // clone note template
    newnote.attr('id', "note_"+data.id);         // insert id
    newnote.data('id', data.id);                 // save id as data attribute
    newnote.children('.title').html(data.title); // insert title
    file_format.set(newnote.children('.text'), data.text);  // convert format of text
    newnote.hide().insertBefore('#note_template').show("drop", {direction: "up"}, function() { // insert note
      add_button.stop().fadeIn();                //show add-button again after finishing animation
    });
    hallo_init();                                //reinit hallo
    status( 'Note created' );                    // notify user
  });
}

// save note to server
note.write = function(id, callback) {
  // prepare params
  var d = note.serialize(id);
  d.action = 'write';
  // send to server
  $.post('./php/ajax.php', d, function(data) {
    //notify user
    if(data.error) { status('Could not save note. Sorry.'); return; }
    status( 'Note saved' );
    // hide save button again
    $('#note_'+id+' .save_button').stop(true,true).hide('blind');
    // call callback function (if any)
    if( callback ) {
      callback(data); 
    }
  });
}

// delete note from server
note.delete = function(id) {
  $.post('./php/ajax.php', { action: 'delete', id: id }, function(data) {
    // notify user
    if(data.error) { status('Could not delete note. Sorry.'); return; }
    status( 'Note deleted' );
    // hide button temporarily
    var add_button = $('#new_note').hide();
    //fade out deleted note
    $('#note_'+id).hide("drop", {direction: "down"}, function() {
      $(this).remove();
      add_button.stop().fadeIn(); // show button again
    });
  });
}

//#end NOTE FUNCTIONS ##//

//## INTERFACE FUNCTIONS ##//

//writes msg to statusbar
function status(msg) {
  $('#status_text').text(msg);

  //remove message after some time
  setTimeout(function() {
    $('#status_text').text('');
  }, 6000)
}

//show dialog with information about the app and settings
function about() {
  $('#dialog_about').dialog({show: 'fade', height: 400, width: 480});
} 

//#end INTERFACE FUNCTIONS ##//

//## FILE FORMAT FUNCTIONS ##//

// format: html, md, txt
var file_format = {};

// returns content of an html element in given format
// format defaults to WN_FILE_FORMAT
file_format.get = function( element, format ) {
  format = format || WN_FILE_FORMAT;
  text = $(element).html() || '';
  if( 'html' == format ) {
    return text;
  } else if( 'txt' == format ) {
    // chrome creates <div><br></div> elements on double linebreak
    // 1. replace <br> by \r\n 2. remove empty divs 3. replace closing divs by \r\n 4. remove remaining html 5. replace &nbsp;
    return text.replace(/<br\s*\/?>/gi, '\r\n').replace(/<div>(\s*)<\/div>/gi, '$1')
               .replace(/<div>/gi, '\r\n').replace(/<[^>]*>/gi, '').replace(/&nbsp;/gi, ' ');
  } else if( 'md' == format ) {
    return toMarkdown( text );
  } else {
    status( "ERROR: Unknown format ("+format+")." );
    return "ERROR: Unknown format ("+format+").";
  }
}

// sets content of an html element to string in given format
// format defaults to WN_FILE_FORMAT
file_format.set = function( element, string, format ) {
  format = format || WN_FILE_FORMAT;
  string = string || '';  // if string is not defined input empty string
  if( 'html' == format ) {
    $(element).html( string );
  } else if( 'txt' == format ) {
    // replace \r\n by <br>
    $(element).html( string ); //.replace(/(\r\n)|(\n)/gi, '<br>').replace(/&nbsp;/gi, ' ') );
  } else if( 'md' == format ) {
    var converter = new Showdown.converter();
    $(element).html( converter.makeHtml(string) );
  } else {
    status( "ERROR: Unknown format ("+format+")." );
  }
}

//#end FILE FORMAT FUNCTIONS ##//
