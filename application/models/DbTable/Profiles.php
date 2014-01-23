<?php

class Application_Model_DbTable_Profiles extends Zend_Db_Table_Abstract
{

    protected $_name = 'profiles';

	private $_cache;


	public  function init() {
		$frontendOptions = array(
			'lifetime' => 3600,
			'automatic_serialization' => true
		);

		$backendOptions = array('cache_dir' => './tmp/cache');

		$this->_cache = Zend_Cache::factory('Output','File',
			$frontendOptions,
			$backendOptions);
	}



	private function _getFromCache( $pid )
	{
		$cacheId = 'profile' . $pid;

		if( ! $profileData = $this->_cache->load( $cacheId ) ) {

			$profileData = $this->fetchRow('pid = ' . $pid);
			if ( ! $profileData ) {
				return array();
			}
			$this->_cache->save( $profileData, $cacheId );
		}

		return  $profileData->toArray();
	}


	/**
	 * ������� ����
	 * @param $pid
	 */
	private function _clearCache( $pid )
	{
		$cacheId = 'profile' . $pid;
		$this->_cache->remove( $cacheId );
	}


	/**
	 * ���������� ������ �������,
	 * @param array $formData
	 * @return int
	 */
	public function updateProfile( array $formData )
	{
		$data = array(
			'first_name'	=> $formData['first_name'],
			'last_name' 	=> $formData['last_name'],
			'patronymic'  	=> $formData['patronymic'],
			'birth'    		=> $formData['birth'],
			'phone'    		=> $formData['phone'],
			'branches'    	=> $formData['branches'],
			'city_id'    		=> $formData['city_id']
		);

		$pid = Zend_Auth::getInstance()->getIdentity()->profile_id;

		$this->_clearCache( $pid );

		return $this->update( $data, 'pid = ' . $pid );
	}


	/**
	 * ��������� ������ �������
	 * ������ ����������
	 * @param null $pid
	 * @internal param null $id
	 * @return array
	 */
	public function getProfile( $pid = null )
	{
		$pid = $pid ? $pid : Zend_Auth::getInstance()->getIdentity()->profile_id;
		return $this->_getFromCache( (int) $pid );
	}



	/** !!!
	 * ����� ������ �� �����
	 * @param $name
	 * @return bool
	 */
	public function getByName( $name )
	{
		$name = (string) $name;
		$row = $this->fetchRow( $this->select()->where('username = ?' , $name) );
		if (!$row) {
			return false;
		}
		return true;
	}


	/** !!!
	 * ���������� ������
	 * @param $data
	 */
	public function addPlayer( $data )
	{
		$this->insert( $data );
	}


	/** !!!
	 * ���������� ������ ������
	 * @param $id
	 * @param $data
	 */
	public function updatePlayer( $id, $data )
	{
		$this->update( $data, 'id = '. (int) $id );
	}


	/** !!!
	 * �������� ������
	 * @param $id
	 */
	public function deletePlayer( $id )
	{
		$this->delete('id =' . (int) $id );
	}

	/** !!!
	 * @param $ids
	 */
	public function deleteByIds( $ids )
	{
		$this->delete('id IN (' . (string) $ids . ')' );
	}



}

