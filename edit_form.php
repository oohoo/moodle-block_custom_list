<?php

/**
 * ************************************************************************
 * *                  Custom list                                        **
 * ************************************************************************
 * @package     block                                                    **
 * @subpackage  custom_list                                              **
 * @name        custom_list                                              **
 * @copyright   oohoo.biz                                                **
 * @link        http://oohoo.biz                                         **
 * @author      Nicolas Bretin                                           **
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later **
 * ************************************************************************
 * ************************************************************************ */

/**
 * Form for editing custom_list block instances.
 */
class block_custom_list_edit_form extends block_edit_form
{

    protected function specific_definition($mform)
    {
        global $CFG;

        // Fields for editing custom_list block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('configtitle', 'block_html'));
        $mform->setType('config_title', PARAM_TEXT);

        $mform->addElement('textarea', 'config_text', get_string('configcontent', 'block_custom_list'), array('style' => 'width: 500px; height:150px;'));
        $mform->addElement('static', 'comment', '', get_string('configcomment', 'block_custom_list'));
        $mform->addRule('config_text', null, 'required', null, 'client');
        $mform->setType('config_text', PARAM_RAW); // XSS is prevented when printing the block contents and serving files
    }

    function set_data($defaults)
    {
        if (!empty($this->block->config) && is_object($this->block->config))
        {
            $text = $this->block->config->text;
            if (empty($text))
            {
                $currenttext = '';
            }
            else
            {
                $currenttext = $text;
            }
            $defaults->config_text = $currenttext;
        }
        else
        {
            $text = '';
        }

        if (!$this->block->user_can_edit() && !empty($this->block->config->title))
        {
            // If a title has been set but the user cannot edit it format it nicely
            $title = $this->block->config->title;
            $defaults->config_title = format_string($title, true, $this->page->context);
            // Remove the title from the config so that parent::set_data doesn't set it.
            unset($this->block->config->title);
        }

        // have to delete text here, otherwise parent::set_data will empty content
        // of editor
        unset($this->block->config->text);
        parent::set_data($defaults);
        // restore $text
        if (!isset($this->block->config))
        {
            $this->block->config = new stdClass();
        }
        $this->block->config->text = $text;
        if (isset($title))
        {
            // Reset the preserved title
            $this->block->config->title = $title;
        }
    }

}
