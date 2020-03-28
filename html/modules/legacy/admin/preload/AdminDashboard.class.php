<?php
/**
 * Preload  : AdminDashboard
 * Version  : 2.3
 * Package  : XCL
 * Module   : Legacy
 * Author   : Nuno Luciano aka Gigamaster
 * Credits  : Original AdminSystemCheckPlus Preload
 *            by wanikoo ( https://www.wanisys.net/ )
*/

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

/**
 * Dashboard Display Options
 * URL : /admin.php
 * Boolean data type - Display(1) or not display(0)
 */ 

    // Welcome message
    if (!defined('XC_ADMINDASHBOARD_WELCOME')) {
        define('XC_ADMINDASHBOARD_WELCOME', 1);
    }
    // Site/System Info
    if (!defined('XC_ADMINDASHBOARD_SYSTEMINFO')) {
        define('XC_ADMINDASHBOARD_SYSTEMINFO', 0);
    }
    // PHP Settings
    if (!defined('XC_ADMINDASHBOARD_PHPSETTING')) {
        define('XC_ADMINDASHBOARD_PHPSETTING', 0);
    }
    // Waiting (pending) contents
    if (!defined('XC_ADMINDASHBOARD_WAITING')) {
        define('XC_ADMINDASHBOARD_WAITING', 0);
    }
    // Commentss
    if (!defined('XC_ADMINDASHBOARD_COMMENTS')) {
        define('XC_ADMINDASHBOARD_COMMENTS', 0);
    }
    // Full PHP Info
    if (!defined('XC_ADMINDASHBOARD_PHPINFO')) {
        define('XC_ADMINDASHBOARD_PHPINFO', 0);
    }


    // Admin Sidemenu blocks only 'admin_theme.html'
    //!Fix Cannot be displayed on 'admin-dashboard.html'

    //you can determine which block should be displayed!
    //display(1) or not display(0): waiting-contents block
    if (!defined('XC_ADMINBLOCK_WAITING')) define('XC_ADMINBLOCK_WAITING', 0);
    //display(1) or not display(0): system info block
    if (!defined('XC_ADMINBLOCK_SYSINFO')) define('XC_ADMINBLOCK_SYSINFO', 0);
    //display(1) or not display(0): online-info block
    if (!defined('XC_ADMINBLOCK_ONLINEINFO')) define('XC_ADMINBLOCK_ONLINEINFO', 1);
    //display(1) or not display(0): admin-theme select block
    if (!defined('XC_ADMINBLOCK_ADMINTHEME')) define('XC_ADMINBLOCK_ADMINTHEME', 0);


class Legacy_AdminDashboard extends XCube_ActionFilter
{

