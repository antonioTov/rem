<?php
class Application_Form_Profile extends Zend_Form
{

	public function init()
	{

		$emptyMsg 		= '';
		$longMsg 			= '������� ���';
		$badSymbMsg 	= '���� �������� ������������ �������';
		$errorEmail		= '������������ ������';
		$errorBusy			= '����� e-mail ��� �����';

		$this->setMethod('post');

		//-----------------------------------------------------------------//
		// ���� E-mail
		$email = new Zend_Form_Element_Text('email');
		$email
			->setRequired(true)
			->setAttrib('class', 'form-input')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setDecorators(array('ViewHelper', 'Errors'))
			->addValidators( array(
				array('NotEmpty', true,
					array(
						'messages'  => array(
							'isEmpty' => $emptyMsg
						)
					)),
				array('EmailAddress', true,
					array(
						'messages'	 => array(
							'emailAddressInvalidFormat' => $errorEmail,
							'emailAddressInvalidHostname' => $errorEmail
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
			));


		// ------------------------------------------------------------------//
		// First name
		$firstName = new Zend_Form_Element_Text('first_name');
		$firstName
			->setRequired(true)
			->setAttrib('class', 'form-input')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setDecorators( array('ViewHelper', 'Errors') )
			->addValidators( array(
				array('NotEmpty', true, array('messages' => array(
					'isEmpty' => $emptyMsg
				))),
				array('stringLength', true, array(1, 50, 'messages' => array(
					'stringLengthTooLong' => $longMsg,
					//'stringLengthTooShort' => 'short'
				))),
				array('regex', true, array(
					'pattern'   => '/^[a-z�-��]+$/i',
					'messages'  =>  $badSymbMsg
				))
			));

		// ------------------------------------------------------------------//
		// Last name
		$lastName = new Zend_Form_Element_Text('last_name');
		$lastName
			->setRequired(true)
			->setAttrib('class', 'form-input')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setDecorators( array('ViewHelper', 'Errors') )
			->addValidators( array(
				array('NotEmpty', true, array('messages' => array(
					'isEmpty' => $emptyMsg
				)))) );

		// ------------------------------------------------------------------//
		// Patronymic
		$patronymic = new Zend_Form_Element_Text('patronymic');
		$patronymic
			->setRequired(true)
			->setAttrib('class', 'form-input')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setDecorators( array('ViewHelper', 'Errors') )
			->addValidators( array(
				array('NotEmpty', true, array('messages' => array(
					'isEmpty' => $emptyMsg
				)))) );


		// ------------------------------------------------------------------//
		// Date of birth
		$birthDate = new Zend_Form_Element_Text('birth_date');
		$birthDate
			->setRequired(true)
			->setAttrib('class', 'form-input')
			->setAttrib('id', 'datepicker')
			->setAttrib('autocomplete', 'off')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setDecorators( array('ViewHelper', 'Errors') )
			->addValidators( array(
				array('NotEmpty', true, array('messages' => array(
					'isEmpty' => $emptyMsg
				)))) );


		// ------------------------------------------------------------------//
		// Phone
		$phone = new Zend_Form_Element_Text('phone');
		$phone
			->setRequired(true)
			->setAttrib('class', 'form-input')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setDecorators( array('ViewHelper', 'Errors') )
			->addValidators( array(
				array('NotEmpty', true, array('messages' => array(
					'isEmpty' => $emptyMsg
				)))) );



		//-----------------------------------------------------------------//
		// Submit
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('class', 'btn')
			->setLabel('���������')
			->setIgnore(true)
			->setDecorators(array('ViewHelper'));


		// -- hidden -- //

		// select2 City
		$city = new Zend_Form_Element_Hidden( 'city_id' );
		$city->setAttrib('id', 'cities')
			->setDecorators( array('ViewHelper') );

		// select2 Branches
		$branches = new Zend_Form_Element_Hidden( 'branches' );
		$branches->setAttrib('id', 'branches')
			->setDecorators( array('ViewHelper') );

		// -- hidden end -- //

		$this->setDecorators(array(
			array('ViewScript', array('viewScript' => 'forms/ProfileData.phtml'))
		));

		$this->addElements( array($email, $firstName, $lastName, $patronymic,  $birthDate, $phone, $city, $branches, $submit ) );
	}


}