<?php

namespace BlogTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class BlogControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(
            include '/xampp/htdocs/zend-doctrine/config/application.config.php'
        );
        parent::setUp();
    }

    public function testIndexAction()
    {
        $this->dispatch('/blog');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Blog');
        $this->assertControllerName('Blog\Controller\Blog');
        $this->assertControllerClass('BlogController');
        $this->assertMatchedRouteName('paginator');
    }

    public function testAddAction()
    {
        $this->dispatch('/blog/add', 'POST',
            array(
                'title' => 'PHPUnit',
                'body' => 'Hoe awesome is een UNIT test?'));
    }

    public function testEditAction()
    {
        $this->dispatch('/blog/edit', 'POST',
            array(
                'id' => '',
                'body' => 'Hoe awesome is een UNIT test?'));
    }

    public function testDeleteAction()
    {
        $this->dispatch('/blog/delete', 'POST',
            array(
                'id' => '',
                'body' => 'Hoe awesome is een UNIT test?'));
    }



}
