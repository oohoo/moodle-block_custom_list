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
class block_custom_list extends block_base
{

    function init()
    {
        $this->title = get_string('pluginname', 'block_custom_list');
    }

    function has_config()
    {
        return true;
    }

    function applicable_formats()
    {
        return array('all' => true);
    }

    function specialization()
    {
        $this->title = isset($this->config->title) ? format_string($this->config->title) : format_string(get_string('newhtmlblock', 'block_custom_list'));
    }

    function instance_allow_multiple()
    {
        return true;
    }

    function get_content()
    {
        global $CFG;

        if ($this->content !== NULL)
        {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->footer = '<div class="customfooter"></div>';
        if (isset($this->config->text))
        {
            $this->content->text = '';
            $elems = explode("\n", trim($this->config->text));
            $this->content->text .= '<ul class="unlist">';
            $rn = false;
            foreach ($elems as $elem)
            {
                $row = explode('|', $elem);
                if (count($row) > 1)
                {
                    $this->content->text .= '<li class="r'.(int)$rn.'">';
                    $this->content->text .= '<div class="column c'.(int)$rn.'">';
                    $this->content->text .= '<a href="' . trim($row[1]) . '">' . trim($row[0]) . '</a>';
                    $this->content->text .= '</div>';
                    $this->content->text .= '</li>';
                    $rn = !$rn;
                }
            }
            $this->content->text .= '</ul>';
        }
        else
        {
            $this->content->text = '';
        }

        return $this->content;
    }

    /**
     * Serialize and store config data
     */
    function instance_config_save($data, $nolongerused = false)
    {
        $config = clone($data);
        // Move embedded files into a proper filearea and adjust HTML links to match
        $config->text = $data->text;

        parent::instance_config_save($config, $nolongerused);
    }

    /**
     * Do any additional initialization you may need at the time a new block instance is created
     * @global moodle_database $DB
     * @return boolean
     */
    function instance_create()
    {
        global $DB;
        $this->instance->defaultweight = -10;
        $DB->update_record('block_instances', $this->instance);
        return true;
    }

    function instance_delete()
    {
        return true;
    }

    /**
     * The block should only be dockable when the title of the block is not empty
     * and when parent allows docking.
     *
     * @return bool
     */
    public function instance_can_be_docked()
    {
        return (!empty($this->config->title) && parent::instance_can_be_docked());
    }

    /**
     * Returns the role that best describes the course list block.
     *
     * @return string
     */
    public function get_aria_role()
    {
        return 'navigation';
    }

}
