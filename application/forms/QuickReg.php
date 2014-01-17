<?php

class Application_Form_QuickReg extends Zend_Form
{

	public function init()
	{
		$errorEmpty	=  '���� ������ ���� ���������';
		$errorEmail	=  '������� ���������� E-mail';
		$badCaptcha	=  '�������� ��� ������ �� ���������';
		$errorBusy		=  '����� E-mail ��� ���������������';

		$this->setMethod('post');

		//-----------------------------------------------------------------//
		// ���� E-mail
		$email = new Zend_Form_Element_Text('email');
		$email
			->setLabel('E-mail')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setOrder(1)
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
				)),
				array('Db_NoRecordExists', true,
					array(
						'table' 	=> 'users',
						'field' 	=> 'email',
						'messages' => array(
							'recordFound' => $errorBusy
						)
					))
			))
			->setDecorators(array('ViewHelper'));
//			->setDecorators(array(
//				'ViewHelper',
//				'Errors',
//				array(array('td' => 'HtmlTag'), array('tag' => 'td')),
//				array('Label'),
//			));


		//-----------------------------------------------------------------//
		// ������
		$captcha = new Zend_Form_Element_Captcha('captchaField',
			array('label' 	=> "�������� ���:",
				'required'	=> true,
				'validator' 	=> 'NotEmpty',
				'captcha' => array(
					'captcha' => 'image',
					'font'=> './design/fonts/cour.ttf',
					'imgDir'=>'static/captcha',
					'imgUrl'=> 'static/captcha',
					'wordLen' => 5,
					'fsize'=>20,
					'height'=>40,
					'width'=>150,
					'gcFreq'=>50,
					'timeout' => 300,
					'dotNoiseLevel' => 50,
					'lineNoiseLevel' => 2,
					'messages' => array(
						'badCaptcha' => $badCaptcha
					)
				)
			));

		$captcha->removeDecorator("ViewHelper");


		//-----------------------------------------------------------------//
		// Submit
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('class', 'new')
			->setLabel('�����������')
			->setOrder(3)
			->setDecorators(array('ViewHelper'));


		$this->addElements( array( $email, $submit ) );

	}


	/**
	 * ������������� ����� ������� ����������� � ����� �����������
	 * @return $this
	 */
	public function toLogin()
	{
		$pass = new Zend_Form_Element_Password('pass');
		$pass->setLabel('������')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setOrder(2)
			->addValidators(array(
				array('NotEmpty', true, array('messages' => array(
					'isEmpty' => '������ �� ����� ���� ������!',
				)))))
			->setDecorators(array('ViewHelper'));

		$this
			->addElement( $pass, null, array('order' => 10))
			->setAction('/auth/login')
			 ->setDecorators(array(
				'FormElements',
				array('HtmlTag', array('tag' => 'p')),
				'Form'
			 )
		 );

		$this->getElement('email')->removeValidator('Db_NoRecordExists');

		return $this;

	}

}