    public function preBlockFilter()
    {
        $root=&XCube_Root::getSingleton();
        $root->mDelegateManager->add('Legacypage.Admin.SystemCheck', 'Legacy_AdminDashboard::AdminDashboard', XCUBE_DELEGATE_PRIORITY_NORMAL + 1);
     
        $this->mController->_mStrategy->mSetupBlock->add([&$this, 'AdminSetupBlock']);
        
		//new language constants
		if (!defined('_MB_LEGACY_XCLEGACYVERSION')) {
		define('_MB_LEGACY_XCLEGACYVERSION', 'XC Legacy Version');
		}
		if (!defined('_MB_LEGACY_XCVERSION')) {
		define('_MB_LEGACY_XCVERSION', 'XOOPS Cube Version');
		}
		if (!defined('_MB_LEGACY_SYSTEMINFO')) {
		define('_MB_LEGACY_SYSTEMINFO', 'Site/System Info');
		}
		if (!defined('_MB_LEGACY_PHPVERSION')) {
		define('_MB_LEGACY_PHPVERSION', 'PHP Version');
		}
		if (!defined('_MB_LEGACY_MYSQLVERSION')) {
		define('_MB_LEGACY_MYSQLVERSION', 'MYSQL Version');
		}
		if (!defined('_MB_LEGACY_OS')) {
		define('_MB_LEGACY_OS', 'Operating System');
		}
		if (!defined('_MB_LEGACY_SERVER')) {
		define('_MB_LEGACY_SERVER', 'Server');
		}
		if (!defined('_MB_LEGACY_USERAGENT')) {
		define('_MB_LEGACY_USERAGENT', 'User Agent');
		}
		if (!defined('_MB_LEGACY_ONLINEINFO')) {
		define('_MB_LEGACY_ONLINEINFO', "Who's online");
		}
		if (!defined('_MB_LEGACY_ADMINTHEMESELECT')) {
		define('_MB_LEGACY_ADMINTHEMESELECT', 'A-Theme Changer');
		}
		//you can add your own here!
       
    }
	//If you want to add any new block, please customize this func!
	//please refer to sample-blocks!
	public static function AdminSetupBlock()
	{
		//online info block
		if(XC_ADMINBLOCK_ONLINEINFO && file_exists(XOOPS_LEGACY_PATH . '/admin/blocks/AdminOnlineInfo.class.php')) {
            require_once XOOPS_LEGACY_PATH . '/admin/blocks/AdminOnlineInfo.class.php';
            $this->mController->_mBlockChain[] = new Legacy_AdminOnlineInfo();
            }
        //system info block
		if(XC_ADMINBLOCK_SYSINFO && file_exists(XOOPS_LEGACY_PATH . '/admin/blocks/AdminSystemInfo.class.php')) {
            require_once XOOPS_LEGACY_PATH . '/admin/blocks/AdminSystemInfo.class.php';
            $this->mController->_mBlockChain[] = new Legacy_AdminSystemInfo();
            }   
        //waiting contents block
		if(XC_ADMINBLOCK_WAITING && file_exists(XOOPS_LEGACY_PATH . '/admin/blocks/AdminWaiting.class.php')) {
            require_once XOOPS_LEGACY_PATH . '/admin/blocks/AdminWaiting.class.php';
            $this->mController->_mBlockChain[] = new Legacy_AdminWaiting();
            }
		//admin-theme changer block
		if(XC_ADMINBLOCK_ADMINTHEME && file_exists(XOOPS_LEGACY_PATH . '/admin/blocks/AdminThemeSelect.class.php') && file_exists(XOOPS_LEGACY_PATH . '/admin/preload/AdminThemeSelectPreload.class.php')) {
            require_once XOOPS_LEGACY_PATH . '/admin/blocks/AdminThemeSelect.class.php';
            $this->mController->_mBlockChain[] = new Legacy_AdminThemeSelect();
            }

		//you can add your own block here!

	}
      
