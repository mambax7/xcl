<?php
/**
 *
 * @param $options
 * @return array|null
 * @copyright Copyright (c) 2000 XOOPS.org  <https://www.xoops.org/>
 * @license   https://github.com/xoopscube/legacy/blob/master/docs/GPL_V2.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @package   XOOPS2
 * @version   $Id: legacy_themes.php,v 1.3 2008/09/25 15:12:13 kilica Exp $
 */
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: https://www.myweb.ne.jp/, https://www.xoops.org/, https://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
//  This file has been modified for Legacy from XOOPS2 System module block   //
// ------------------------------------------------------------------------- //

function b_legacy_themes_show($options)
{
    global $xoopsConfig;
    
    if (0 == count($xoopsConfig['theme_set_allowed'])) {
        return null;
    }
    
    $block = [];
    if ('POST' == xoops_getenv('REQUEST_METHOD')) {
        $block['isEnableChanger'] = 0;
        return $block;
    }
    
    $block['isEnableChanger'] = 1;
    
    $theme_options = [];
    $handler =& xoops_getModuleHandler('theme', 'legacy');
    foreach ($xoopsConfig['theme_set_allowed'] as $name) {
        $theme =& $handler->get($name);
        if (null != $theme) {
            $theme_option['name'] = $name;
            $theme_option['screenshot'] = $theme->getShow('screenshot');
            $theme_option['screenshotUrl'] = XOOPS_THEME_URL . '/' . $name . '/' . $theme->getShow('screenshot');
            if ($name == $xoopsConfig['theme_set']) {
                $theme_option['selected'] = 'selected="selected"';
                $block['theme_selected_screenshot'] = $theme->getShow('screenshot');
            } else {
                $theme_option['selected'] = '';
            }
            $theme_options[] = $theme_option;
        }
    }
    
    $block['count'] = count($xoopsConfig['theme_set_allowed']);
    $block['mode'] = $options[0];
    $block['width'] = $options[1];
    $block['theme_options'] = $theme_options;
    return $block;
}

function b_legacy_themes_edit($options)
{
    $chk = '';
    $form = '<div>'._MB_LEGACY_LANG_THSHOW.'&nbsp;&nbsp;';
    if (1 == $options[0]) {
        $chk = ' checked="checked"';
    }
    $form .= '<label><input type="radio" name="options[0]" value="1"'.$chk.' /><span>'._YES.'</span></label>';
    $chk = '';
    if (0 == $options[0]) {
        $chk = ' checked="checked"';
    }
    $form .= '<label><input type="radio" name="options[0]" value="0"'.$chk.' /><span>'._NO.'</span></label></div>';
    $form .= '<div><label><span>'._MB_LEGACY_LANG_THWIDTH.'</span>&nbsp;&nbsp;';
    $form .= '<input type="text" name="options[1]" size="3" value="'.$options[1].'" /></label></div>';
    return $form;
}
