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
 * Form for editing boasearch block instances.
 *
 * @package   block_boasearch
 * @copyright 2020 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_boasearch extends block_base {

    static $genericloaded = false;

    function init() {
        $this->title = get_string('pluginname', 'block_boasearch');
    }

    function has_config() {
        return true;
    }

    function applicable_formats() {
        return array('all' => true);
    }

    function specialization() {
        if (isset($this->config->title)) {
            $this->title = $this->title = format_string($this->config->title, true, ['context' => $this->context]);
        } else {
            $this->title = get_string('newblocktitle', 'block_boasearch');
        }
    }

    function instance_allow_multiple() {
        return false;
    }

    function get_content() {
        global $CFG, $OUTPUT;

        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content         =  new stdClass;
        $this->content->text   = '';
        $this->content->footer = '';

        $config = $this->config;

        if (empty($config->boauri)) {
            return $this->content;
        }

        // Load one time if multiple instances be added in the page.
        if (!self::$genericloaded) {
            // Load templates and other general information.
            $renderable = new \block_boasearch\output\main();
            $renderable->config = $config;
            $renderer = $this->page->get_renderer('block_boasearch');

            $this->content->text = $renderer->render($renderable);

            self::$genericloaded = true;
        } else {
            $this->content->text = self::$genericloaded;
        }


        return $this->content;
    }

    public function instance_can_be_docked() {
        return false;
    }

    private function choosepreview($item) {
        if (property_exists($item->manifest, 'alternate') && property_exists($item->manifest, 'entrypoint')) {
            $alterpath = $item->about . '/!/.alternate/' . $item->manifest->entrypoint;

            if (in_array('preview.png', $item->manifest->alternate)) {
                return $alterpath . '/preview.png';
            } else if (in_array('thumb.png', $item->manifest->alternate)) {
                return $alterpath . '/thumb.png';
            }
        }

        return $item->manifest->customicon;
    }

}