    public static function AdminDashboard()
    {

        // Render Output Options 
        // Template 'legacy_dummy.html'         : $type = 0
        // Template 'legacy_admin_welcome.html' : $type = 1   
        if (XC_ADMINDASHBOARD_WELCOME) {

            $type = 1;
            
            // Render Raw
            if (0 == $type) {
 
                $welcome = '<b>Welcome to XOOPS Cube Legacy!!</b><br />Have a nice and happy time!';

                $attributes = [];
                $attributes['dummy_content'] = $welcome;
                $template = self::getTemplate('legacy_dummy.html');

                Legacy_AdminDashboard::display_message($attributes, $template, $return = false);
            } // if type=0


            // Render Theme Template
            elseif (1 == $type) {
            //Dashboard
            $template = self::getTemplate('admin_dashboard.html');
          
                if (file_exists($template)) {
                
                // Module Handler
                $moduleHandler =& xoops_getHandler('module');
                $module_total = $moduleHandler->getCount();
                $active_module_total = $moduleHandler->getCount(new Criteria('isactive', 1));

                $welcome_title = 'Welcome Message!';

                    $welcome_msg = [];
                    $welcome_msg[] = 'Welcome to XOOPS Cube Legacy!';
                    $welcome_msg[] = 'Have a nice and happy time!';

                    $attributes = [];
                    $attributes['title'] = $welcome_title;
                    $attributes['messages'] = $welcome_msg;
                    $attributes['ModuleTotal'] = $module_total;
                    $attributes['activeModuleTotal'] = $active_module_total;
                    $attributes['inactiveModuleTotal'] = $module_total - $active_module_total;

                    Legacy_AdminDashboard::display_message($attributes, $template, $return = false);
                } // if Template file_exists
            } // if type=1
        } // Output WELCOME



        /**
         * ADMIN System Info
         */ 
        if (XC_ADMINDASHBOARD_SYSTEMINFO) {
            
            $systeminfo_message = [];

            if (defined('XOOPS_DISTRIBUTION_VERSION')) {
                $systeminfo_message[] = 'Distribution : ' . XOOPS_DISTRIBUTION_VERSION;
            }
            $systeminfo_message[] = _AD_LEGACY_XCLEGACYVERSION . ' : ' . XOOPS_VERSION;
            $systeminfo_message[] = _MD_AM_DTHEME . ' : ' . $root->mContext->mXoopsConfig['theme_set'];
            $systeminfo_message[] = _MD_AM_DTPLSET . ' : ' . $root->mContext->mXoopsConfig['template_set'];
            $systeminfo_message[] = _MD_AM_LANGUAGE . ' : ' . $root->mContext->mXoopsConfig['language'];
        
            $debugmode = (int)$root->mContext->mXoopsConfig['debug_mode'];
                if (0 == $debugmode) {
                    $systeminfo_message[] = _MD_AM_DEBUGMODE . ' : ' . _MD_AM_DEBUGMODE0;
                } elseif (1 == $debugmode) {
                    $systeminfo_message[] = _MD_AM_DEBUGMODE . ' : ' . _MD_AM_DEBUGMODE1;
                } elseif (2 == $debugmode) {
                    $systeminfo_message[] = _MD_AM_DEBUGMODE . ' : ' . _MD_AM_DEBUGMODE2;
                } elseif (3 == $debugmode) {
                    $systeminfo_message[] = _MD_AM_DEBUGMODE . ' : ' . _MD_AM_DEBUGMODE3;
                }

            $systemconfig = [];
            $systemconfig['phpversion'] = phpversion();

                $db = &$root->mController->getDB();
                $result = $db->query('SELECT VERSION()');
                list($mysqlversion) = $db->fetchRow($result);

            $systemconfig['mysqlversion'] = $mysqlversion;
            $systemconfig['os'] = substr(php_uname(), 0, 7);
            $systemconfig['server'] = xoops_getenv('SERVER_SOFTWARE');
            $systemconfig['useragent'] = xoops_getenv('HTTP_USER_AGENT');

            $systeminfo_message[] = _AD_LEGACY_OS . ' : ' . $systemconfig['os'];
            $systeminfo_message[] = _AD_LEGACY_SERVER . ' : ' . $systemconfig['server'];
            $systeminfo_message[] = _AD_LEGACY_USERAGENT . ' : ' . $systemconfig['useragent'];
            $systeminfo_message[] = _AD_LEGACY_PHPVERSION . ' : ' . $systemconfig['phpversion'];
            $systeminfo_message[] = _AD_LEGACY_MYSQLVERSION . ' : ' . $systemconfig['mysqlversion'];

            xoops_result($systeminfo_message, _AD_LEGACY_SYSTEMINFO, 'tips');
        } // if Systeminfo

        
        // PHP Settings
        if (XC_ADMINDASHBOARD_PHPSETTING) {
            $phpsetting_message = [];

            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_SM . ' : ' . (ini_get('safe_mode')? '<span style=color:red>' . _AD_LEGACY_PHPSETTING_ON . '</span>' : '<span style=color:green>' . _AD_LEGACY_PHPSETTING_OFF . '</span>');
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_MET . ' : ' . (ini_get('max_execution_time')? ini_get('max_execution_time') . ' sec.' : _AD_LEGACY_PHPSETTING_OFF);
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_ML . ' : ' . (ini_get('memory_limit')? ini_get('memory_limit') . 'b' : _AD_LEGACY_PHPSETTING_OFF);
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_DE . ' : ' . (ini_get('display_errors')? '<span style=color:green>' . _AD_LEGACY_PHPSETTING_ON . '</span>' : '<span style=color:red>' . _AD_LEGACY_PHPSETTING_OFF . '</span>');
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_SOT . ' : ' . (ini_get('short_open_tag')? '<span style=color:green>' . _AD_LEGACY_PHPSETTING_ON . '</span>' : '<span style=color:red>' . _AD_LEGACY_PHPSETTING_OFF . '</span>');
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_FU . ' : ' . (ini_get('file_uploads')? _AD_LEGACY_PHPSETTING_ON . ' ( ' . _AD_LEGACY_PHPSETTING_FU_UMAX . ini_get('upload_max_filesize') . ', ' . _AD_LEGACY_PHPSETTING_FU_PMAX . ini_get('post_max_size') . ' )' : _AD_LEGACY_PHPSETTING_OFF);
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_MQ . ' : ' . (ini_get('magic_quotes_gpc')? '<span style=color:green>' . _AD_LEGACY_PHPSETTING_ON . '</span>' : '<span style=color:red>' . _AD_LEGACY_PHPSETTING_OFF . '</span>');
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_RG . ' : ' . (ini_get('register_globals')? '<span style=color:red>' . _AD_LEGACY_PHPSETTING_ON . ' (recommended OFF)</span>' : '<span style=color:green>' . _AD_LEGACY_PHPSETTING_OFF . '</span>');
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_OB . ' : ' . (ini_get('output_buffering')? '<span style=color:red>' . _AD_LEGACY_PHPSETTING_ON . '</span>' : '<span style=color:green>' . _AD_LEGACY_PHPSETTING_OFF . '</span>');
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_OBD . ' : ' . (ini_get('open_basedir')? '<span style=color:green>' . _AD_LEGACY_PHPSETTING_ON . '</span>' : '<span style=color:red>' . _AD_LEGACY_PHPSETTING_OFF . '</span>');
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_UFO . ' : ' . (ini_get('allow_url_fopen')? '<span style=color:red>' . _AD_LEGACY_PHPSETTING_ON . ' (recommended OFF)</span>' : '<span style=color:green>' . _AD_LEGACY_PHPSETTING_OFF . '</span>');
        
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_DOM . ' : ' . (extension_loaded('dom')? '<span style=color:green>' . _YES . '</span>' : '<span style=color:red>' . _NO . ' (required by recent modules)</span>');
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_EXIF . ' : ' . (extension_loaded('exif')? '<span style=color:green>' . _YES . '</span>' : '<span style=color:red>' . _NO . ' (required by recent modules)</span>');
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_GTXT . ' : ' . (extension_loaded('gettext')? '<span style=color:green>' . _YES . '</span>' : '<span style=color:red>' . _NO . ' (required by recent modules)</span>');
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_JSON . ' : ' . (extension_loaded('json')? '<span style=color:green>' . _YES . '</span>' : '<span style=color:red>' . _NO . ' (required by recent modules)</span>');
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_XML . ' : ' . (extension_loaded('xml')? '<span style=color:green>' . _YES . '</span>' : '<span style=color:red>' . _NO . ' (required by recent modules)</span>');
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_CRL . ' : ' . (extension_loaded('curl')? '<span style=color:green>' . _YES . '</span>' : '<span style=color:red>' . _NO . ' (required by recent modules)</span>');
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_ZLIB . ' : ' . (extension_loaded('zlib')? '<span style=color:green>' . _YES . '</span>' : '<span style=color:red>' . _NO . ' (required by recent modules)</span>');
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_SOAP . ' : ' . (extension_loaded('soap')? '<span style=color:green>' . _YES . '</span>' : '<span style=color:red>' . _NO . ' (required by recent modules)</span>');
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_MB . ' : ' . (extension_loaded('mbstring')? '<span style=color:green>' . _YES . '</span>' : '<span style=color:red>' . _NO . ' (required by recent modules)</span>');
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_ICONV . ' : ' . (function_exists('iconv')? '<span style=color:green>' . _YES . '</span>' : '<span style=color:red>' . _NO . ' (required by recent modules)</span>');
            $phpsetting_message[] = _AD_LEGACY_PHPSETTING_GD . ' : ' . (function_exists('gd_info')? '<span style=color:green>' . _YES . '</span>' : '<span style=color:red>' . _NO . ' (required by recent modules)</span>');

            if (function_exists('gd_info')) {
                $gd_info = gd_info() ;
                $phpsetting_message[] =  "GD Version: {$gd_info['GD Version']}" ;
            }
    
            if (function_exists('imagecreatetruecolor')) {
                $phpsetting_message[] = _AD_LEGACY_PHPSETTING_GD . ' Image create Truecolor';
            }

            xoops_result($phpsetting_message, _AD_LEGACY_PHPSETTING, 'tips');
        }
        
        
        // Waiting
        if (XC_ADMINDASHBOARD_WAITING) {

            $modules = [];
            XCube_DelegateUtils::call('Legacyblock.Waiting.Show', new XCube_Ref($modules));

            $attributes = [];
            $attributes['block']['modules'] = $modules;

            $template = self::getTemplate('legacy_block_waiting.html', 'blocks/');

            $result = Legacy_AdminDashboard::display_message($attributes, $template, $return = true);
            xoops_result($result, _MI_LEGACY_BLOCK_WAITING_NAME);
        }//waiting if


        // COMMENTS
        if (XC_ADMINDASHBOARD_COMMENTS) {

            $modules = [];
            XCube_DelegateUtils::call('b_legacy_comments_show', new XCube_Ref($modules));

            $attributes = [];
            $attributes['block']['modules'] = $modules;

            $template = self::getTemplate('legacy_block_comments.html', 'blocks/');

            $result = Legacy_AdminDashboard::display_message($attributes, $template, $return = true);
            xoops_result($result, _MB_LEGACY_DISPLAYC);
        }//waiting if


        // PHP Information
        if (XC_ADMINDASHBOARD_PHPINFO) {
            //some code borrowed from joomla
        ob_start();
            phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES);
            $phpinfo = ob_get_contents();
            ob_end_clean();
            preg_match_all('#<body[^>]*>(.*)</body>#siU', $phpinfo, $output);
            $output = preg_replace('#<table#', '<table class="outer""', $output[1][0]);
            $output = preg_replace('#(\w),(\w)#', '\1, \2', $output);
            $output = preg_replace('#border="0" cellpadding="3" width="600"#', '', $output);
            $output = preg_replace('#<hr />#', '', $output);
            $output = preg_replace('#class="e"#', 'class="even"', $output);
            $output = preg_replace('#class="h"#', 'class="odd"', $output);
            $output = preg_replace('#class="v"#', 'class="even"', $output);
            $output = preg_replace('#class="p"#', 'class="odd"', $output);
            $output = str_replace('<div class="center">', '', $output);
            $output = str_replace('</div>', '', $output);
            $attributes = [];
            $attributes['dummy_content'] = $output;
            $template = self::getTemplate('legacy_dummy.html');
            Legacy_AdminDashboard::display_message($attributes, $template, $return = false);
        }//phpinfo if

    }  // AdminDashboard


    public static function display_message($attributes = [], $template= '', $return = false)
    {
        $root =& XCube_Root::getSingleton();

        $renderSystem =& $root->getRenderSystem($root->mContext->mBaseRenderSystemName);
        
        $renderTarget =& $renderSystem->createRenderTarget('main');
        //$renderTarget->setAttribute('legacy_module', 'legacy');
        $renderTarget->setTemplateName($template);

        foreach (array_keys($attributes) as $attribute) {
            $renderTarget->setAttribute($attribute, $attributes[$attribute]);
        }
        $renderSystem->render($renderTarget);
        if (true == $return) {
            $ret = $renderTarget->getResult();
            return $ret;
        } else {
            print $renderTarget->getResult();
        }
    }

    
    private static function getTemplate($file, $prefix = null)
    {
        $infoArr = Legacy_get_override_file($file, $prefix);
        if ($prefix) {
            $file = $prefix . $file;
        }
        
        if (null != $infoArr['theme'] && null != $infoArr['dirname']) {
            return XOOPS_THEME_PATH . '/' . $infoArr['theme'] . '/modules/' . $infoArr['dirname'] . '/' . $file;
        } elseif (null != $infoArr['theme']) {
            return XOOPS_THEME_PATH . '/' . $infoArr['theme'] . '/' . $file;
        } elseif (null != $infoArr['dirname']) {
            return XOOPS_MODULE_PATH . '/' . $infoArr['dirname'] . '/admin/templates/' . $file;
        }
        
        return LEGACY_ADMIN_RENDER_FALLBACK_PATH . '/' . $file;
    }
}
