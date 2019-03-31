<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 29.03.19
 * Time: 18:36
 */

namespace Opus\Http;


class Request
{
    protected $controller = null;

    protected $action = null;

    protected $baseUri = null;

    public function __construct()
    {
        $this->init();
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getBaseUri()
    {
        return $this->baseUri;
    }

    public function getParameter($name, $defaultValue = null)
    {
        if (isset($_GET[$name])) {
            $value = $_GET[$name];
        } elseif (isset($_POST[$name])) {
            $value = $_POST[$name];
        } else {
            $value = $defaultValue;
        }
        return $value;
    }

    protected function init()
    {

        $baseUriElements = array_filter(
            array_map(
                function ($v) {return trim($v);},
                explode('/', $_SERVER['SCRIPT_NAME'])
            ),
            function ($v) {return ! (empty($v) || preg_match('/\\.php/', $v) !== false);}
        );

        $this->baseUri = implode('/', $baseUriElements);
        if ( ! empty($this->baseUri)) {$this->baseUri = '/' . $this->baseUri;}

        $elements = array_diff (

            array_filter(
                array_map(
                    function ($v) {return preg_replace('/\\?.+$/', '', trim($v));},
                    explode('/', $_SERVER['REQUEST_URI'])
                ),
                function ($v) {return ! empty($v);}
            ),

            $baseUriElements
        );

        $this->controller = 'default';
        if (($element = array_shift($elements)) !== null) {$this->controller = $element;}

        $this->action = 'index';
        if (($element = array_shift($elements)) !== null) {$this->action = $element;}

        $name = null;
        $isValue = false;
        foreach ($elements as $element) {
            if (! empty($element)) {
                if ($isValue) {
                    if (! empty($name) ) {
                        $_GET[$name] = $element;
                    }
                } else {
                    $name = $element;
                }

            }
            $isValue = ! $isValue;
        }
    }
}