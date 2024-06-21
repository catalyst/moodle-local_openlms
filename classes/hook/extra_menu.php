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

namespace local_openlms\hook;

/**
 * Base class for extra menu additions hook
 *
 * @package    local_openlms
 * @copyright  2024 Open LMS (https://www.openlms.net/)
 * @author     Petr Skoda
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class extra_menu implements \core\hook\described_hook {
    /** @var array $items links, dividers or custom html fragments */
    protected $items = [];
    /** @var string $page */
    protected $page;

    /**
     * @param string $page page name, usually matches relative file name without .php suffix.
     */
    public function __construct(string $page) {
        $this->page = $page;
    }

    /**
     * Returns page identification.
     *
     * @return string
     */
    public function get_page(): string {
        return $this->page;
    }

    /**
     * Add standard link item to extra menu.
     *
     * @param string $label
     * @param \moodle_url $url
     */
    public function add_item(string $label, \moodle_url $url): void {
        $this->items[] = ['label' => $label, 'url' => $url];
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
     * Returns collected extra items.
     *
     * @return array
     */
    public function get_items(): array {
        return $this->items;
    }

    /**
     * Hook purpose description in Markdown format
     * used on Hooks overview page.
     *
     * @return string
     */
    public static function get_hook_description(): string {
        return 'Extra menu additions hook.';
    }

    /**
     * List of tags that describe this hook.
     *
     * @return string[]
     */
    public static function get_hook_tags(): array {
        return [];
    }
}
