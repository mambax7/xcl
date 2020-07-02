<?php
// $Id: cp_functions.php,v 1.1 2007/05/15 02:34:18 minahito Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
define('XOOPS_CPFUNC_LOADED', 1);

function xoops_cp_header()
{
    //
    // [Special Mission] Additional CHECK!!
    // Old modules may call this file from other admin directory.
    // In this case, the controller does not have Admin Module Object.
    //
    $root=&XCube_Root::getSingleton();
    require_once XOOPS_ROOT_PATH . '/modules/legacy/kernel/Legacy_AdminControllerStrategy.class.php';

    $strategy =new Legacy_AdminControllerStrategy($root->mController);

    $root->mController->setStrategy($strategy);
    $root->mController->setupModuleContext();
    $root->mController->_mStrategy->setupModuleLanguage();    //< Umm...

    require_once XOOPS_ROOT_PATH . '/header.php';
}

function xoops_cp_footer()
{
    require_once XOOPS_ROOT_PATH . '/footer.php';
}

// We need these because theme files will not be included
function OpenTable()
{
    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #2F5376;'><tr class='bg4'><td valign='top'>\n";
}

function CloseTable()
{
    echo '</td></tr></table>';
}

function themecenterposts($title, $content)
{
    echo '<table cellpadding="4" cellspacing="1" width="98%" class="outer"><tr><td class="head">'.$title.'</td></tr><tr><td><br>'.$content.'<br></td></tr></table>';
}

function myTextForm($url, $value)
{
    return '<form action="'.$url.'" method="post"><input type="submit" value="'.$value.'" /></form>';
}

function xoopsfwrite()
{
    if ('POST' != $_SERVER['REQUEST_METHOD']) {
        return false;
    }
    if (!xoops_refcheck()) {
        return false;
    }
    return true;
}

