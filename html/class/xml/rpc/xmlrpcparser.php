<?php
// $Id: xmlrpcparser.php,v 1.1 2007/05/15 02:34:53 minahito Exp $
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
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: https://www.myweb.ne.jp/, https://www.xoops.org/, https://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

require_once XOOPS_ROOT_PATH.'/class/xml/saxparser.php';
require_once XOOPS_ROOT_PATH.'/class/xml/xmltaghandler.php';

/**
* Class RSS Parser
*
* This class offers methods to parse RSS Files
*
* @link      https://www.xoops.org/ Latest release of this class
* @package   XOOPS
* @copyright Copyright (c) 2001 xoops.org. All rights reserved.
* @author    Kazumi Ono <onokazu@xoops.org>
* @version   1.6 ($Date: 2007/05/15 02:34:53 $) $Revision: 1.1 $
* @access    public
*/

class XoopsXmlRpcParser extends SaxParser
{

    /**
    *
    *
    *
    *
    * @access private
    * @var    array
    */
    public $_param;

    /**
    *
    *
    *
    *
    * @access private
    * @var    string
    */
    public $_methodName;

    /**
    *
    *
    *
    *
    * @access private
    * @var    array
    */
    public $_tempName;

    /**
    *
    *
    *
    *
    * @access private
    * @var    array
    */
    public $_tempValue;

    /**
    *
    *
    *
    *
    * @access private
    * @var    array
    */
    public $_tempMember;

    /**
    *
    *
    *
    *
    * @access private
    * @var    array
    */
    public $_tempStruct;

    /**
    *
    *
    *
    *
    * @access private
    * @var    array
    */
    public $_tempArray;

    /**
    *
    *
    *
    *
    * @access private
    * @var    array
    */
    public $_workingLevel = [];

