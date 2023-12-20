﻿<?php

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

/**
 * Defines backup_apply_activity_task class
 *
 * @package     mod_apply
 * @category    backup
 * @copyright   2016 Fumi.Iseki
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/mod/apply/backup/moodle2/backup_apply_stepslib.php');

/**
 * Provides the steps to perform one complete backup of the Label instance
 */
class backup_apply_activity_task extends backup_activity_task
{
    /**
     * No specific settings for this activity
     */
    protected function define_my_settings()
    {
    }

    /**
     * Defines a backup step to store the instance data in the apply.xml file
     */
    protected function define_my_steps()
    {
        $this->add_step(new backup_apply_activity_structure_step('apply_structure', 'apply.xml'));
    }

    /**
     * No content encoding needed for this activity
     *
     * @param string $content some HTML text that eventually contains URLs to the activity instance scripts
     * @return string the same content with no changes
     */
    static public function encode_content_links($content)
    {
        return $content;
    }
}
