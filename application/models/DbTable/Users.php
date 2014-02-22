<?php

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract
{

	/**
	 * table name
	 * @var string
	 */
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
	 * Добавление записи при реристрации через соц. сети
	 * Паралельно создается запись в профилье
	 * @param $data
	 * @return bool
	 */
	public function socRegAdd( $data )
	{
		$profileData = array(
			'first_name' 	=> $data['first_name'],
			'last_name' 	=> $data['last_name'],
			'photo'			=> $data['photo']
		);

		$profileModel = new Application_Model_DbTable_Profiles();
		$pid = $profileModel->insert( $profileData );

		$userData = array(
			'profile_id' 			=> $pid,
			'social_id'			=> $data['social_id'],
			'social_net' 		=> $data['social_net'],
			'access_token'	=> $data['access_token'],
			'email'				=> isset($data['email']) ? $data['email'] : null
		);

		if( $uid = $this->insert( $userData ) ) {
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
		$data = array(
			'pass' => md5( $pass )
		);

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

	public function findBySocId( $socId )
	{
		$row = $this->fetchRow( $this->select()->where('social_id = ?' , $socId ) );
		if( ! $row ) {
			return false;
		} else {
			return $row;
		}
	}



}

