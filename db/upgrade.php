<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

// This file keeps track of upgrades to
// the apply module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installation to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the methods of database_manager class
//
// Please do not forget to use upgrade_set_timeout()
// before any action that may take longer time to finish.

function xmldb_apply_upgrade($oldversion)
{
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    // 2013042002
    if ($oldversion < 2013042002) {
        $table = new xmldb_table('apply');
        //
        $field = new xmldb_field('enable_deletemode', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'name_pattern');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
    }

    // 2014112300 
    if ($oldversion < 2014112300) {
        $table = new xmldb_table('apply');
        //
        $field = new xmldb_field('email_notification_user', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'email_notification');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
    }

    // 2018091701
    if ($oldversion < 2018091701) {
        $table = new xmldb_table('apply');
        //
        $field = new xmldb_field('only_acked_accept', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'name_pattern');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
    }

    // 2018091901
    if ($oldversion < 2018091901) {
        $table = new xmldb_table('apply');
        //
        $field = new xmldb_field('can_discard', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1', 'enable_deletemode');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
    }

    // 2018091903
    if ($oldversion < 2018091903) {
        $table = new xmldb_table('apply');
        //
        $field = new xmldb_field('date_format', XMLDB_TYPE_CHAR, '32', null, XMLDB_NOTNULL, null, '%m/%d/%y %H:%M', 'can_discard');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
    }

    // 2025020400
    if ($oldversion < 2025020400) {
        $table = new xmldb_table('apply');
        //
        $field = new xmldb_field('anyone_submit', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'introformat');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
    }

    return true;
}

