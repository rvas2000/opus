<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 29.03.19
 * Time: 19:18
 */

namespace controllers;

use Opus\Http\Response;
use Opus\Mvc\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $this->getView()->setTitle('Василий Рубцов -персональный сайт');

        $rs = $this->getApp()->getService('db')->selectTbl('users');
        return $this->render(['rs' => $rs]);
    }


}