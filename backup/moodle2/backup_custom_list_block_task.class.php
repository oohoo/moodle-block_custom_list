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
class backup_custom_list_block_task extends backup_block_task
{

    protected function define_my_settings()
    {
        
    }

    protected function define_my_steps()
    {
        
    }

    public function get_fileareas()
    {
        return array('content');
    }

    public function get_configdata_encoded_attributes()
    {
        return array('text'); // We need to encode some attrs in configdata
    }

    static public function encode_content_links($content)
    {
        return $content; // No special encoding of links
    }

}

