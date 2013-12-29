<?php

class RegistrationController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
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
				$pass = Rem_PassGenerator::generate();

				$mess =	"Ваши данные для авторизации \r\n>" .
								"Логин: " . $email . "\r\n>" .
								"Пароль: " . $pass . "\r\n" .
								"<a href='" . $this->view->serverUrl() . "/registration/validation/code/" . $this->_generateCode( $email ) ."'>Подвердить адрес электронной почты</a>";

				//отправка сообщения
				$mail = new Rem_Mail('windows-1251');
				$mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
				$mail->addTo( $email, 'Test');
				$mail->setFrom('studio@peptolab.com', 'Test');
				$mail->setSubject(
					'Пароль для входа'
				);
				$mail->setBodyHtml( $mess );
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
		$code = $this->_getParam('code', 0);
	}

	private function _generateCode( $email )
	{
		return md5( $email );
	}


}

