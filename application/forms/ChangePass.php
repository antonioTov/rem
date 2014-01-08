<?php
class Application_Form_ChangePass extends Zend_Form
{

	public function init()
	{

		$emptyMsg 	=  '���� �� ����� ���� ������!';

		$this->setMethod('post');

		$pass = new Zend_Form_Element_Password('pass');
		$pass->setLabel('����� ������')
			->setRequired(true)
			->setAttrib('class', 'input_auth')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidators(array(
				array('NotEmpty', true, array('messages' => array(
					'isEmpty' => $emptyMsg,
				)))))
			->setDecorators(array(
				'ViewHelper',
				'Errors',
				array(array('td' => 'HtmlTag'), array('tag' => 'td')),
				array('Label'),
			));


		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('class', 'new')
			->setLabel('��������')
			->setDecorators(array(
				'ViewHelper',
				'Errors',
				array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' =>'button')),
			));

		$this->addElements( array( $pass, $submit ) );
	}


}