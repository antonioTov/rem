<?php

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract
{

    protected $_name = 'users';


	/**
	 * ƒобавление записи при быстрой регистрации
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

}

