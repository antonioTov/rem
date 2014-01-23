<?php

class RegistrationController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {

    }



	/**
	 * Быстрая регистрация
	 */
	public function quickAction()
	{
		$form = new Application_Form_QuickReg();

		if ( $this->getRequest()->isPost() )
		{
			$formData = $this->getRequest()->getPost();
			if ( $form->isValid( $formData ) ) {

				$email = $formData['email'];

				// генерируем временный пароль
				$pass = App_PassGenerator::generate();

				$this->view->email			= $email;
				$this->view->pass 			= $pass;
				$this->view->code			= $this->_generateCode( $email );
				$this->view->serverUrl 	= $this->view->serverUrl();

				$body = $this->view->render('registration/mail.phtml');

				//отправка сообщения
				$mail = new Zend_Mail('windows-1251');
				$mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
				$mail->addTo( $email, 'Test');
				$mail->setFrom('studio@peptolab.com', 'Test');
				$mail->setSubject('Пароль для входа'	);
				$mail->setBodyHtml( $body );
				$mail->send();

				$usersModel = new Application_Model_DbTable_Users();

				if ( $usersModel->quickRegAdd( $email, $pass ) )
				{
					echo $pass;
					$form = $form->toLogin();
				}
				else {
					echo 'error';
				}

			}
			else {
				$form->populate( $formData );
			}

		}

		$this->view->quickRegForm = $form;


	}



	public function validationAction()
	{
		echo $code = $this->_getParam('code', 0);

	}



	public function mailAction()
	{

	}



	private function _generateCode( $email )
	{
		return md5( $email );
	}



}

