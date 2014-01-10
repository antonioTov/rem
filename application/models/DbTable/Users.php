<?php

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract
{

    protected $_name = 'users';


	// определение фильтров и валидаторов входных данных
	private $_filters = array(
		'pass'    => array('HtmlEntities', 'StripTags', 'StringTrim'),
		'email' 	=> array('HtmlEntities', 'StripTags', 'StringTrim'),
		'price'    => array('HtmlEntities', 'StripTags', 'StringTrim')
	);

	private $_validators = array(
		'title'     => array(),
		'shortdesc' => array(),
		'price'     => array('Float'),
		'quantity'  => array('Int')
	);

	/**
	 * Добавление записи при быстрой регистрации
	 * @param $email
	 * @param $pass
	 * @return bool
	 */
	public function quickRegAdd( $email, $pass )
	{
		$data = array(
			'email' => $email,
			'pass' => md5( $pass )
		);
		if( $this->insert( $data ) ) {
			return true;
		}

		return false;

	}


	/**
	 * Изменение пароля пользователя
	 * @param $id
	 * @param $pass
	 * @return bool
	 */
	public function changePassword( $id, $pass )
	{
		$data = array();
		$data['pass'] = md5( $pass );

		if( $this->update( $data, 'id = ' . (int) $id ) ) {
			return true;
		}

		return false;
	}


	public function lastVisit( $id )
	{
		$currDate =  date( 'Y-m-d H:i:s', time() );
		echo $this->update( array('last_visit' =>$currDate ), 'id = ' . (int) $id );
	}



	private function _dataValidation( $data )
	{
		$input = new Zend_Filter_Input($this->_filters,
			$this->_validators, $data);
		if ( ! $input->isValid() ) {
			throw new Zend_Exception('Invalid input');
		}

		return $input->getEscaped();
	}


}

