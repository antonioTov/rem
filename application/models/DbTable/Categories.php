<?
class Application_Model_DbTable_Categories extends Zend_Db_Table_Abstract
{

	protected $_name = 'categories';

	public function getCategories()
	{
		$select = $this->select()
			->where('active = 1')
			//->order('rate', 'date')
			->limit(15);
		return $this->fetchAll( $select );
	}

}

