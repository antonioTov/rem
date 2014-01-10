<?php

class Application_Model_DbTable_Cities extends Zend_Db_Table_Abstract
{

    protected $_name = 'cities';

	public function searchByName( $cityName )
	{
		$select = $this->select()->where('name LIKE ?', (string) "%" . $cityName . "%" )->limit(5);
		return $this->fetchAll( $select )->toArray();
	}
}

