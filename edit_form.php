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

/**
 * prints the forms to choose an item-typ to create items and to choose a template to use
 *
 * @author Andreas Grabs
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package apply
 */

//It must be included from a Moodle page
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');
}

require_once($CFG->libdir.'/formslib.php');
require_once('jbxl/jbxl_moodle_tools.php');

class apply_edit_add_question_form extends moodleform 
{
    public function definition() {
        $mform = $this->_form;

        //headline
        $mform->addElement('header', 'general', get_string('add_items', 'apply'));
        // visible elements
        $apply_names_options = apply_load_apply_items_options();

        $attributes = 'onChange="M.core_formchangechecker.set_form_submitted(); this.form.submit()"';
        $mform->addElement('select', 'typ', '', $apply_names_options, $attributes);

        // hidden elements
        $mform->addElement('hidden', 'cmid');
        $mform->setType('cmid', PARAM_INT);
        $mform->addElement('hidden', 'position');
        $mform->setType('position', PARAM_INT);

        // buttons の表示 
        if (jbxl_get_moodle_version()>=4.5) {
            $mform->addElement('submit', 'add_item', get_string('add_item', 'apply'));
        }
    }
}


class apply_edit_use_template_form extends moodleform
{
    private $applydata;

    public function definition()
    {
        $this->applydata = new stdClass();
        //this function can not be called, because not all data are available at this time
        //I use set_form_elements instead
    }

    //this function set the data used in set_form_elements()
    //in this form the only value have to set is course
    //eg: array('course' => $course)
    public function set_applydata($data) {
        if (is_array($data)) {
            if (!isset($this->applydata)) {
                $this->applydata = new stdClass();
            }
            foreach ($data as $key => $val) {
                $this->applydata->{$key} = $val;
            }
        }
    }

    //here the elements will be set
    //this function have to be called manually
    //the advantage is that the data are already set
    public function set_form_elements()
    {
        $mform =& $this->_form;

        $elementgroup = array();
        //headline
        $mform->addElement('header', '', get_string('using_templates', 'apply'));
        // hidden elements
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        // visible elements
        $templates_options = array();
        $owntemplates = apply_get_template_list($this->applydata->course, 'own');
        $publictemplates = apply_get_template_list($this->applydata->course, 'public');

        $options = array();
        if ($owntemplates or $publictemplates) {
            $options[''] = array('' => get_string('choose'));

            if ($owntemplates) {
                $courseoptions = array();
                foreach ($owntemplates as $template) {
                    $courseoptions[$template->id] = $template->name;
                }
                $options[get_string('course')] = $courseoptions;
            }

            if ($publictemplates) {
                $publicoptions = array();
                foreach ($publictemplates as $template) {
                    $publicoptions[$template->id] = $template->name;
                }
                $options[get_string('public', 'apply')] = $publicoptions;
            }

            $attributes = 'onChange="M.core_formchangechecker.set_form_submitted(); this.form.submit()"';
            $elementgroup[] = $mform->createElement('selectgroups', 'templateid', '', $options, $attributes);

            $elementgroup[] = $mform->createElement('submit', 'use_template', get_string('use_this_template', 'apply')); 
            $mform->addGroup($elementgroup, 'elementgroup', '', array(' '), false);
        }
        else {
            $mform->addElement('static', 'info', get_string('no_templates_available_yet', 'apply'));
        }
    }
}



class apply_edit_create_template_form extends moodleform
{
    private $applydata;

    public function definition()
    {
    }

    public function data_preprocessing(&$default_values)
    {
        $default_values['templatename'] = '';
    }

    public function set_applydata($data)
    {
        if (is_array($data)) {
            if (!isset($this->applydata)) {
                $this->applydata = new stdClass();
            }
            foreach ($data as $key => $val) {
                $this->applydata->{$key} = $val;
            }
        }
    }

    public function set_form_elements()
    { 
        $mform =& $this->_form;

        // hidden elements
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->addElement('hidden', 'do_show');
        $mform->setType('do_show', PARAM_INT);
        $mform->addElement('hidden', 'savetemplate', 1);
        $mform->setType('savetemplate', PARAM_INT);

        //headline
        $mform->addElement('header', '', get_string('creating_templates', 'apply'));

        // visible elements
        $elementgroup = array();

        $elementgroup[] = $mform->createElement('static', 'templatenamelabel', get_string('name', 'apply'));
        $elementgroup[] = $mform->createElement('text', 'templatename', get_string('name', 'apply'), array('size'=>'40', 'maxlength'=>'200'));

        if (has_capability('mod/apply:createpublictemplate', context_system::instance())) {
            $elementgroup[] = $mform->createElement('checkbox', 'ispublic', get_string('public', 'apply'), get_string('public', 'apply'));
        }

        // buttons
        $elementgroup[] = $mform->createElement('submit', 'create_template', get_string('save_as_new_template', 'apply'));
        $mform->addGroup($elementgroup, 'elementgroup', get_string('name', 'apply'), array(' '), false);
        $mform->setType('templatename', PARAM_TEXT);

    }
}

