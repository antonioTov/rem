<?php

class Application_Form_Login extends Zend_Form
{

   public function init()
    {
		$errorEmpty	= null;
		$errorEmail	= null;

		$this
			->setMethod('post')
			->setAction('/auth/login')
			->setDecorators(array(
				array('ViewScript', array('viewScript' => 'forms/login.phtml'))
			));

		//-----------------------------------------------------------------//
		// поле E-mail
		$email = new Zend_Form_Element_Text('email');
		$email
			->setLabel('E-mail')
			->setRequired(true)
			->setAttrib('class', 'input-ui')
			->setAttrib('id', 'auth-email')
			->setAttrib('placeholder', 'введите e-mail')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidators( array(
				array('NotEmpty', true,
					array(
						'messages'  => array(
							'isEmpty' => $errorEmpty
						)
					)),
				array('EmailAddress', true,
					array(
						'messages'	 => array(
							'emailAddressInvalidFormat' => $errorEmail
						)
					))
			))
			->setDecorators(array('ViewHelper'));
//			->setDecorators(array(
//				'ViewHelper',
//				array('HtmlTag', array('tag' => 'div', 'class' => 'f-row'))
//			));


	   //-----------------------------------------------------------------//
	   	// поле password
	   $pass = new Zend_Form_Element_Password('pass');
	   $pass->setLabel('Пароль')
		   ->setRequired(true)
		   ->setAttrib('class', 'input-ui')
		   ->setAttrib('id', 'auth-pass')
		   ->setAttrib('placeholder', 'введите пароль')
		   ->addFilter('StripTags')
		   ->addFilter('StringTrim')
		   ->addValidators(array(
			   array('NotEmpty', true, array('messages' => array(
				   'isEmpty' => 'Пароль не может быть пустым!',
			   )))))
		   ->setDecorators(array('ViewHelper'));
//			->setDecorators(array(
//				'ViewHelper',
//				array('HtmlTag', array('tag' => 'div', 'class' => 'f-row'))
//			));


		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('class', '')
			->setLabel('Войти')
			->setDecorators(array(
				'ViewHelper',
				'Errors',
				array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' =>'button')),
			));

		$this->addElements( array( $email, $pass,  $submit ) );

    }



}

