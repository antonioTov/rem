<?php
class App_Exception extends Zend_Exception
{
	public function __construct( $code )
	{
		$layout = Zend_Layout::getMvcInstance();
		$layout->setInflectorTarget( $code . '.phtml' );
	}


}