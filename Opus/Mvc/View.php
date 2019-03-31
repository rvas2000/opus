<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 29.03.19
 * Time: 19:37
 */

namespace Opus\Mvc;


use Opus\App;

class View
{
    protected $layout = 'default';

    protected $title = '';

    protected $css = [];

    protected $js = [];

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function registerJs($js)
    {
        $this->js[$js] = $js;
    }

    public function registerCss($css)
    {
        $this->css[$css] = $css;
    }

    public function renderJs()
    {
        return implode('', array_map(function ($v) {return '<script type="text/javascript" src="' . $v . '"></script>';}, $this->js));
    }

    public function renderCss()
    {
        return implode('', array_map(function ($v) {return '<link rel="stylesheet" href="' . $v . '"/>';}, $this->css));
    }

    public function getApp()
    {
        return App::getInstance();
    }

    public function getTemplatePath()
    {
        return realpath(BASE_PATH . '/Site/views/' . $this->getApp()->getRequest()->getController() );
    }

    public function getLayoutPath()
    {
        return realpath(BASE_PATH . '/Site/layouts');
    }

    public function renderPartial($values = [], $template)
    {
        $path = realpath($this->getTemplatePath() . '/' . $template . '.php');
        if ($path === false) {
            throw new \Exception('Не найден шаблон');
        }
        ob_start();
        extract($values);
        include $path;
        return ob_get_clean();
    }


    public function render($values = [], $template = null)
    {
        if ($template === null ) {
            $template = $this->getApp()->getRequest()->getAction();
        }

        $layoutPath = realpath($this->getLayoutPath() . '/' . $this->getLayout() . '.php');
        if ($layoutPath === false) {
            throw new \Exception('Не найден макет');
        }

        $TEMPLATE_PATH = realpath($this->getTemplatePath() . '/' . $template . '.php');
        if ($TEMPLATE_PATH === false) {
            throw new \Exception('Не найден шаблон');
        }

        ob_start();
        extract($values);
        include $layoutPath;
        return ob_get_clean();
    }

}