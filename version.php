<?php
/**
 * Bulk Completions Plugin - Version File
 * @package   local_bulkcompletions
 * @author    Brendan Harris
 * @copyright 2018 Regional Paramedic Program for Eastern Ontario
 * @copyright Based on a web services template by Jerome Mouneyrac, (c) 2011
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$plugin->version  = 2018091200;   // The (date) version of this module + 2 extra digital for daily versions
                                  // This version number is displayed into /admin/forms.php
                                  // TODO: if ever this plugin get branched, the old branch number
                                  // will not be updated to the current date but just incremented. We will
                                  // need then a $plugin->release human friendly date. For the moment, we use
                                  // display this version number with userdate (dev friendly)
$plugin->requires = 2010112400;  // Requires this Moodle version - at least 2.0
$plugin->component = 'local_bulkcompletions';
$plugin->cron     = 0;
$plugin->release = '1.0 (Build: 2018091200)';
$plugin->maturity = MATURITY_STABLE;
