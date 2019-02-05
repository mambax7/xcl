<?php

if (!defined('XOOPS_ROOT_PATH')) exit();

require_once XOOPS_ROOT_PATH."/core/XCube_ActionForm.class.php";

/**
 * This class is generated by makeActionForm tool.
 * @auchor makeActionForm
 */
class Legacy_ImagecategoryListForm extends XCube_ActionForm 
{
	/**
	 * If the request is GET, never return token name.
	 * By this logic, a action can have three page in one action.
	 */
	function getTokenName()
	{
		//
		//
		if (xoops_getenv('REQUEST_METHOD') == 'POST') {
			return "module.legacy.ImagecategorySettingsForm.TOKEN";
		}
		else {
			return null;
		}
	}
	
	/**
	 * For displaying the confirm-page, don't show CSRF error.
	 * Always return null.
	 */
	function getTokenErrorMessage()
	{
		return null;
	}
	
	function prepare()
	{
		// set properties
		$this->mFormProperties['name'] =new XCube_StringArrayProperty('name');
		$this->mFormProperties['maxsize'] =new XCube_IntArrayProperty('maxsize');
		$this->mFormProperties['maxwidth'] =new XCube_IntArrayProperty('maxwidth');
		$this->mFormProperties['maxheight'] =new XCube_IntArrayProperty('maxheight');
		$this->mFormProperties['display'] =new XCube_BoolArrayProperty('display');
		$this->mFormProperties['weight'] =new XCube_IntArrayProperty('weight');
		$this->mFormProperties['delete']=new XCube_BoolArrayProperty('delete');
		//to display error-msg at confirm-page
		$this->mFormProperties['confirm'] =new XCube_BoolProperty('confirm');
		// set fields
		$this->mFieldProperties['name'] =new XCube_FieldProperty($this);
		$this->mFieldProperties['name']->setDependsByArray(array('required','maxlength'));
		$this->mFieldProperties['name']->addMessage('required', _MD_LEGACY_ERROR_REQUIRED, _AD_LEGACY_LANG_IMGCAT_NAME, '100');
		$this->mFieldProperties['name']->addMessage('maxlength', _MD_LEGACY_ERROR_MAXLENGTH, _AD_LEGACY_LANG_IMGCAT_NAME, '100');
		$this->mFieldProperties['name']->addVar('maxlength', '100');
	
		$this->mFieldProperties['maxsize'] =new XCube_FieldProperty($this);
		$this->mFieldProperties['maxsize']->setDependsByArray(array('required'));
		$this->mFieldProperties['maxsize']->addMessage('required', _MD_LEGACY_ERROR_REQUIRED, _AD_LEGACY_LANG_IMGCAT_MAXSIZE);
	
		$this->mFieldProperties['maxwidth'] =new XCube_FieldProperty($this);
		$this->mFieldProperties['maxwidth']->setDependsByArray(array('required'));
		$this->mFieldProperties['maxwidth']->addMessage('required', _MD_LEGACY_ERROR_REQUIRED, _AD_LEGACY_LANG_IMGCAT_MAXWIDTH);
	
		$this->mFieldProperties['maxheight'] =new XCube_FieldProperty($this);
		$this->mFieldProperties['maxheight']->setDependsByArray(array('required'));
		$this->mFieldProperties['maxheight']->addMessage('required', _MD_LEGACY_ERROR_REQUIRED, _AD_LEGACY_LANG_IMGCAT_MAXHEIGHT);
	
		$this->mFieldProperties['weight'] =new XCube_FieldProperty($this);
		$this->mFieldProperties['weight']->setDependsByArray(array('required'));
		$this->mFieldProperties['weight']->addMessage('required', _MD_LEGACY_ERROR_REQUIRED, _AD_LEGACY_LANG_IMGCAT_WEIGHT);

	}
}


