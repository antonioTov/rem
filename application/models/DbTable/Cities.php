<?php

class Application_Model_DbTable_Cities extends Zend_Db_Table_Abstract
{

    protected $_name = 'cities';

	public function searchByName( $cityName )
	{
		$select = $this->select()->where('name LIKE ?', (string) "%" . $cityName . "%" )->limit(5);
		return $this->fetchAll( $select )->toArray();
	}

	/**
	 * @param $id
	 * @return null|Zend_Db_Table_Row_Abstract
	 */
	public function getById( $id )
	{
		//$rows = $table->find(1234);
		$select = $this->select()->where('id = ?', (int) $id);
		return $this->fetchRow( $select );
	}


	public function getByIds( array $ids )
	{
		//$rows = $table->find(array(1234, 5678));
		$select = $this->select()->where('id IN (?)', $ids );
		$result = $this->fetchAll( $select )->toArray();
		$cities = array();
		if( $result ) {
			foreach( $result as $city ) {
				$cities[$city['id']] = $city;
			}
			return $cities;
		}
		return false;

	}

}

