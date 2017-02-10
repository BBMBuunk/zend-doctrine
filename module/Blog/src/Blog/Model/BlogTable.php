<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 18-12-2016
 * Time: 20:43
 */

namespace Blog\Model;

use Zend\Db\TableGateway\TableGateway;

class BlogTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getBlog($id)
    {
        $id  = (int) $id;

        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveBlog($blog)
    {
        $data = array(
            'title' => $blog->title,
            'body'  => $blog->body,
        );

        $id = (int) $blog->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getBlog($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Blog id does not exist');
            }
        }
    }

    public function deleteBlog($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
} 