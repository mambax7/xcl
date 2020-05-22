<?php

altsys_set_module_config();

function altsys_set_module_config()
{
    global $altsysModuleConfig, $altsysModuleId;

    $module_handler = xoops_gethandler('module');
    $module         = &$module_handler->getByDirname('altsys');
    if (is_object($module)) {
        $config_handler     = xoops_gethandler('config');
        $altsysModuleConfig = $config_handler->getConfigList($module->getVar('mid'));
        $altsysModuleId = $module->getVar('mid');
    } else {
        $altsysModuleConfig = [];
        $altsysModuleId = 0;
    }

    // for RTL users
    @define('_GLOBAL_LEFT', 1 == @_ADM_USE_RTL ? 'right' : 'left');
    @define('_GLOBAL_RIGHT', 1 == @_ADM_USE_RTL ? 'left' : 'right');
}


function altsys_include_mymenu()
{
    global $xoopsModule, $xoopsConfig, $mydirname, $mydirpath, $mytrustdirname, $mytrustdirpath, $mymenu_fake_uri;

    $mymenu_find_paths = [
        $mydirpath . '/admin/mymenu.php',
        $mydirpath . '/mymenu.php',
        $mytrustdirpath . '/admin/mymenu.php',
        $mytrustdirpath . '/mymenu.php',
    ];

    foreach ($mymenu_find_paths as $mymenu_find_path) {
        if (file_exists($mymenu_find_path)) {
            include $mymenu_find_path;
            break;
        }
    }
}


function altsys_include_language_file($type)
{
    $mylang = $GLOBALS['xoopsConfig']['language'];

    if (file_exists(XOOPS_ROOT_PATH . '/modules/altsys/language/' . $mylang . '/' . $type . '.php')) {
        include_once XOOPS_ROOT_PATH . '/modules/altsys/language/' . $mylang . '/' . $type . '.php';
    } elseif (file_exists(XOOPS_TRUST_PATH . '/libs/altsys/language/' . $mylang . '/' . $type . '.php')) {
        include_once XOOPS_TRUST_PATH . '/libs/altsys/language/' . $mylang . '/' . $type . '.php';
    } elseif (file_exists(XOOPS_ROOT_PATH . '/modules/altsys/language/english/' . $type . '.php')) {
        include_once XOOPS_ROOT_PATH . '/modules/altsys/language/english/' . $type . '.php';
    } elseif (file_exists(XOOPS_TRUST_PATH . '/libs/altsys/language/english/' . $type . '.php')) {
        include_once XOOPS_TRUST_PATH . '/libs/altsys/language/english/' . $type . '.php';
    }
}

define('ALTSYS_CORE_TYPE_XCL21', 16); // XOOPS Cube 2.1 Legacy


function altsys_get_core_type()
{
    static $result = null;

    if (empty($result)) {
        if (defined('XOOPS_CUBE_LEGACY')) {
            $result = ALTSYS_CORE_TYPE_XCL21;
        }

    }

    return $result;
}


function altsys_get_link2modpreferences( $mid )
{
    return XOOPS_URL . '/modules/legacy/admin/index.php?action=PreferenceEdit&confmod_id=' . $mid;
}


function altsys_template_touch($tpl_id)
{
    xoops_template_touch($tpl_id);
}


function altsys_clear_templates_c()
{
    $dh = opendir(XOOPS_COMPILE_PATH);
    while ($file = readdir($dh)) {
        if ('.' == substr($file, 0, 1)) {
            continue;
        }
        if ('.php' != substr($file, -4)) {
            continue;
        }
        @unlink(XOOPS_COMPILE_PATH . '/' . $file);
    }
    closedir($dh);
}
