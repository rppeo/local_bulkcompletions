<?php

/**
 * Get Completions API Extensions
 *
 * @package   local_bulkcompletions
 * @author    Brendan Harris
 * @copyright 2018 Regional Paramedic Program for Eastern Ontario
 * @copyright Based on a web services template by Jerome Mouneyrac, (c) 2011
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");

class local_bulkcompletions_external extends external_api {
    
    /**
     * Get Completions Parameters
     * 
     * Returns description of get_completions() parameters
     *
     * @return external_function_parameters
     */
    public static function get_completions_parameters() {
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, 'Course id')
            )
        );
    }

    /**
     * Returns Completions, per course
     * Excludes anyone who hasn't actually completed the course yet (you can use core_enrol_get_enrolled_users if that's
     * what you want), and any users who have been deleted in Moodle
     * 
     * @param $courseid
     * @throws moodle_exception
     */
    public static function get_completions($courseid) {
        global $CFG, $USER, $DB;
    
        require_once($CFG->dirroot . '/course/lib.php');
        require_once($CFG->dirroot . "/user/lib.php");
    
        $params = self::validate_parameters(
            self::get_completions_parameters(),
            array(
                'courseid'=>$courseid
            )
        );
        
        $course = $DB->get_record('course', array('id'=>$courseid), '*', MUST_EXIST);
        $coursecontext = context_course::instance($courseid, IGNORE_MISSING);
        if ($courseid == SITEID) {
            $context = context_system::instance();
        } else {
            $context = $coursecontext;
        }
        try {
            self::validate_context($context);
        } catch (Exception $e) {
            $exceptionparam = new stdClass();
            $exceptionparam->message = $e->getMessage();
            $exceptionparam->courseid = $params['courseid'];
            throw new moodle_exception('errorcoursecontextnotvalid' , 'webservice', '', $exceptionparam);
        }
        
       course_require_view_participants($context);
        
        $completions = $DB->get_records_sql(
            "SELECT c.userid, u.username, u.email, c.course, c.timeenrolled, c.timestarted, c.timecompleted ".
            "FROM {course_completions} c left join {user} u ON c.userid=u.id ".
            "WHERE c.timecompleted is not null AND u.deleted=0 AND c.course=?",
            array($courseid));
        
        $results = array();
        $warnings = array();
        
        foreach ($completions as $completion) {
            $results[] = array(
               'userid'          => $completion->userid,
               'username'        => $completion->username,
               'email'           => $completion->email,
               'course'          => $completion->course,                
               'timeenrolled'    => $completion->timeenrolled,
               'timestarted'     => $completion->timestarted,
               'timecompleted'   => $completion->timecompleted
            );
        }
        
        $results = array(
            'course_fullname' => $course->fullname,
            'course_shortname' => $course->shortname,
            'completions' => $results,
            'warnings' => $warnings
        );
                
        return $results;
    }
    
    /**
     * Get Completions Returns
     * 
     * Returns description of get_completions() returns
     * 
     * @return external_single_structure
     */
    public static function get_completions_returns() {
        return new external_single_structure(
            array(
                'course_fullname' => new external_value(PARAM_RAW, 'course_fullname'),
                'course_shortname' => new external_value(PARAM_RAW, 'course_shortname'),
                'completions' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'userid'          => new external_value(PARAM_RAW, 'userid'),
                            'username'        => new external_value(PARAM_RAW, 'username'),
                            'email'           => new external_value(PARAM_RAW, 'email'),
                            'course'          => new external_value(PARAM_RAW, 'course'),
                            'timeenrolled'    => new external_value(PARAM_RAW, 'timeenrolled'),
                            'timestarted'     => new external_value(PARAM_RAW, 'timestarted'),
                            'timecompleted'   => new external_value(PARAM_RAW, 'timecompleted')                            
                        ), 'Completion'
                        ), 'List of completions'
                    ),
                'warnings' => new external_warnings()
            )
        );
    }
}
