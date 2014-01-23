<?php

class Application_Model_DbTable_Projects extends Zend_Db_Table_Abstract
{

    protected $_name = 'projects';


	public function getProjects()
	{
		$select = $this->select()
							//->from($this->_name, array('*', 'minuts' => 'TIMESTAMPDIFF(MINUTE, date, NOW())' ))
							->where('active = 1')
							->order('rate', 'date')
							->limit(15);
		return $this->fetchAll( $select );
	}

}

