<?php
/**
 *
 * @package Legacy
 * @version $Id: Mailer.php,v 1.4 2008/09/25 15:12:43 kilica Exp $
 * @copyright Copyright 2005-2007 XOOPS Cube Project  <https://github.com/xoopscube/legacy>
 * @license https://github.com/xoopscube/legacy/blob/master/docs/GPL_V2.txt GNU GENERAL PUBLIC LICENSE Version 2
 *
 */

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

/**
 * This is an exmine class for mail.
 */
class Legacy_Mailer extends PHPMailer
{
    /**
     * @type XCube_Delegate
     */
    public $mConvertLocal = null;
    
    public function __construct()
    {
        $this->mConvertLocal =new XCube_Delegate();
        $this->mConvertLocal->register('Legacy_Mailer.ConvertLocal');
    }
    
    public function prepare()
    {
        $root =& XCube_Root::getSingleton();
        
        $handler =& xoops_getHandler('config');
        $xoopsMailerConfig =& $handler->getConfigsByCat(XOOPS_CONF_MAILER);
        $this->reset();
        
        if ('' == $xoopsMailerConfig['from']) {
            $this->From = $root->mContext->mXoopsConfig['adminmail'];
        } else {
            $this->From = $xoopsMailerConfig['from'];
        }
        
        $this->Sender = $root->mContext->mXoopsConfig['adminmail'];
        
        $this->SetLanguage = LEGACY_MAIL_LANG;
        $this->CharSet = LEGACY_MAIL_CHAR;
        $this->Encoding = LEGACY_MAIL_ENCO;
        
        switch ($xoopsMailerConfig['mailmethod']) {
            case 'smtpauth':
                $this->isSMTP();
                $this->SMTPAuth = true;
                $this->Host = implode(';', $xoopsMailerConfig['smtphost']);
                $this->Username = $xoopsMailerConfig['smtpuser'];
                $this->Password = $xoopsMailerConfig['smtppass'];
                break;
                
            case 'smtp':
                $this->isSMTP();
                $this->SMTPAuth = false;
                $this->Host = implode(';', $xoopsMailerConfig['smtphost']);
                break;
                
            case 'sendmail':
                $this->isSendmail();
                $this->Sendmail = $xoopsMailerConfig['sendmailpath'];
                break;
        }
        
        return true;
    }
    
    public function setFrom($text)
    {
        $this->From = $text;
    }
    
    public function setFromname($text)
    {
        $this->FromName = $this->convertLocal($text, 2);
    }
    
    public function setSubject($text)
    {
        $this->Subject = $this->convertLocal($text, true);
    }
  
    public function setBody($text)
    {
        $this->Body = $this->convertLocal($text);
    }
    
    public function setTo($add, $name)
    {
        $this->addAddress($add, $this->convertLocal($name, true));
    }
    
    public function reset()
    {
        $this->clearAllRecipients();
        $this->Body = '';
        $this->Subject = '';
    }
    
    public function convertLocal($text, $mime = false)
    {
        $this->mConvertLocal->call(new XCube_Ref($text), $mime);
        return $text;
    }
}
