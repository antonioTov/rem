<?php

class Api_Bootstrap extends Zend_Application_Module_Bootstrap
{

	public function _initSecurityRequest()
	{
		Zend_Controller_Front::getInstance()
			->registerPlugin( new Api_Plugin_SecurityRequest() );
	}

}

