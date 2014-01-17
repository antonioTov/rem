<?php

class AuthController extends Zend_Controller_Action
{


	public function loginAction()
	{
		if ( Zend_Auth::getInstance()->hasIdentity() ) {
			$this->redirect('/');
		}

		$form = new Application_Form_QuickReg();
		$form = $form->toLogin();

		$form->getElement('submit')->setLabel('Войти');

		$this->view->form = $form;

		if ( $this->getRequest()->isPost() )
		{
			$formData = $this->getRequest()->getPost();
			if ( $form->isValid( $formData ) )
			{
				$bootstrap 	= $this->getInvokeArg('bootstrap');
				$auth 			= Zend_Auth::getInstance();
				$adapter 		= $bootstrap->getPluginResource('db')->getDbAdapter();
				$usersModel	= new Application_Model_DbTable_Users();
				$tableUsers 	= $usersModel->info('name');
				$authAdapter	= new Zend_Auth_Adapter_DbTable(
					$adapter, $tableUsers, 'email',
					'pass', 'MD5(?)' // AND active = 1'
				);

				$authAdapter->setIdentity( $form->email->getValue() );
				$authAdapter->setCredential( $form->pass->getValue() );
				$result = $auth->authenticate( $authAdapter );

				if ( $result->isValid() ) {
					$storage = $auth->getStorage();
					$storage_data = $authAdapter->getResultRowObject(
						null,
						array('activate', 'password', 'enabled'));
					$storage_data->status = 'admin';
					$storage->write($storage_data);

					$usersModel->lastVisit( $storage_data->id );

					$this->redirect('/');
				}
				else {
					$this->_helper->FlashMessenger('Неправильный логин или пароль!');
				}

			}
			else {
				$this->_helper->FlashMessenger('Неправильный логин или пароль!');
			}

		}

		$this->view->message = $this->getHelper('FlashMessenger')->getCurrentMessages();
	}


	public function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		$this->_helper->redirector('login'); // back to login page
	}
}