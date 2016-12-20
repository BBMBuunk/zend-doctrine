<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 18-12-2016
 * Time: 19:20
 */

namespace Blog\Controller;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Entity\Blog;
use Blog\Form\BlogForm;


class BlogController extends AbstractActionController
{
    protected $em;

    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

    public function indexAction()
    {
        return new ViewModel(array(
            'blog' => $this->getEntityManager()->getRepository('Blog\Entity\Blog')->findAll(),
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
                $this->getEntityManager()->persist($blog);
                $this->getEntityManager()->flush();

                // Redirect to list blogs
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

        $blog = $this->getEntityManager()->find('Blog\Entity\Blog', $id);
        if (!$blog) {
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
                $this->getEntityManager()->flush();

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
                $blog = $this->getEntityManager()->find('Blog\Entity\Blog', $id);
                if ($blog) {
                    $this->getEntityManager()->remove($blog);
                    $this->getEntityManager()->flush();
                }
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('blog');
        }

        return array(
            'id'    => $id,
            'blog' => $this->getEntityManager()->find('Blog\Entity\Blog', $id)
        );
    }
} 