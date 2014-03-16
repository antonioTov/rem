<?php

class ProfileController extends Zend_Controller_Action
{

	/**
	 * profile ID
	 * @var
	 */
	private $_pid;
	/**
	 * user ID
	 * @var
	 */
	private $_uid;


    public function init()
    {
		if( ! Zend_Auth::getInstance()->hasIdentity() ) {
			$this->redirect('/');
		}

		$this->_pid = Zend_Auth::getInstance()->getIdentity()->profile_id;
		$this->_uid = Zend_Auth::getInstance()->getIdentity()->id;
		$this->view->pid = $this->_pid;
    }


	/**
	 * Просмотр профиля
	 */
	public function viewAction()
    {
		$pid = $this->getParam('id', $this->_pid );

		$profileModel 	= new Application_Model_DbTable_Profiles();
		$profile = $profileModel->getProfile( $pid );

		if (! $profile ) {
			$this->renderScript('error/404.phtml');
		}

		// Узнаем горд по ID
		if ( $cityId = $profile['city_id'] ) {
			$citiesModel	= new Application_Model_DbTable_Cities();
			$cityData 		= $citiesModel->getById( $cityId );
			$profile['city'] = $cityData->name;
		}

		$this->view->title = $profile['first_name'] . ' ' . $profile['last_name'];
		$this->view->profile = $profile;

    }


	/**
	 * Изменение пароля
	 */
	public function passwordAction()
	{
		$form = new Application_Form_ChangePass();

		$this->view->form = $form;

		if ( $this->getRequest()->isPost() )
		{
			$formData = $this->getRequest()->getPost();
			if ( $form->isValid( $formData ) )
			{
				$pass 	= $form->pass->getValue();

				$userModel = new Application_Model_DbTable_Users();
				$userModel->changePassword( $this->_uid, $pass );

				/* изменение пароля в сессии
				$storage_data = Zend_Auth::getInstance()->getStorage()->read();
				$storage_data->pass = md5( $pass );

				$storage = Zend_Auth::getInstance()->getStorage();
				$storage->write($storage_data);
				*/

				$this->redirect('/profile/' . $this->_pid );
			 }

		}

	}

	/**
	 * Редактирование профиля
	 */
	public function editAction()
	{
		$profileForm 	= new Application_Form_Profile();
		$profileModel 	= new Application_Model_DbTable_Profiles();
		$userModel	= new Application_Model_DbTable_Users();

		if( $this->getRequest()->isPost() )
		{
			$formData = $this->getRequest()->getPost();

			// при редактировании если email не изменился, то не проверяем го наличие в БД
			if ($formData['email'] == Zend_Auth::getInstance()->getIdentity()->email ) {
				$profileForm->getElement('email')->removeValidator('Db_NoRecordExists');
			}

			$profileForm->isValid($formData);

			// Обновляем данные профиля
			$profileModel->updateProfile( $profileForm->getValues() );

			// Обноволяем email в БД и сесии
			if ( ! $profileForm->getErrors('email') ) {
				$userModel->updateEmail( $profileForm->email->getValue() );
			}

			//$this->redirect($_SERVER["REQUEST_URI"]);
		}

		// Получаем данные профиля
		$profileData = $profileModel->getProfile();
		$profileForm->populate( $profileData );
		$profileForm->getElement('email')->setValue( Zend_Auth::getInstance()->getIdentity()->email );

		// Узнаем горд по ID
		if ( $cityId = $profileData['city_id'] ) {
			$citiesModel	= new Application_Model_DbTable_Cities();
			$cityData 		= $citiesModel->getById( $cityId );

			// Подставляем название города в атрибут элемента
			$profileForm->getElement('city_id')
				->setAttrib('data-init-text', $cityData->name );
		}
		else {
			$profileForm->getElement('city_id')->setValue(null);
		}

		$this->view->howLong = date('d.m.y', strtotime( Zend_Auth::getInstance()->getIdentity()->reg_date ) );
		$this->view->avatar = $profileData['photo'];
		$this->view->form = $profileForm;
		$this->view->title = $profileData['first_name'] . ' ' . $profileData['last_name'];

	}


}