    /**
     * Constructor of the class
     *
     *
     *
     *
     * @access
     * @param $input
     * @see
     * @author
     */
    public function __construct(&$input)
    {
        parent::__construct($input);
        $this->addTagHandler(new RpcMethodNameHandler());
        $this->addTagHandler(new RpcIntHandler());
        $this->addTagHandler(new RpcDoubleHandler());
        $this->addTagHandler(new RpcBooleanHandler());
        $this->addTagHandler(new RpcStringHandler());
        $this->addTagHandler(new RpcDateTimeHandler());
        $this->addTagHandler(new RpcBase64Handler());
        $this->addTagHandler(new RpcNameHandler());
        $this->addTagHandler(new RpcValueHandler());
        $this->addTagHandler(new RpcMemberHandler());
        $this->addTagHandler(new RpcStructHandler());
        $this->addTagHandler(new RpcArrayHandler());
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param
     * @return void
     * @author
     * @see
     */
    public function setTempName($name)
    {
        $this->_tempName[$this->getWorkingLevel()] = $name;
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return mixed
     * @author
     * @see
     */
    public function getTempName()
    {
        return $this->_tempName[$this->getWorkingLevel()];
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param
     * @return void
     * @author
     * @see
     */
    public function setTempValue($value)
    {
        if (is_array($value)) {
            $this->_tempValue = (array)$this->_tempValue;
            foreach ($value as $k => $v) {
                $this->_tempValue[$k] = $v;
            }
        } elseif (is_string($value)) {
            if (isset($this->_tempValue)) {
                if (is_string($this->_tempValue)) {
                    $this->_tempValue .= $value;
                }
            } else {
                $this->_tempValue = $value;
            }
        } else {
            $this->_tempValue = $value;
        }
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return array
     * @author
     * @see
     */
    public function getTempValue()
    {
        return $this->_tempValue;
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return void
     * @author
     * @see
     */
    public function resetTempValue()
    {
        unset($this->_tempValue);
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param $name
     * @param $value
     * @return void
     * @author
     * @see
     */
    public function setTempMember($name, $value)
    {
        $this->_tempMember[$this->getWorkingLevel()][$name] = $value;
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return mixed
     * @author
     * @see
     */
    public function getTempMember()
    {
        return $this->_tempMember[$this->getWorkingLevel()];
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return void
     * @author
     * @see
     */
    public function resetTempMember()
    {
        $this->_tempMember[$this->getCurrentLevel()] = [];
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return void
     * @author
     * @see
     */
    public function setWorkingLevel()
    {
        array_push($this->_workingLevel, $this->getCurrentLevel());
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return mixed
     * @author
     * @see
     */
    public function getWorkingLevel()
    {
        return $this->_workingLevel[count($this->_workingLevel) - 1];
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return void
     * @author
     * @see
     */
    public function releaseWorkingLevel()
    {
        array_pop($this->_workingLevel);
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param
     * @return void
     * @author
     * @see
     */
    public function setTempStruct($member)
    {
        $key = key($member);
        $this->_tempStruct[$this->getWorkingLevel()][$key] = $member[$key];
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return mixed
     * @author
     * @see
     */
    public function getTempStruct()
    {
        return $this->_tempStruct[$this->getWorkingLevel()];
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return void
     * @author
     * @see
     */
    public function resetTempStruct()
    {
        $this->_tempStruct[$this->getCurrentLevel()] = [];
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param
     * @return void
     * @author
     * @see
     */
    public function setTempArray($value)
    {
        $this->_tempArray[$this->getWorkingLevel()][] = $value;
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return mixed
     * @author
     * @see
     */
    public function getTempArray()
    {
        return $this->_tempArray[$this->getWorkingLevel()];
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return void
     * @author
     * @see
     */
    public function resetTempArray()
    {
        $this->_tempArray[$this->getCurrentLevel()] = [];
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param
     * @return void
     * @author
     * @see
     */
    public function setMethodName($methodName)
    {
        $this->_methodName = $methodName;
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return string
     * @author
     * @see
     */
    public function getMethodName()
    {
        return $this->_methodName;
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param
     * @return void
     * @author
     * @see
     */
    public function setParam($value)
    {
        $this->_param[] = $value;
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return array
     * @author
     * @see
     */
    public function &getParam()
    {
        return $this->_param;
    }
}


class RpcMethodNameHandler extends XmlTagHandler
{

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return string
     * @author
     * @see
     */
    public function getName()
    {
        return 'methodName';
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param $parser
     * @param $data
     * @return void
     * @author
     * @see
     */
    public function handleCharacterData($parser, &$data)
    {
        $parser->setMethodName($data);
    }
}

class RpcIntHandler extends XmlTagHandler
{

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return array
     * @author
     * @see
     */
    public function getName()
    {
        return ['int', 'i4'];
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param $parser
     * @param $data
     * @return void
     * @author
     * @see
     */
    public function handleCharacterData($parser, $data)
    {
        $parser->setTempValue((int)$data);
    }
}

class RpcDoubleHandler extends XmlTagHandler
{

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return string
     * @author
     * @see
     */
    public function getName()
    {
        return 'double';
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param $parser
     * @param $data
     * @return void
     * @author
     * @see
     */
    public function handleCharacterData($parser, &$data)
    {
        $data = (float)$data;
        $parser->setTempValue($data);
    }
}

class RpcBooleanHandler extends XmlTagHandler
{

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return string
     * @author
     * @see
     */
    public function getName()
    {
        return 'boolean';
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param $parser
     * @param $data
     * @return void
     * @author
     * @see
     */
    public function handleCharacterData($parser, &$data)
    {
        $data = (boolean)$data;
        $parser->setTempValue($data);
    }
}

class RpcStringHandler extends XmlTagHandler
{

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return string
     * @author
     * @see
     */
    public function getName()
    {
        return 'string';
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param $parser
     * @param $data
     * @return void
     * @author
     * @see
     */
    public function handleCharacterData($parser, $data)
    {
        $parser->setTempValue((string)$data);
    }
}

class RpcDateTimeHandler extends XmlTagHandler
{

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return string
     * @author
     * @see
     */
    public function getName()
    {
        return 'dateTime.iso8601';
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param $parser
     * @param $data
     * @return void
     * @author
     * @see
     */
    public function handleCharacterData($parser, $data)
    {
        $matches = [];
        if (!preg_match('/^([0-9]{4})([0-9]{2})([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})$/', $data, $matches)) {
            $parser->setTempValue(time());
        } else {
            $parser->setTempValue(gmmktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[3], $matches[1]));
        }
    }
}

class RpcBase64Handler extends XmlTagHandler
{

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return string
     * @author
     * @see
     */
    public function getName()
    {
        return 'base64';
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param $parser
     * @param $data
     * @return void
     * @author
     * @see
     */
    public function handleCharacterData($parser, $data)
    {
        $parser->setTempValue(base64_decode($data));
    }
}

class RpcNameHandler extends XmlTagHandler
{

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return string
     * @author
     * @see
     */
    public function getName()
    {
        return 'name';
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param $parser
     * @param $data
     * @return void
     * @author
     * @see
     */
    public function handleCharacterData($parser, &$data)
    {
        switch ($parser->getParentTag()) {
        case 'member':
            $parser->setTempName($data);
            break;
        default:
            break;
        }
    }
}


class RpcValueHandler extends XmlTagHandler
{

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return string
     * @author
     * @see
     */
    public function getName()
    {
        return 'value';
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param $parser
     * @param $data
     * @return void
     * @author
     * @see
     */
    public function handleCharacterData($parser, &$data)
    {
        switch ($parser->getParentTag()) {
        case 'member':
            $parser->setTempValue($data);
            break;
        case 'data':
        case 'array':
            $parser->setTempValue($data);
            break;
        default:
            break;
        }
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param $parser
     * @param $attributes
     * @return void
     * @author
     * @see
     */
    public function handleBeginElement(&$parser, &$attributes)
    {
        //$parser->resetTempValue();
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param
     * @return void
     * @author
     * @see
     */
    public function handleEndElement($parser)
    {
        switch ($parser->getCurrentTag()) {
        case 'member':
            $parser->setTempMember($parser->getTempName(), $parser->getTempValue());
            break;
        case 'array':
        case 'data':
            $parser->setTempArray($parser->getTempValue());
            break;
        default:
            $parser->setParam($parser->getTempValue());
            break;
        }
        $parser->resetTempValue();
    }
}

class RpcMemberHandler extends XmlTagHandler
{

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return string
     * @author
     * @see
     */
    public function getName()
    {
        return 'member';
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param $parser
     * @param $attributes
     * @return void
     * @author
     * @see
     */
    public function handleBeginElement($parser, &$attributes)
    {
        $parser->setWorkingLevel();
        $parser->resetTempMember();
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param
     * @return void
     * @author
     * @see
     */
    public function handleEndElement($parser)
    {
        $member =& $parser->getTempMember();
        $parser->releaseWorkingLevel();
        $parser->setTempStruct($member);
    }
}

class RpcArrayHandler extends XmlTagHandler
{

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @return string
     * @author
     * @see
     */
    public function getName()
    {
        return 'array';
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param $parser
     * @param $attributes
     * @return void
     * @author
     * @see
     */
    public function handleBeginElement($parser, &$attributes)
    {
        $parser->setWorkingLevel();
        $parser->resetTempArray();
    }

    /**
     * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
     *
     * @access
     * @param
     * @return void
     * @author
     * @see
     */
    public function handleEndElement($parser)
    {
        $parser->setTempValue($parser->getTempArray());
        $parser->releaseWorkingLevel();
    }
}

class RpcStructHandler extends XmlTagHandler
{

    /**
     *
     *
     * @access
     * @return string
     * @author
     * @see
     */
    public function getName()
    {
        return 'struct';
    }

    /**
     *
     *
     * @access
     * @param $parser
     * @param $attributes
     * @return void
     * @author
     * @see
     */
    public function handleBeginElement($parser, &$attributes)
    {
        $parser->setWorkingLevel();
        $parser->resetTempStruct();
    }

    /**
     *
     *
     * @access
     * @param
     * @return void
     * @author
     * @see
     */
    public function handleEndElement($parser)
    {
        $parser->setTempValue($parser->getTempStruct());
        $parser->releaseWorkingLevel();
    }
}
