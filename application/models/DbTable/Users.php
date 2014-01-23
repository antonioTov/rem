<?php

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract
{

    protected $_name = 'users';

	/**
	 * Добавление записи при быстрой регистрации
	 * Паралельно создается пустая запись в профиле
	 * @param $email
	 * @param $pass
	 * @return bool
	 */
	public function quickRegAdd( $email, $pass )
	{
		// создаем пустую запись профиля
		$profileModel = new Application_Model_DbTable_Profiles();
		$pid = $profileModel->insert( array() );

		$data = array(
			'email' => $email,
			'pass' => md5( $pass ),
			'profile_id' => $pid
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
		$data = array(
			'last_visit' 	=> date( 'Y-m-d H:i:s', time() ),
			'last_ip'		=> $_SERVER['REMOTE_ADDR']
		);

		$this->update( $data, 'id = ' . (int) $id );
	}


	public function updateEmail( $email )
	{
		$data = array(
			'email' => $email
		);

		$pid = Zend_Auth::getInstance()->getIdentity()->profile_id;
		$uid = Zend_Auth::getInstance()->getIdentity()->id;

		if( $this->update( $data, 'profile_id = ' . $pid . ' AND id = ' . $uid ) ) {
			$storage = Zend_Auth::getInstance()->getStorage();
			$storageData = $storage->read();
			$storageData->email = $email;
			$storage->write( $storageData );
		}
	}



}

