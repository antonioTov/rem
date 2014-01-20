<?php

class logoutController extends Zend_Controller_Action
{

	public function indexAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		$this->redirect('/'); // back to login page
	}
}