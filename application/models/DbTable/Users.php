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


	/**
	 * »зменение парол€ пользовател€
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

}

