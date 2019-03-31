<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 30.03.19
 * Time: 0:47
 */

namespace Opus\Common;


class Config
{
    protected $settings = [];

    public function __construct()
    {
        $files = array_filter(scandir(CONFIG_PATH), function ($v) {return preg_match('/\\.conf/', $v);});
        foreach ($files as $file) {
            $settings = parse_ini_file(CONFIG_PATH . '/' . $file, true);
            $this->settings = array_merge($this->settings, $settings);
        }
    }

    public function get($section)
    {
        $result = null;
        if (isset($this->settings[$section])) {
            $result = $this->settings[$section];
        }
        return $result;
    }

    public function __get($name)
    {
        return $this->get($name);
    }
}