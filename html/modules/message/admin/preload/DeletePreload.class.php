<?php
/**
 * @author Marijuana
 * @license https://www.gnu.org/licenses/gpl.txt GNU GENERAL PUBLIC LICENSE Version 2
 */

if (!defined('XOOPS_ROOT_PATH')) {
    die();
}

class message_DeletePreload extends XCube_ActionFilter
{
    public function postFilter()
    {
        $this->mRoot->mDelegateManager->add('Legacypage.Admin.SystemCheck', 'message_DeletePreload::deleteMessage');
    }
  
    public static function deleteMessage()
    {
        $confHand = xoops_getHandler('config');
        $modconf = $confHand->getConfigsByDirname('message');

        $inHand = xoops_getModuleHandler('inbox', 'message');
        $inHand->deleteDays($modconf['savedays'], $modconf['dletype']);
    
        $outHand = xoops_getModuleHandler('outbox', 'message');
        $outHand->deleteDays($modconf['savedays']);
    }
}
