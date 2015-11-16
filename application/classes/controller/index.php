<?php

class Controller_Index extends Controller_Template {

    public function before() {
		$return = parent::before();
    	$this->add_style('page-index.css');
		$this->add_script('page-index.js','foot');

	}

    public function action_index() {

		Zwidget::zadd('index/index', array(), 'content');
        return;
    }


}

// End Index

