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

use local_openlms\notification\manager;

/**
 * Notification import confirmation form.
 *
 * @package    local_openlms
 * @copyright  2024 Open LMS
 * @author     Farhan Karmali
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class notification_import_confirmation extends \local_openlms\dialog_form {
    protected function definition() {
        global $DB;

        $mform = $this->_form;
        $component = $this->_customdata['component'];
        $instanceid = $this->_customdata['instanceid'];
        $frominstance = $this->_customdata['frominstance'];
        /** @var class-string<\local_openlms\notification\manager> $manager */
        $manager = $this->_customdata['manager'];

        $mform->addElement('hidden', 'instanceid');
        $mform->setType('instanceid', PARAM_INT);
        $mform->setConstant('instanceid', $instanceid);

        $mform->addElement('hidden', 'component');
        $mform->setType('component', PARAM_COMPONENT);
        $mform->setConstant('component', $component);

        $mform->addElement('hidden', 'frominstance');
        $mform->setType('frominstance', PARAM_INT);
        $mform->setConstant('frominstance', $frominstance);

        $fromname = $manager::get_instance_name($instanceid);
        $mform->addElement('static', 'staticinstance', get_string('notification_import_from', 'local_openlms'), $fromname);

        $types = $manager::get_all_types();

        $notifications = $DB->get_records('local_openlms_notifications',
            ['instanceid' => $frominstance, 'component' => $component]);
        foreach ($notifications as $notification) {
            $classname = $types[$notification->notificationtype] ?? null;
            $mform->addElement('advcheckbox', 'importnotification_'.$notification->id, $classname::get_name(), null,
                ['group' => 1]);
        }
        $this->add_checkbox_controller(1);

        $mform->addElement('html', get_string('notification_import_warning', 'local_openlms'));
        $this->add_action_buttons(true, get_string('notification_import_confirmation', 'local_openlms'));
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        $manager = $this->_customdata['manager'];
        if (!$manager::validate_frominstance($data['frominstance'])) {
            $errors['frominstance'] = get_string('error');
        }
        return $errors;
    }


}
