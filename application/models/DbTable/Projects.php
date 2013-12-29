<?php

class Application_Model_DbTable_Projects extends Zend_Db_Table_Abstract
{

    protected $_name = 'projects';


	public function getByName( $name )
	{
		return $this->fetchRow( $this->select()->where('login = ?', (string) $name ) );
	}

}

