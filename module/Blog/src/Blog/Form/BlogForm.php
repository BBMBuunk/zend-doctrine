<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 19-12-2016
 * Time: 19:56
 */

namespace Blog\Form;

use Zend\Form\Form;

class BlogForm extends Form {

    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('blog');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'options' => array(
                'label' => 'Title',
            ),
            'attributes' => array(
                'class' => 'mdl-textfield__input',
            ),
        ));
        $this->add(array(
            'name' => 'body',
            'type' => 'TextArea',
            'options' => array(
                'label' => 'Body',
            ),
            'attributes' => array(
                'class' => 'mdl-textfield__input',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Add Blog',
                'id' => 'submitbutton',
            ),
        ));
    }
} 