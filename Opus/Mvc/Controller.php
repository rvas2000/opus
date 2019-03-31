<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 29.03.19
 * Time: 19:33
 */

namespace Opus\Mvc;

use Opus\App;

class Controller
{
    public function getApp()
    {
        return App::getInstance();
    }

    public function getRequest()
    {
        return $this->getApp()->getRequest();
    }

    public function getResponse()
    {
        return $this->getApp()->getResponse();
    }

    public function getView()
    {
        return $this->getApp()->getView();
    }

    public function renderPartial($values = [], $template)
    {
        return $this->getView()->renderPartial($values, $template);
    }

    public function render($values = [], $template = null)
    {
        return $this->getView()->render($values, $template);
    }

    public function init()
    {
//        $this->getView()->registerJs('/js/require.js');
        $this->getView()->registerJs('/js/jquery-3.3.1.min.js');
        $this->getView()->registerJs('/js/main.js');

        $this->getView()->registerCss('/css/main.css');
    }
}