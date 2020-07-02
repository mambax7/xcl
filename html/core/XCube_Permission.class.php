<?php
/**
 *
 * @package XCube
 * @version $Id: XCube_Permission.class.php,v 1.3 2008/10/12 04:30:27 minahito Exp $
 * @copyright Copyright 2005-2007 XOOPS Cube Project  <https://github.com/xoopscube/legacy>
 * @license https://github.com/xoopscube/legacy/blob/master/docs/bsd_licenses.txt Modified BSD license
 *
 */

/**
 * XCube_PermissionUtils
 * Note:
 * In some other languages, such as C, a void function can't be used in an expression, only as a statement.
 * IDEs and other tools can warn the user when the return value of a void function is being used.
 * It isn't strictly necessary for the language itself to cover this.
 * https://wiki.php.net/rfc/void_return_type#use_of_void_functions_in_expressions
 */
class XCube_Permissions
{
    public function getRolesOfAction()
    {
        $args = func_get_args();
        $actionName = array_shift($args);

        $root =& XCube_Root::getSingleton();
        return $root->mPermissionManager->getRolesOfAction($actionName, $args);
    }
}

class XCube_AbstractPermissionProvider
{
    public function __construct()
    {
    }

    public function prepare()
    {
    }

    public function getRolesOfAction($actionName, $args)
    {
    }
}
