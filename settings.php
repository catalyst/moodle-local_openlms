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
 * Open LMS admin pages.
 *
 * @package   local_openlms
 * @copyright 2022 Open LMS (https://www.openlms.net/)
 * @author    Petr Skoda
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/** @var admin_root $ADMIN */

// Work around problematic hard-coded config capability requirement in enrol plugin settings.
if (get_config('enrol_programs', 'version')) {
    $syscontext = context_system::instance();
    if (!has_capability('moodle/site:config', $syscontext)) {
        if (!$ADMIN->locate('programs')) {
            if (has_capability('moodle/site:configview', $syscontext) && get_config('enrol_programs', 'version')) {
                if (file_exists(__DIR__ . '/../../enrol/programs/settings.php')) {
                    require __DIR__ . '/../../enrol/programs/settings.php';
                }
            }
        }

        if (!$ADMIN->locate('customfieldsettings')) {
            if (has_capability('moodle/site:configview', $syscontext) && get_config('customfield_training', 'version')) {
                if (file_exists(__DIR__ . '/../../enrol/programs/settings.php')) {
                    $ADMIN->add('modules', new admin_category('customfieldsettings', new lang_string('customfields', 'core_customfield')));
                    require __DIR__ . '/../../customfield/field/training/settings.php';
                }
            }
        }
    }
}
