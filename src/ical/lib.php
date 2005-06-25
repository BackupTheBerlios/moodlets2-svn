<?PHP  // $Id: lib.php,v 1.3 2004/06/09 22:35:27 gustav_delius Exp $

/// Library of functions and constants for module NEWMODULE
/// (replace NEWMODULE with the name of your module and delete this line)

// Since it's based on the Calendar module, we should at least read
// from its lib.php
require_once("../../calendar/lib.php");


function ical_parse () {
	// imprimimos a fichero
}

function ical_calendar_upcoming_events () {
	// cogemos todos los cursos
}

function ical_event ($event) {
	// parseamos el evento
}

function ical_cron () {
/// Function to be run periodically according to the moodle cron
/// This function searches for things that need to be done, such 
/// as sending out mail, toggling flags etc ... 

    global $CFG;

    return true;
}

?>
