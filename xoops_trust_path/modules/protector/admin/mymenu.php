<?php

global $xoopsModule ;
if (! is_object($xoopsModule)) {
    die('$xoopsModule is not set')  ;
}

// language files (modinfo.php)
$language = empty($xoopsConfig['language']) ? 'english' : $xoopsConfig['language'] ;
if (file_exists("$mydirpath/language/$language/modinfo.php")) {
    // user customized language file
    include_once "$mydirpath/language/$language/modinfo.php" ;
} elseif (file_exists("$mytrustdirpath/language/$language/modinfo.php")) {
    // default language file
    include_once "$mytrustdirpath/language/$language/modinfo.php" ;
} else {
    // fallback english
    include_once "$mytrustdirpath/language/english/modinfo.php" ;
}

include dirname(__DIR__) . '/admin_menu.php' ;
// mytplsadmin (TODO check if this module has tplfile)
// if (file_exists(XOOPS_TRUST_PATH.'/libs/altsys/mytplsadmin.php')) {
//     $title = defined('_MD_A_MYMENU_MYTPLSADMIN') ? _MD_A_MYMENU_MYTPLSADMIN : 'tplsadmin' ;
//     array_push($adminmenu, array( 'title' => $title, 'link' => 'admin/index.php?mode=admin&lib=altsys&page=mytplsadmin' )) ;
// }

if (file_exists(XOOPS_TRUST_PATH.'/libs/altsys/myblocksadmin.php')) {
    // myblocksadmin
    $title = defined('_MD_A_MYMENU_MYBLOCKSADMIN') ? _MD_A_MYMENU_MYBLOCKSADMIN : 'blocksadmin' ;
    array_push($adminmenu, ['title' => $title, 'link' => 'admin/index.php?mode=admin&lib=altsys&page=myblocksadmin']) ;
}

// preferences
$config_handler =& xoops_getHandler('config');
if (count($config_handler->getConfigs(new Criteria('conf_modid', $xoopsModule->mid()))) > 0) {
    if (file_exists(XOOPS_TRUST_PATH.'/libs/altsys/mypreferences.php')) {
        // mypreferences
        $title = defined('_MD_A_MYMENU_MYPREFERENCES') ? _MD_A_MYMENU_MYPREFERENCES : _PREFERENCES ;
        array_push($adminmenu, ['title' => $title, 'link' => 'admin/index.php?mode=admin&lib=altsys&page=mypreferences']) ;
    } else {
        if (false !== $module_handler->getByDirName('legacy')) {
            // legacy->preferences
            array_push($adminmenu, ['title' => _PREFERENCES, 'link' => XOOPS_URL . '/modules/legacy/admin/index.php?action=PreferenceEdit&confmod_id=' . $xoopsModule->mid()]) ;
        } else {
            // system->preferences
            array_push($adminmenu, ['title' => _PREFERENCES, 'link' => XOOPS_URL . '/modules/system/admin.php?fct=preferences&op=showmod&mod=' . $xoopsModule->mid()]) ;
        }
    }
}

$mymenu_uri = empty($mymenu_fake_uri) ? $_SERVER['REQUEST_URI'] : $mymenu_fake_uri ;
$mymenu_link = substr(strstr($mymenu_uri, '/admin/'), 1) ;


// link conversion from relative to absolute
foreach (array_keys($adminmenu) as $i) {
    if (false === stristr($adminmenu[$i]['link'], XOOPS_URL)) {
        $adminmenu[$i]['link'] = XOOPS_URL."/modules/$mydirname/" . $adminmenu[$i]['link'] ;
    }
}

// display (you can customize htmls)
echo "<div class='admin-menu'>" ;
foreach ($adminmenu as $menuitem) {
    echo "<div><a href='".htmlspecialchars($menuitem['link'], ENT_QUOTES)."'>".htmlspecialchars($menuitem['title'], ENT_QUOTES)."</a></div>\n" ;
}
echo "</div>\n" ;
