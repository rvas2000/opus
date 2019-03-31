<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 30.03.19
 * Time: 1:29
 */

namespace common\services;


use Opus\Db\DbServiceAbstract;

class DbService extends DbServiceAbstract
{
    public function addNews()
    {
        $data = [
            'slug' => 'abcd',
            'preview' => 'image1.png',
            'header' => 'Тестовая новость',
            'created_at' => (new \DateTime())->format('Ymd H:i:s'),
            'content' => 'Содержание'
        ];

        $this->insertTbl('news', $data);
    }

    public function ModifyNews($slug, $preview, $header, $content)
    {
        $data = [
            'slug' => $slug,
            'preview' => $preview,
            'header' => $header,
            'content' => $content
        ];

        $this->updateTbl('news', $data, ['header LIKE ?' => '%Заголовок%']);
    }

    public function getNews()
    {
        $rs = $this->selectTbl('news', [], ['id' => SORT_DESC]);
        return array_combine(array_map(function ($v) {return $v['id'];}, $rs), $rs);
    }

}