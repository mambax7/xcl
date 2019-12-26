<?php

// singleton for xoops_breadcrumbs
class AltsysBreadcrumbs
{

public $paths = [];

	// !Fix PHP7 NOTICE: deprecated constructor
	public function __construct()
    // public function AltsysBreadcrumbs()
    {
    }
    //HACK by domifara for php5.3+
    //function &getInstance()
    public static function &getInstance()
    {
        static $instance ;
        if (! isset($instance)) {
            $instance = new AltsysBreadcrumbs() ;
        }
        return $instance ;
    }

    public function getXoopsBreadcrumbs()
    {
        $ret = [];
        foreach ($this->paths as $val) {
            // delayed language constant
        if (substr($val['name'], 0, 1) == '_' && defined($val['name'])) {
            $ret[] = [
                'url' => $val['url'] ,
                'name' => constant($val['name']) ,
            ];
        } else {
            $ret[] = $val ;
        }
        }
        unset($ret[ sizeof($ret) - 1 ]['url']) ;
        return $ret ;
    }

    // all data should be escaped
    public function appendPath($url_or_path, $name = '...')
    {
        if (is_array($url_or_path)) {
            if (empty($url_or_path['name'])) {
                // multiple paths
                $this->paths = array_merge($this->paths, $url_or_path) ;
            } else {
                // array format (just a path)
                $this->paths[] = $url_or_path ;
            }
        } else {
            // separate format
            $this->paths[] = ['url' => $url_or_path, 'name' => $name];
        }
    }

    public function hasPaths()
    {
        return ! empty($this->paths) ;
    }
}
