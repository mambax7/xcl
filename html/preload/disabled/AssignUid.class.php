<?php

/*
* Preload : AssignUid.class.php
* Preload to use $ uid in block template in XOOPS Cube Legacy
* Author : Naaon
* Site   : http://www.naaon.com/modules/plactice/index.php/home/hobby/xoops/assignUid.htm
* get the $ uid of the viewer with the block template
* This way, you can use <{$ uid}> in each block template
*/

class AssignUid extends XCube_ActionFilter
{
    function preBlockFilter()
    {
        $this->mRoot->mDelegateManager->add('XoopsTpl.New', array(&$this, 'assign'));
    }

    function assign($xoopsTpl)
    {
        $root =& XCube_Root::getSingleton();
        if ($root->mContext->mUser->mIdentity->isAuthenticated()) {
            $uid = $root->mContext->mXoopsUser->get('uid');
              $xoopsTpl->assign('uid',$uid);
        }
    }
}
?>
