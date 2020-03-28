<?php
/**
 * @file
 * @package profile
 * @version $Id$
 */

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

require_once XOOPS_ROOT_PATH . '/core/XCube_ActionForm.class.php';
require_once XOOPS_MODULE_PATH . '/legacy/class/Legacy_Validator.class.php';

class Profile_Admin_DefinitionsDeleteForm extends XCube_ActionForm
{
    /**
     * @public
     */
    public function getTokenName()
    {
        return 'module.profile.Admin_DefinitionsDeleteForm.TOKEN';
    }

    /**
     * @public
     */
    public function prepare()
    {
        //
        // Set form properties
        //
        $this->mFormProperties['field_id'] =new XCube_IntProperty('field_id');
    
        //
        // Set field properties
        //
        $this->mFieldProperties['field_id'] =new XCube_FieldProperty($this);
        $this->mFieldProperties['field_id']->setDependsByArray(['required']);
        $this->mFieldProperties['field_id']->addMessage('required', _MD_PROFILE_ERROR_REQUIRED, _MD_PROFILE_LANG_FIELD_ID);
    }

    /**
     * @public
     * @param $obj
     */
    public function load($obj)
    {
        $this->set('field_id', $obj->get('field_id'));
    }

    /**
     * @public
     * @param $obj
     */
    public function update($obj)
    {
        $obj->set('field_id', $this->get('field_id'));
    }
}
