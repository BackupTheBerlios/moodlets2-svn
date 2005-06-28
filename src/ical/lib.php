<?PHP  // $Id: lib.php,v 1.3 2004/06/09 22:35:27 gustav_delius Exp $

/**
 * Library of functions and constants for iCal module.
 * @author Andreas Calvo Gomez <andreas.calvo01@campus.upf.es>
 * @author Guillermo Miranda Alamo <guillermo.miranda01@campus.upf.es>
 */


	/**
	 * Since it's based on the Calendar module, we should at least read
	 * from its lib.php
	 */
    require_once($CFG->dirroot.'/config.php');
    require_once($CFG->dirroot.'/calendar/lib.php');

/**
 * This functions parses all the events, and creates one iCal
 * (.ics) file per course, storing them in the folder specified in the
 * module's configuration.
 * ToDo: site's events.
 */
function ical_parse (){
	global $CFG;
	
	set_config('prefix_ical_path', '../../webdav');
    
    // Retrieves all the courses
    $courses = get_records_sql('SELECT *, 1 FROM '.$CFG->prefix.'course');
    
    // Firstly, we must check if the path to store the iCal files exists...
    if (is_dir($CFG->ical_path)){
		// Now, for each course...
		foreach($courses as $course){
			$count = get_record_sql('SELECT *, 1 FROM '.$CFG->prefix.'event WHERE courseid='.$course->id);
			if ( (!empty($count)) && ($course->id != 0) && ($fp = @fopen($CFG->ical_path . '/' . $course->shortname . '.ics', "w")) ){
				// Write the header
				$write = fwrite($fp,"BEGIN:VCALENDAR\nVERSION\n :2.0\nPRODID\n :-//Moodle.org//NONSGML iCal Module v0.1 beta//EN");
				// expect creating/modifying the course file
				$events = calendar_get_upcoming(array(0=>$course->id), $groups, $users, get_user_preferences('calendar_lookahead', CALENDAR_UPCOMING_DAYS), get_user_preferences('calendar_maxevents', CALENDAR_UPCOMING_MAXEVENTS));
				// For all the events in the current course
				foreach($events as $event){
					// If the event is visible
					if ($event->visible){
						$write = fwrite($fp,"\nBEGIN:VEVENT\nUID\n :" . $event->id . "-" . $event->timestart. "\nSUMMARY\n :" . $event->name . "\nCATEGORIES\n :" . $course->fullname . "\nSTATUS\n :CONFIRMED\nCLASS\n :PUBLIC\nDESCRIPTION:" . $event->description);		
						// We check if it has a stablished duration
						if ($event->timeduration != 0){
							// It does: we print the event time information, with the duration
							$write = fwrite($fp,"\nDTSTART\n :" . gmdate("Ymd\THis\Z",$event->timestart) . "\nDTEND\n :" . gmdate("Ymd\THis\Z",($event->timestart + $event->timeduration)));
						} else {
							// However, if it doesn't, we assume that the event lasts 24hrs
							$write = fwrite($fp,"\nDTSTART\n ;VALUE=DATE\n :" . gmdate("Ymd",$event->timestart) . "\nDTEND\n ;VALUE=DATE\n :" . gmdate("Ymd",($event->timestart + 86400)));
						}
						// And now for the timestamp and we finish this event
						$write = fwrite($fp,"\nDTSTAMP\n :" . gmdate("Ymd\THis\Z",$event->timemodified) . "\nEND:VEVENT");
					}
				}
				// This calendar file (course file) has ended :)
				$write = fwrite($fp,"\nEND:VCALENDAR");
				fclose($fp);
			}
		}
    } else {
    	echo 'The directory ' . $CFG->prefix_ical_path . ' is not valid.';
    }
}

function ical_cron () {
/// Function to be run periodically according to the moodle cron
/// This function searches for things that need to be done, such 
/// as sending out mail, toggling flags etc ... 

    global $CFG;
    
    ical_parse();

    return true;
}

?>
