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

namespace local_openlms\output\extra_menu;

/**
 * Extra menu dropdown.
 *
 * @package    local_openlms
 * @copyright  2024 Open LMS (https://www.openlms.net/)
 * @author     Petr Skoda
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class dropdown implements \renderable, \core\output\named_templatable {
    /** @var array $items links, dividers or custom html fragments */
    protected $items = [];
    /** @var string */
    protected $title;

    /**
     * Constructor.
     *
     * @param string $title
     */
    public function __construct(string $title) {
        $this->title = $title;
    }

    /**
     * Add standard link item to dropdown.
     *
     * @param string $label
     * @param \moodle_url $url
     */
    public function add_item(string $label, \moodle_url $url): void {
        $this->items[] = ['label' => $label, 'url' => $url->out(false)];
    }

    /**
     * Add divider element.
     */
    public function add_divider(): void {
        $this->items[] = ['divider' => true];
    }

    /**
     * Add link that opens dialog_form.
     *
     * @param \local_openlms\output\dialog_form\link $link
     */
    public function add_dialog_form(\local_openlms\output\dialog_form\link $link): void {
        global $PAGE;
        $output = $PAGE->get_renderer('local_openlms', 'dialog_form');
        $oldclass = $link->get_class();
        $link->set_class('dropdown-item');
        $this->items[] = ['customhtml' => $output->render($link)];
        $link->set_class($oldclass);
    }

    /**
     * Are there any items?
     *
     * @return bool
     */
    public function has_items(): bool {
        return !empty($this->items);
    }

    /**
     * Export data for template.
     *
     * @param \renderer_base $output
     * @return array
     */
    public function export_for_template(\renderer_base $output): array {
        return [
            'title' => $this->title,
            'items' => $this->items,
        ];
    }

    /**
     * Get the name of the template to use for this templatable.
     *
     * @param \renderer_base $renderer The renderer requesting the template name
     * @return string
     */
    public function get_template_name(\renderer_base $renderer): string {
        return 'local_openlms/extra_menu/dropdown';
    }
}
