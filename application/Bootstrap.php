<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	/**
	 * Инициализация кеша метадаты таблиц
	 */
	public function _initMetaDataCache()
	{
		$frontendOptions = array (
			'automatic_serialization' => true,
			'lifetime' => null,
		);
		$backendOptions = array ('cache_dir' =>  './tmp/db-tables-metadata');
		$cache = Zend_Cache::factory(
			'Core',
			'File',
			$frontendOptions,
			$backendOptions
		);

		Zend_Db_Table::setDefaultMetadataCache( $cache );
	}


	/**
	 * Инициализация роутера для профиля пользователя
	 */

	public function _initRoutes()
	{
		$routeDefault = new Zend_Controller_Router_Route(
			'/:controller/:action/:id',
			array(
				'controller' => 'task',
				'action'		=> 'list',
				'id'			=> 0,
			)
		);

		$routeTaskList = new Zend_Controller_Router_Route(
			'/task/:page',
			array(
				'controller' => 'task',
				'action'		=> 'list',
				'page'		=> 1
			)
		);

		$routeProfile = new Zend_Controller_Router_Route(
			'profile',
			array(
				'controller' 	=> 'profile',
				'action'     	=> 'view',
				'id'         	=> 0,
				'news'		=> 'Article_1'
			)
			//array('id' => '\d+') // Makes sure :id is an int
		);

		$router = Zend_Controller_Front::getInstance()->getRouter();
		$router->addRoute('route', $routeDefault);
		$router->addRoute('task_list', $routeTaskList);
		$router->addRoute('profile', $routeProfile);
	}

	 /**
	 * Инициализация формы авторизации
	 */
	public function _initLogin()
	{

		if ( ! Zend_Auth::getInstance()->getIdentity() ) {

			$this->bootstrap('layout');
			$layout = $this->getResource('layout');
			$view = $layout->getView();

			$view->loginForm = new Application_Form_Login();
			$view->registrationForm =  new Application_Form_QuickReg();

			return $view;
		}

	}


	/**
	 * Инициализация плагина прав доступа
	 */
/*	public function _initAcl()
	{
		$this->_register( new Application_Plugin_AccessCheck() );
	}
*/

	/**
	 * Регистрация плагинов
	 *
	 * @param Zend_Controller_Plugin_Abstract $plugin
	 */
	private function _register( Zend_Controller_Plugin_Abstract $plugin )
	{
		Zend_Controller_Front::getInstance()
			->registerPlugin( $plugin );
	}



}

