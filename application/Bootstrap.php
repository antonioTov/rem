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
	public function _initRouters()
	{
		$route = new Zend_Controller_Router_Route(
			'profile/:id/:action',
			array(
				'controller' => 'profile',
				'action'     	=> 'index',
				'id'         	=> 0
			)
			//array('id' => '\d+') // Makes sure :id is an int
		);

		$router = Zend_Controller_Front::getInstance()->getRouter();
		$router->addRoute('profile', $route);

	}

	 /**
	 * Инициализация плагина авторизации
	 */
/*	public function _initAuth()
	{

		if ( ! Zend_Auth::getInstance()->getIdentity() ) {
			$this->_register( new Application_Plugin_AuthCheck() );
		}

	}
*/

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
/*	private function _register( Zend_Controller_Plugin_Abstract $plugin )
	{
		Zend_Controller_Front::getInstance()
			->registerPlugin( $plugin );
	}
*/


}

