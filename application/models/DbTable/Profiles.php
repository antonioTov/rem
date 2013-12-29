<?php

class Application_Model_DbTable_Profiles extends Zend_Db_Table_Abstract
{

    protected $_name = 'profiles';


	/**
	 * ��������� ������ �� ID
	 * @param $id
	 * @return array
	 * @throws Exception
	 */
	public function getPlayer( $id )
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
		if (!$row) {
			throw new Exception("Could not find row $id");
		}
		return $row->toArray();
	}


	/**
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


	/**
	 * ���������� ������
	 * @param $data
	 */
	public function addPlayer( $data )
	{
		$this->insert( $data );
	}


	/**
	 * ���������� ������ ������
	 * @param $id
	 * @param $data
	 */
	public function updatePlayer( $id, $data )
	{
		$this->update( $data, 'id = '. (int) $id );
	}


	/**
	 * �������� ������
	 * @param $id
	 */
	public function deletePlayer( $id )
	{
		$this->delete('id =' . (int) $id );
	}


	public function deleteByIds( $ids )
	{
		$this->delete('id IN (' . (string) $ids . ')' );
	}



}

