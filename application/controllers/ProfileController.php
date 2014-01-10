<?php

class ProfileController extends Zend_Controller_Action
{

	/**
	 * user ID
	 * @var
	 */
	private $_uid;


    public function init()
    {
		$this->_uid = Zend_Auth::getInstance()->getIdentity()->id;
       	$this->view->uid = $this->_uid;

    }



    public function indexAction()
    {
		$pid = $this->_getParam('id');

		if( ! $pid ) {
			$this->_redirect('/profile/' . $this->_uid );
		}

		//print_r($this->_getAllParams());
    }


	/**
	 * Изменение пароля
	 */
	public function passwordAction()
	{
		$form = new Application_Form_ChangePass();

		$this->view->form = $form;

		if ( $this->getRequest()->isPost() )
		{
			$formData = $this->getRequest()->getPost();
			if ( $form->isValid( $formData ) )
			{
				$pass 	= $form->pass->getValue();

				$userModel = new Application_Model_DbTable_Users();
				$userModel->changePassword( $this->_uid, $pass );

				/* изменение пароля в сессии
				$storage_data = Zend_Auth::getInstance()->getStorage()->read();
				$storage_data->pass = md5( $pass );

				$storage = Zend_Auth::getInstance()->getStorage();
				$storage->write($storage_data);
				*/

				$this->redirect('/profile/' . $this->_uid );
			 }

		}

	}


	public function infoAction()
	{
		$regions = new Application_Model_DbTable_Cities();

		$this->view->regions = $regions->fetchAll(null,null, 20);
	}


}

