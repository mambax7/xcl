<?php
// $Id: formcheckboxgroup.php,v 1.1 2007/05/15 02:34:42 minahito Exp $

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

/**
 * @package     kernel
 * @subpackage  form
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */

/**
 * Parent
 */
include_once XOOPS_ROOT_PATH . '/class/xoopsform/formcheckbox.php';

/**
 * A checkbox field with a choice of available groups
 *
 * @package     kernel
 * @subpackage  form
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class AltsysFormCheckboxGroup extends XoopsFormCheckbox
{
    /**
     * Constructor
     *
     * @param	string	$caption
     * @param	string	$name
     * @param	bool	$include_anon	Include group "anonymous"?
     * @param	mixed	$value	    	Pre-selected value (or array of them).
     */
    public function __construct($caption, $name, $include_anon=false, $value=null)
    {
        $this->XoopsFormCheckbox($caption, $name, $value);
        $member_handler = xoops_gethandler('member');
        if (!$include_anon) {
            $options = $member_handler->getGroupList(new Criteria('groupid', XOOPS_GROUP_ANONYMOUS, '!='));
        } else {
            $options = $member_handler->getGroupList();
        }
        foreach ($options as $k => $v) {
            $options[$k] = $v . '<br>';
        }
        $this->addOptionArray($options);
    }
}
