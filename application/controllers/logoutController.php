<?php

class logoutController extends Zend_Controller_Action
{

	public function init()
	{
		Zend_Auth::getInstance()->clearIdentity();
		Zend_Session::forgetMe();
		Zend_Session::destroy(true);
		$this->redirect('/');
	}
}