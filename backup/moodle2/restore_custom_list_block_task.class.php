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
class restore_custom_list_block_task extends restore_block_task
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

    static public function define_decode_contents()
    {

        $contents = array();

        $contents[] = new restore_custom_list_block_decode_content('block_instances', 'configdata', 'block_instance');

        return $contents;
    }

    static public function define_decode_rules()
    {
        return array();
    }

}

/**
 * Specialised restore_decode_content provider that unserializes the configdata
 * field, to serve the configdata->text content to the restore_decode_processor
 * packaging it back to its serialized form after process
 */
class restore_custom_list_block_decode_content extends restore_decode_content
{

    protected $configdata; // Temp storage for unserialized configdata

    protected function get_iterator()
    {
        global $DB;

        // Build the SQL dynamically here
        $fieldslist = 't.' . implode(', t.', $this->fields);
        $sql = "SELECT t.id, $fieldslist
                  FROM {" . $this->tablename . "} t
                  JOIN {backup_ids_temp} b ON b.newitemid = t.id
                 WHERE b.backupid = ?
                   AND b.itemname = ?
                   AND t.blockname = 'custom_list'";
        $params = array($this->restoreid, $this->mapping);
        return ($DB->get_recordset_sql($sql, $params));
    }

    protected function preprocess_field($field)
    {
        $this->configdata = unserialize(base64_decode($field));
        return isset($this->configdata->text) ? $this->configdata->text : '';
    }

    protected function postprocess_field($field)
    {
        $this->configdata->text = $field;
        return base64_encode(serialize($this->configdata));
    }

}
