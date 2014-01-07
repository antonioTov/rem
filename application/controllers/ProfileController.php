<?php

class ProfileController extends Zend_Controller_Action
{


    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		$pid = $this->_getParam('id');

		if( ! $pid ) {
			$this->_redirect('/profile/' . Zend_Auth::getInstance()->getIdentity()->id );
		}

		print_r($this->_getAllParams());
    }


}

