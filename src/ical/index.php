<?PHP // $Id: index.php,v 1.1 2003/09/30 02:45:19 moodler Exp $

/// This page lists all the instances of NEWMODULE in a particular course
/// Replace NEWMODULE with the name of your module

    require_once("../../config.php");
    require_once("lib.php");
    // Incluimos la libreria del calendar
    require_once("../../calendar/lib.php");
    
    // COSAS NUESTRAS DE PRUEBA - GUILLE
	// calendar_get_mini(1,0,2);
//	$courses = array();
//	$courses[0]=2;
	$courses = get_records_sql('SELECT *, 1 FROM '.$CFG->prefix.'course');
	foreach($courses as $course){
		echo 'BEGIN:VCALENDAR' . '<br/>';
		echo 'VERSION' . '<br/>';
		echo ' :2.0' . '<br/>';
		echo 'PRODID' . '<br/>';
		echo '&nbsp;' . ':-//Moodle.org//NONSGML iCal Module v0.1 beta//EN' . '<br/>';
		// expect creating/modifying the course file
		$events = calendar_get_upcoming(array(0=>$course->id), $groups, $users, get_user_preferences('calendar_lookahead', CALENDAR_UPCOMING_DAYS), get_user_preferences('calendar_maxevents', CALENDAR_UPCOMING_MAXEVENTS));
		echo '<div class="eventlist">';
		foreach($events as $event){
			if ($event->visible){
				echo 'BEGIN:VEVENT'. '<br/>';
				echo 'UID'. '<br/>';
				echo '&nbsp;' . ':' . $event->id . '-' . $event->timestart. '<br/>';
				echo 'SUMMARY'. '<br/>';
				echo '&nbsp;' . ':' . $event->name. '<br/>';
				echo 'CATEGORIES'. '<br/>';
				echo '&nbsp;' . ':' . $course->fullname. '<br/>';
				echo 'STATUS'. '<br/>';
				echo '&nbsp;' . ':CONFIRMED'. '<br/>';
				echo 'CLASS'. '<br/>';
				echo '&nbsp;' . ':PUBLIC'. '<br/>';
				echo 'DESCRIPTION:' . $event->description . '<br/>';		
				if ($event->timeduration != 0){
					echo 'DTSTART'. '<br/>';
					echo '&nbsp;' . ':' . gmdate("Ymd\THis\Z",$event->timestart). '<br/>';
					echo 'DTEND'. '<br/>';
					echo ':' . gmdate("Ymd\THis\Z",($event->timestart + $event->timeduration)). '<br/>';
				} else {
					echo 'DTSTART'. '<br/>';
					echo '&nbsp;' . ';VALUE=DATE'. '<br/>';
					echo '&nbsp;' . ':' . gmdate("Ymd",$event->timestart). '<br/>';
					echo 'DTEND'. '<br/>';
					echo '&nbsp;' . ';VALUE=DATE'. '<br/>';
					echo '&nbsp;' . ':' . gmdate("Ymd",($event->timestart + 86400)). '<br/>';
				}
				echo 'DTSTAMP'. '<br/>';
				echo '&nbsp;' . ':' . gmdate("Ymd\THis\Z",$event->timestart). '<br/>';
				echo 'END:VEVENT'. '<br/>';
			}
		}
		echo 'END:VCALENDAR' . '<br/>';
	}
	echo '</div>';
	// FIN COSAS

    require_variable($id);   // course

    if (! $course = get_record("course", "id", $id)) {
        error("Course ID is incorrect");
    }

    require_login($course->id);

    add_to_log($course->id, "ical", "view all", "index.php?id=$course->id", "");


/// Get all required strings

    $stricals = get_string("modulenameplural", "ical");
    $strical  = get_string("modulename", "ical");

/// Print the header

    if ($course->category) {
        $navigation = "<A HREF=\"../../course/view.php?id=$course->id\">$course->shortname</A> ->";
    }

    print_header("$course->shortname: $stricals", "$course->fullname", "$navigation $stricals", "", "", true, "", navmenu($course));

/// Get all the appropriate data

    if (! $icals = get_all_instances_in_course("ical", $course)) {
        notice("There are no icals", "../../course/view.php?id=$course->id");
        die;
    }

/// Print the list of instances (your module will probably extend this)

    $timenow = time();
    $strname  = get_string("name");
    $strweek  = get_string("week");
    $strtopic  = get_string("topic");

    if ($course->format == "weeks") {
        $table->head  = array ($strweek, $strname);
        $table->align = array ("CENTER", "LEFT");
    } else if ($course->format == "topics") {
        $table->head  = array ($strtopic, $strname);
        $table->align = array ("CENTER", "LEFT", "LEFT", "LEFT");
    } else {
        $table->head  = array ($strname);
        $table->align = array ("LEFT", "LEFT", "LEFT");
    }

    foreach ($icals as $ical) {
        if (!$ical->visible) {
            //Show dimmed if the mod is hidden
            $link = "<A class=\"dimmed\" HREF=\"view.php?id=$ical->coursemodule\">$ical->name</A>";
        } else {
            //Show normal if the mod is visible
            $link = "<A HREF=\"view.php?id=$ical->coursemodule\">$ical->name</A>";
        }

        if ($course->format == "weeks" or $course->format == "topics") {
            $table->data[] = array ($ical->section, $link);
        } else {
            $table->data[] = array ($link);
        }
    }

    echo "<BR>";

    print_table($table);

/// Finish the page

    print_footer($course);

?>
