<?php
if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

class viewAction extends AbstractAction
{
    private $inout = 'inbox';
    private $msgdata = null;
  
    public function __construct()
    {
        parent::__construct();
    }
  
    public function execute()
    {
        if ('in' == $this->root->mContext->mRequest->getRequest('inout')) {
            $this->inout = 'inbox';
        } else {
            $this->inout = 'outbox';
        }
    
        $boxid = (int)$this->root->mContext->mRequest->getRequest($this->inout);
        $modHand = xoops_getmodulehandler($this->inout);
        $modObj = $modHand->get($boxid);
        if (!is_object($modObj)) {
            $this->setErr(_MD_MESSAGE_ACTIONMSG1);
            return;
        }
    
        if ($modObj->get('uid') != $this->root->mContext->mXoopsUser->get('uid')) {
            $this->setErr(_MD_MESSAGE_ACTIONMSG8);
            return;
        }
    
        if ('inbox' == $this->inout) {
            if ('POST' == $_SERVER['REQUEST_METHOD']) {
                if ('lock' == $this->root->mContext->mRequest->getRequest('cmd')) {
                    if (1 == (int)$this->root->mContext->mRequest->getRequest('lock')) {
                        $modObj->set('is_read', 2);
                    } else {
                        $modObj->set('is_read', 1);
                    }
                    $modHand->insert($modObj);
                } elseif ('mail' == $this->root->mContext->mRequest->getRequest('cmd')) {
                    $this->send_mail($modObj);
                }
            } elseif (0 == $modObj->get('is_read')) {
                $modObj->set('is_read', 1);
                $modHand->insert($modObj, true);
            }
        }
    
        foreach (array_keys($modObj->gets()) as $var_name) {
            $this->msgdata[$var_name] = $modObj->getShow($var_name);
        }
        if ('inbox' == $this->inout) {
            $this->msgdata['fromname'] = $this->getLinkUnameFromId($this->msgdata['from_uid'], $this->msgdata['uname']);
        } else {
            $this->msgdata['toname'] = $this->getLinkUnameFromId($this->msgdata['to_uid'], $this->root->mContext->mXoopsConfig['anonymous']);
        }
    }
  
    private function send_mail($obj)
    {
        /*
    require_once XOOPS_ROOT_PATH.'/class/mail/phpmailer/class.phpmailer.php';
    require_once _MY_MODULE_PATH.'class/MyMailer.class.php';
    $mailer = new My_Mailer();
    $mailer->prepare();
    $mailer->setFromName($this->root->mContext->mXoopsConfig['sitename']);
    $mailer->setFromEmail($this->root->mContext->mXoopsConfig['adminmail']);
    $mailer->setTo($this->root->mContext->mXoopsUser->get('email'), $this->root->mContext->mXoopsUser->get('uname'));
    $mailer->setSubject($obj->get('title'));
    $mailer->setBody($obj->get('message'));
    $mailer->Send();
    */
    $mailer = $this->getMailer();
        $mailer->setFromName($this->root->mContext->mXoopsConfig['sitename']);
        $mailer->setFromEmail($this->root->mContext->mXoopsConfig['adminmail']);
        $mailer->setToEmails($this->root->mContext->mXoopsUser->get('email'));
        $mailer->setSubject($obj->get('title'));
        $mailer->setBody($obj->get('message'));
        $mailer->send();
    }
  
    public function executeView($render)
    {
        if ('inbox' == $this->inout) {
            $render->setTemplateName('message_inboxview.html');
        } else {
            $render->setTemplateName('message_outboxview.html');
        }
        $render->setAttribute('msgdata', $this->msgdata);
    }
}
