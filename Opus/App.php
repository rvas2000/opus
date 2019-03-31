<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 29.03.19
 * Time: 18:33
 */

namespace Opus;


use Opus\Common\Config;
use Opus\Http\Request;
use Opus\Http\Response;
use Opus\Mvc\View;

class App
{
    private static $instance = null;

    private $request = null;

    private $response = null;

    private $view = null;

    private $services = [];

    private $config = null;

    private $pdo = null;

    private function __construct(){}

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getRequest()
    {
        if ($this->request === null) {
            $this->request = new Request();
        }
        return $this->request;
    }

    public function getResponse()
    {
        if ($this->response === null) {
            $this->response = new Response();
        }
        return $this->response;
    }


    public function getView()
    {
        if ($this->view === null) {
            $this->view = new View();
        }
        return $this->view;
    }

    public function getService($name)
    {
        if (! isset($this->services[$name])) {
            $className = '\\common\\services\\' . $this->getCanonicalName($name) . 'Service';
            $this->services[$name] = new $className();
        }
        return $this->services[$name];
    }

    public function getConfig()
    {
        if ($this->config === null) {
            $this->config = new Config();
        }
        return $this->config;
    }

    public function getPdo()
    {
        if ($this->pdo === null) {
            $config = $this->getConfig()->db;

            $dsn = "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
            $options = [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ];
            $this->pdo = new \PDO($dsn, $config['user'], $config['password'], $options);
            if ($config['driver'] === 'mysql') {
                $this->pdo->exec("SET NAMES UTF8");
            }

        }
        return $this->pdo;
    }

    public function getCanonicalName($name)
    {
        return implode('', array_map(function ($v) {return ucfirst(strtolower(trim($v)));}, explode('-', $name)));
    }

    public function run()
    {
        ob_start();
        $controllerName = '\\controllers\\' . $this->getCanonicalName($this->getRequest()->getController()) . 'Controller';
        $actionName = 'action' . $this->getCanonicalName($this->getRequest()->getAction());

        $controller = new $controllerName();
        $controller->init();
        $this->getResponse()->content = $controller->{$actionName}();

        $this->getResponse()->flush();
        ob_end_flush();
    }
}
