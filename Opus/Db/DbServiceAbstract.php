<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 30.03.19
 * Time: 0:59
 */

namespace Opus\Db;


use Opus\App;

class DbServiceAbstract
{
    protected $leftQuote = null;

    protected $rightQuote = null;

    public function getLeftQuote()
    {
        if ($this->leftQuote === null) {
            $this->setQuotes();
        }
        return $this->leftQuote;
    }

    public function getRightQuote()
    {
        if ($this->rightQuote === null) {
            $this->setQuotes();
        }
        return $this->rightQuote;
    }

    public function getApp()
    {
        return App::getInstance();
    }

    public function getPdo()
    {
        return $this->getApp()->getPdo();
    }

    public function insertTbl($table, $data)
    {
        $fields = [];
        $values = [];
        $parameters = [];

        $i = 1;
        foreach ($data as $field => $value) {
            $fields[] = $this->getLeftQuote() . $field . $this->getRightQuote();
            $values[] = '?';
            $parameters[$i++] = $value;
        }

        $sql = "INSERT INTO "
            . $this->getLeftQuote() . $table . $this->getRightQuote()
            . " (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $values) . ")";

        $stmt = $this->getPdo()->prepare($sql);

        $i = 1;
        foreach ($parameters as &$parameter) {
            $stmt->bindParam($i, $parameter);
            $i++;
        }

        $stmt->execute();

    }

    public function updateTbl($table, $data, $conditions)
    {
        $fields = [];
        $parameters = [];
        $conditionElements = [];

        $i = 1;
        foreach ($data as $field => $value) {
            $fields[] = $this->getLeftQuote() . $field . $this->getRightQuote() . ' = ?';
            $parameters[$i++] = $value;
        }

        foreach ($conditions as $condition => $value) {
            if (strpos($condition, '?') === false) {
                $condition = $this->getLeftQuote() . $condition . $this->getRightQuote() . ' = ?';
            }
            $conditionElements[] = '(' . $condition . ')';
            $parameters[$i++] = $value;
        }

        $whereString = '';
        if (count($conditionElements)) {
            $whereString = " WHERE (" . implode(' AND ', $conditionElements) . ")";
        }

        $sql = "UPDATE "
            . $this->getLeftQuote() . $table . $this->getRightQuote()
            . " SET " . implode(', ', $fields) . $whereString;

        $stmt = $this->getPdo()->prepare($sql);

        $i = 1;
        foreach ($parameters as &$parameter) {
            $stmt->bindParam($i, $parameter);
            $i++;
        }

        $stmt->execute();

    }

    public function selectTbl($table, $conditions = [], $order = [])
    {
        $parameters = [];
        $conditionElements = [];

        $i = 1;
        foreach ($conditions as $condition => $value) {
            if (strpos($condition, '?') === false) {
                $condition = $this->getLeftQuote() . $condition . $this->getRightQuote() . ' = ?';
            }
            $conditionElements[] = '(' . $condition . ')';
            $parameters[$i++] = $value;
        }

        $whereString = '';
        if (count($conditionElements)) {
            $whereString = " WHERE (" . implode(' AND ', $conditionElements) . ")";
        }

        $orderByString = '';
        if (count($order)) {
            $orderByString = " ORDER BY " . implode (', ', array_map(function ($v1, $v2) {return $this->getLeftQuote() . $v1 . $this->getRightQuote() . ' ' . ($v2 == SORT_DESC ? 'DESC' : 'ASC');}, array_keys($order), $order));
        }

        $sql = "SELECT * FROM "
            . $this->getLeftQuote() . $table . $this->getRightQuote()
            . $whereString . $orderByString;

        $stmt = $this->getPdo()->prepare($sql);

        $i = 1;
        foreach ($parameters as &$parameter) {
            $stmt->bindParam($i, $parameter);
            $i++;
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    protected function setQuotes()
    {
        $driver = $this->getApp()->getConfig()->db['driver'];
        switch ($driver) {
            case 'pgsql':
                $this->leftQuote = '"';
                $this->rightQuote = '"';
                break;
            case 'mysql':
                $this->leftQuote = '`';
                $this->rightQuote = '`';
                break;
            default:
                $this->leftQuote = '';
                $this->rightQuote = '';
        }
    }
}