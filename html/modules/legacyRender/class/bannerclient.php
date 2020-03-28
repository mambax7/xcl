<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

class LegacyRenderBannerclientObject extends XoopsSimpleObject
{
    public $mBanners = [];
    public $_mBannersLoadedFlag = false;
    
    /**
     * @todo A name of this property is a strange. banner finish?
     */
    public $mFinishBanners = [];
    public $_mFinishBannersLoadedFlag = false;
    
    public $mBannerCount = null;
    public $_mBannerCountLoadedFlag = false;

    public $mFinishBannerCount = null;
    public $_mFinishBannerCountLoadedFlag = false;

    // !Fix deprecated constructor for PHP 7.x
    public function __construct()
    // public function LegacyRenderBannerclientObject()
    {
        static $initVars;
        if (isset($initVars)) {
            $this->mVars = $initVars;
            return;
        }
        $this->initVar('cid', XOBJ_DTYPE_INT, '', false);
        $this->initVar('name', XOBJ_DTYPE_STRING, '', true, 60);
        $this->initVar('contact', XOBJ_DTYPE_STRING, '', true, 60);
        $this->initVar('email', XOBJ_DTYPE_STRING, '', true, 60);
        $this->initVar('login', XOBJ_DTYPE_STRING, '', true, 10);
        $this->initVar('passwd', XOBJ_DTYPE_STRING, '', true, 10);
        $this->initVar('extrainfo', XOBJ_DTYPE_TEXT, '', true);
        $initVars=$this->mVars;
    }

    public function loadBanner()
    {
        if (false == $this->_mBannersLoadedFlag) {
            $handler =& xoops_getModuleHandler('banner', 'legacyRender');
            $this->mBanners =& $handler->getObjects(new Criteria('cid', $this->get('cid')));
            $this->_mBannersLoadedFlag = true;
        }
    }

    public function loadBannerCount()
    {
        if (false == $this->_mBannerCountLoadedFlag) {
            $handler =& xoops_getModuleHandler('banner', 'legacyRender');
            $this->mBannerCount = $handler->getCount(new Criteria('cid', $this->get('cid')));
            $this->_mBannerCountLoadedFlag = true;
        }
    }

    public function &createBanner()
    {
        $handler =& xoops_getModuleHandler('banner', 'legacyRender');
        $obj =& $handler->create();
        $obj->set('cid', $this->get('cid'));
        return $obj;
    }

    public function loadBannerfinish()
    {
        if (false == $this->_mFinishBannersLoadedFlag) {
            $handler =& xoops_getModuleHandler('bannerfinish', 'legacyRender');
            $this->mFinishBanners =& $handler->getObjects(new Criteria('cid', $this->get('cid')));
            $this->_mFinishBannersLoadedFlag = true;
        }
    }

    public function loadFinishBannerCount()
    {
        if (false == $this->_mFinishBannerCountLoadedFlag) {
            $handler =& xoops_getModuleHandler('bannerfinish', 'legacyRender');
            $this->mFinishBannerCount = $handler->getCount(new Criteria('cid', $this->get('cid')));
            $this->_mFinishBannerCountLoadedFlag = true;
        }
    }

    public function &createBannerfinish()
    {
        $handler =& xoops_getModuleHandler('bannerfinish', 'legacyRender');
        $obj =& $handler->create();
        $obj->set('cid', $this->get('cid'));
        return $obj;
    }
}

class LegacyRenderBannerclientHandler extends XoopsObjectGenericHandler
{
    public $mTable = 'bannerclient';
    public $mPrimary = 'cid';
    public $mClass = 'LegacyRenderBannerclientObject';

    // !Fix compatibility with XoopsObjectGenericHandler::delete(&$obj, $force = false)
    public function delete(&$obj, $force = false)
    //public function delete(&$obj)
    {
        $handler =& xoops_getModuleHandler('banner', 'legacyRender');
        $handler->deleteAll(new Criteria('cid', $obj->get('cid')));
        unset($handler);
    
        $handler =& xoops_getModuleHandler('bannerfinish', 'legacyRender');
        $handler->deleteAll(new Criteria('cid', $obj->get('cid')));
        unset($handler);
    
        return parent::delete($obj);
    }
}
