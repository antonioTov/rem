<?php
class Application_Plugin_AuthCheck extends Zend_Controller_Plugin_Abstract
{

	public function preDispatch( Zend_Controller_Request_Abstract $request )
	{
			$request
				->setModuleName('default')
				->setControllerName('auth')
				->setActionName('login');
	}

}
