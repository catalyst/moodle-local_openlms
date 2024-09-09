<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

namespace local_openlms\form;

/**
 * Notification import form.
 *
 * @package    local_openlms
 * @copyright  2024 Open LMS
 * @author     Farhan Karmali
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class notification_import extends \local_openlms\dialog_form {
    protected function definition() {
        $mform = $this->_form;
        $component = $this->_customdata['component'];
        $instanceid = $this->_customdata['instanceid'];
        /** @var class-string<\local_openlms\notification\manager> $manager */
        $manager = $this->_customdata['manager'];

        $mform->addElement('hidden', 'instanceid');
        $mform->setType('instanceid', PARAM_INT);
        $mform->setConstant('instanceid', $instanceid);

        $mform->addElement('hidden', 'component');
        $mform->setType('component', PARAM_COMPONENT);
        $mform->setConstant('component', $component);

        $instance = $manager::get_instance_name($instanceid);
        $mform->addElement('static', 'staticinstance', get_string('notification_instance', 'local_openlms'), $instance);

        $manager::add_import_frominstance_element($instanceid, $mform);

        $this->add_action_buttons(true, get_string('continue'));
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        $instanceid = $this->_customdata['instanceid'];
        $manager = $this->_customdata['manager'];

        if (!$manager::validate_import_frominstance($instanceid, $data['frominstance'])) {
            $errors['frominstance'] = get_string('error');
        }
        return $errors;
    }
}
