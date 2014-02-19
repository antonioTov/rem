<?php

class indexController extends Zend_Controller_Action
{


	/**
	 *
	 */
	function indexAction()
    {
		$projectsModel 	= new Application_Model_DbTable_Projects();
		$citiesModel 		= new Application_Model_DbTable_Cities();

		$paginator = new Zend_Paginator( new Zend_Paginator_Adapter_DbTableSelect($projectsModel->select()) );

		$paginator->setCurrentPageNumber( $this->_getParam('page'));
		$paginator->setItemCountPerPage(5);

		$ids = array();
		foreach( $paginator as $project) {
			$ids[] = $project->city_id;
		}
		$cities = $citiesModel->getByIds( $ids );

		$this->view->cities 		= $cities;
		$this->view->projects 	= $paginator;

    }



}

