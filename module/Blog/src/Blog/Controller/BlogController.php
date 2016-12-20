<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 18-12-2016
 * Time: 19:20
 */

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Model\Blog;
use Blog\Form\BlogForm;

class BlogController extends AbstractActionController {

    protected $blogTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'blog' => $this->getBlogTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new BlogForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $blog = new Blog();
            $form->setInputFilter($blog->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $blog->exchangeArray($form->getData());
                $this->getBlogTable()->saveBlog($blog);
                // Redirect to list of blogs
                return $this->redirect()->toRoute('blog');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('blog', array(
                'action' => 'add'
            ));
        }

        // Get the Blog with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $blog = $this->getBlogTable()->getBlog($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('blog', array(
                'action' => 'index'
            ));
        }

        $form  = new BlogForm();
        $form->bind($blog);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($blog->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getBlogTable()->saveBlog($blog);

                // Redirect to list of albums
                return $this->redirect()->toRoute('blog');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('blog');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getBlogTable()->deleteBlog($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('blog');
        }

        return array(
            'id'    => $id,
            'blog' => $this->getBlogTable()->getBlog($id)
        );
    }

    public function getBlogTable()
    {
        if (!$this->blogTable) {
            $sm = $this->getServiceLocator();
            $this->blogTable = $sm->get('Blog\Model\BlogTable');
        }
        return $this->blogTable;
    }
} 