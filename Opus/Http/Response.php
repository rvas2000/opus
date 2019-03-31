<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 29.03.19
 * Time: 21:41
 */

namespace Opus\Http;


class Response
{
    const RESPONSE_TYPE_TEXT = 'text';
    const RESPONSE_TYPE_XML = 'xml';
    const RESPONSE_TYPE_JSON = 'json';
    const RESPONSE_TYPE_HTML = 'html';
    const RESPONSE_TYPE_EMPTY = 'empty';


    public $content = '';

    public $type = self::RESPONSE_TYPE_HTML;

    protected $headers = [];

    protected $contentTypes = [
        self::RESPONSE_TYPE_TEXT => 'text/plain; charset=UTF-8',
        self::RESPONSE_TYPE_XML => 'application/xml',
        self::RESPONSE_TYPE_JSON => 'application/json; charset=UTF-8',
        self::RESPONSE_TYPE_HTML => 'text/html; charset=UTF-8',
    ];

    public function setHeader($name, $content)
    {
        $this->headers[$name] = $content;
    }

    public function flush()
    {
        if ($this->type !== self::RESPONSE_TYPE_EMPTY) {
            $this->setHeader('Content-Type', $this->contentTypes[$this->type]);
        }
        $this->sendHeaders();

        if ($this->type == self::RESPONSE_TYPE_JSON) {
            echo json_encode($this->content);
        } elseif ($this->type == self::RESPONSE_TYPE_XML) {

        } else {
            echo $this->content;
        }
    }

    protected function sendHeaders()
    {
        foreach ($this->headers as $name => $content) {
            header($name . ': ' . $content, true);
        }
    }

}