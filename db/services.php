<?php

/**
 * Bulk Completions - Web Services
 *
 * @package   local_bulkcompletions
 * @author    Brendan Harris
 * @copyright 2018 Regional Paramedic Program for Eastern Ontario
 * @copyright Based on a web services template by Jerome Mouneyrac, (c) 2011
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// We defined the web service functions to install.
$functions = array(
    'local_bulkcompletions_get_completions' => array(
        'classname'   => 'local_bulkcompletions_external',
        'methodname'  => 'get_completions',
        'classpath'   => 'local/bulkcompletions/externallib.php',
        'description' => 'Return completions data per course',
        'capabilities' => 'report/completion:view, moodle/course:viewparticipants',
        'type'        => 'read',
    )
);

// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = array(
    // Don't bother: the admin can just self-configure a service
    // It is likely the get_completions function will need to be bundled with built-in Moodle functions anyway
    
    //         'My service' => array(
    //                 'functions' => //array ('local_bulkcompletions_hello_world'),
    //                 'restrictedusers' => 0,
    //                 'enabled'=>1,
);
