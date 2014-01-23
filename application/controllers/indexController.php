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

		$projects = $projectsModel->getProjects();

		$ids = array();
		foreach( $projects as $project) {
			$ids[] = $project->city_id;
		}
		$cities = $citiesModel->getByIds( $ids );

		$this->view->cities 		= $cities;
		$this->view->projects 	= $projectsModel->getProjects();
		print_r(Zend_Auth::getInstance()->getStorage()->read());
    }



}

