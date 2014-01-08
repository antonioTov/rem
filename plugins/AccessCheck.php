<?php
/**
 * Class Application_Plugin_AccessCheck
 *
 * ������� ��� ���������� ���� ������� ����� � ��������
 */
class Application_Plugin_AccessCheck extends Zend_Controller_Plugin_Abstract
{

	private $_acl;

	public function __construct()
	{
		// ������ ������ Zend_Acl
		$this->_acl = new Zend_Acl();

		// ��������� ������� ������ �����,
		// ������� ������� ��������� ����������� � ��������

		// ���������, ��� � ��� ���� ������ index
		$this->_acl->addResource('index');

		// ������ add �������� �������� ������� index
		$this->_acl->addResource('add', 'index');

		// ������ edit �������� �������� ������� index
		$this->_acl->addResource('edit', 'index');

		// ������ delete �������� �������� ������� index
		$this->_acl->addResource('delete', 'index');

		// ���������, ��� � ��� ���� ������ error
		$this->_acl->addResource('error');

		// ���������, ��� � ��� ���� ������ auth
		$this->_acl->addResource('auth');

		// ������ login �������� �������� ������� auth
		$this->_acl->addResource('login', 'auth');

		// ������ logout �������� �������� ������� auth
		$this->_acl->addResource('logout', 'auth');

		$this->_acl->addResource('players');

		// ����� ��������� � �������� �����, ������� � ��� 2:
		// ����� (������������������ ������������)
		$this->_acl->addRole('guest');

		// �������������, ������� ��������� ������ �� �����
		$this->_acl->addRole('admin', 'guest');

		// ��������� ����� ������������� ������ index
		$this->_acl->allow('guest', 'players', array('index', 'add'));

		// ��������� ����� ������������� ������ auth � ��� ����������
		$this->_acl->allow('guest', 'auth', array('index', 'login', 'logout'));

		// ��� �������������� ������ � ���� ��������
		$this->_acl->allow('admin');

		// ��������� �������������� ������������� �������� ������
		$this->_acl->allow('admin', 'error');

	}


	public function preDispatch( Zend_Controller_Request_Abstract $request ) {
		// �������� ��� �������� �������
		$resource 	= $request->getControllerName();

		// �������� ��� action
		$action 		= $request->getActionName();

		// �������� ������ � ��������� ������ Zend,
		// � ������ ���� ������������
		$identity = Zend_Auth::getInstance()->getStorage()->read();

		// ���� � ��������� ������ ���, �� ������ �� ����� ���� � ������
		$role = ! empty( $identity->status ) ? $identity->status : 'guest';

		// ���� ������������ �� ������� �� ������� �������,
		// �� �������� ��� �� �������� �����������
		if ( ! $this->_acl->isAllowed( $role, $resource, $action ) ) {
			$request->setControllerName('error')->setActionName('deny');
		}
	}


}
