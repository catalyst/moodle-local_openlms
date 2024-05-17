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

/**
 * Import notification.
 *
 * @package    local_openlms
 * @copyright  2024 Open LMS (https://www.openlms.net/)
 * @author     Farhan Karmali
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/** @var moodle_database $DB */
/** @var moodle_page $PAGE */
/** @var core_renderer $OUTPUT */
/** @var stdClass $CFG */
/** @var stdClass $COURSE */

use local_openlms\notification\util;

if (!empty($_SERVER['HTTP_X_LEGACY_DIALOG_FORM_REQUEST'])) {
    define('AJAX_SCRIPT', true);
}

require('../../../config.php');

$component = required_param('component', PARAM_COMPONENT);
$instanceid = required_param('instanceid', PARAM_INT);
$frominstance = optional_param('frominstance', 0, PARAM_INT);

require_login();

/** @var class-string<\local_openlms\notification\manager> $manager */
$manager = \local_openlms\notification\util::get_manager_classname($component);
if (!$manager) {
    throw new invalid_parameter_exception('Invalid notification component');
}

$returnurl = $manager::get_instance_management_url($instanceid);
if (!$manager::can_manage($instanceid)) {
    redirect($returnurl);
}
$context = $manager::get_instance_context($instanceid);

$PAGE->set_context($context);
$PAGE->set_url('/local/openlms/notification/add.php', ['component' => 'component', 'instanceid' => $instanceid]);
$PAGE->set_pagelayout('admin');
$PAGE->set_heading(get_string('notification_create', 'local_openlms'));
$PAGE->set_title(get_string('notification_create', 'local_openlms'));

$form = null;
if (!$frominstance) {
    $form = new \local_openlms\form\notification_import(null,
            ['instanceid' => $instanceid, 'component' => $component, 'manager' => $manager]);
    if ($form->is_cancelled()) {
        redirect($returnurl);
    } else if ($data = $form->get_data()) {
        $frominstance = $data->frominstance;
        unset($data);
        $form = null;
    }
}

if (!$form) {
    $form = new \local_openlms\form\notification_import_confirmation(null,
        ['instanceid' => $instanceid, 'component' => $component, 'manager' => $manager,
            'frominstance' => $frominstance]);

    if ($form->is_cancelled()) {
        redirect($returnurl);
    }

    if ($data = $form->get_data()) {
        $notificationids = [];
        foreach ($data as $key => $value) {
            if (str_contains($key, 'importnotification') && $value == 1) {
                $notificationids[] = explode('_', $key)[1];
            }
        }

        util::import_notifications($data, $notificationids);

        $form->redirect_submitted($returnurl);

    }
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('notification_create', 'local_openlms'));
echo $form->render();
echo $OUTPUT->footer();
