<?PHP  // $Id: view.php,v 1.1 2003/09/30 02:45:19 moodler Exp $

/// This page prints a particular instance of ical
/// (Replace ical with the name of your module)

    require_once($CFG->dirroot.'/config.php');
    require_once($CFG->dirroot.'/calendar/lib.php');

    optional_variable($id);    // Course Module ID, or
    optional_variable($a);     // ical ID

    if ($id) {
        if (! $cm = get_record("course_modules", "id", $id)) {
            error("Course Module ID was incorrect");
        }
    
        if (! $course = get_record("course", "id", $cm->course)) {
            error("Course is misconfigured");
        }
    
        if (! $ical = get_record("ical", "id", $cm->instance)) {
            error("Course module is incorrect");
        }

    } else {
        if (! $ical = get_record("ical", "id", $a)) {
            error("Course module is incorrect");
        }
        if (! $course = get_record("course", "id", $ical->course)) {
            error("Course is misconfigured");
        }
        if (! $cm = get_coursemodule_from_instance("ical", $ical->id, $course->id)) {
            error("Course Module ID was incorrect");
        }
    }

    require_login($course->id);

    add_to_log($course->id, "ical", "view", "view.php?id=$cm->id", "$ical->id");

/// Print the page header

    if ($course->category) {
        $navigation = "<A HREF=\"../../course/view.php?id=$course->id\">$course->shortname</A> ->";
    }

    $stricals = get_string("modulenameplural", "ical");
    $strical  = get_string("modulename", "ical");

    print_header("$course->shortname: $ical->name", "$course->fullname",
                 "$navigation <A HREF=index.php?id=$course->id>$stricals</A> -> $ical->name", 
                  "", "", true, update_module_button($cm->id, $course->id, $strical), 
                  navmenu($course, $cm));

/// Print the main part of the page

    echo "YOUR CODE GOES HERE";


/// Finish the page
    print_footer($course);

?>
