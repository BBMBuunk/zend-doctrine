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

    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/blog');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Blog');
        $this->assertControllerName('Blog\Controller\Blog');
        $this->assertControllerClass('BlogController');
        $this->assertMatchedRouteName('paginator');
    }

    public function testSaveBlog()
    {
        $this->dispatch('/blog/add', 'POST',
            array(
                'title' => 'PHPUnit',
                'body' => 'Hoe awesome is een UNIT test?'));
    }



}
