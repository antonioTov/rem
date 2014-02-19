<?php

class RegistrationController extends Zend_Controller_Action
{

	private $isAjax;

	public function init()
	{
		$this->isAjax = $this->_request->isXmlHttpRequest();
		if ($this->isAjax)
			// если это аякс запрос то отключаем layout
		$this->_helper->layout()->disableLayout();
	}

	/**
	 * Быстрая регистрация
	 */
	public function indexAction()
	{
		if ($this->isAjax) {
			$this->_helper->ViewRenderer->setNoRender();
		}

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
				$mail->setFrom('info@realmaster.com.ua', 'realmaster.com.ua');
				$mail->setSubject('Пароль для входа'	);
				$mail->setBodyHtml( $body );
				$mail->send();

				$usersModel = new Application_Model_DbTable_Users();

				if ( $usersModel->quickRegAdd( $email, $pass ) ) {

					$form = new Application_Form_Login();
					if ($this->isAjax) {
						return $this->_helper->json(array( 'status' => 'ok'	));
					}


				} else {
					if ($this->isAjax) {
						return $this->_helper->json(array('status' => 'fail', 'message' => 'error' ));
					}
				}

			} else {

				$formMessages = $form->getMessages();
				if ($this->isAjax) {
					return $this->_helper->json(array('status' => 'fail', 'message' => iconv('cp1251', 'utf-8', current($formMessages['email']) ) ));
				}

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

