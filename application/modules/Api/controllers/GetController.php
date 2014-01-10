<?php

class Api_GetController extends Zend_Controller_Action
{

	public function init()
	{
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext
			->addActionContext('cities', 'json')
			->initContext();
	}

	public function indexAction()
	{
		$this->citiesAction();
	}


	public function citiesAction()
	{
		if($q = $this->getRequest()->getQuery() )
		{
			$cityName 		= iconv( 'utf-8', 'cp1251', $q['q'] );
			$citiesModel	= new Application_Model_DbTable_Cities();
			$cities 			= $citiesModel->searchByName( $cityName );

			foreach( $cities as &$city ) {
					$city['name'] = iconv('cp1251', 'utf-8', $city['name'] . ' (' . $city['region_id'] . ')' );
			}

			$this->_helper->json( array(
				'cities' => $cities
			) );
		}

	}


}

