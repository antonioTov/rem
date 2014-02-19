<?php

/**
 * @property Application_Model_DbTable_Users usersModel
 */
class AuthController extends Zend_Controller_Action
{

	// vkontakte -------------------------------------//
	const VKONTAKTE_APP_ID 	= 4191611;
	const VKONTAKTE_SECRET	= 'P0qefPgprZoBMpLOO9ne';
	const VKONTAKTE_SCOPE 		= 'offline';
	const VKONTAKTE_REDIRECT= '/auth/vk';

	// facebook  -------------------------------------//
	const FACEBOOK_APP_ID 		= 740619945957406;
	const FACEBOOK_SECRET		= 'ae40f380e25a12c872edb6e19eef0cdb';
	const FACEBOOK_SCOPE 		= 'offline_access,email';
	const FACEBOOK_REDIRECT	= '/auth/fb';

	// facebook  -------------------------------------//
	const ODNOKLASSNIKI_APP_ID 		= 223856128;
	const ODNOKLASSNIKI_PUBLIC		= 'CBAHMIFOABABABABA';
	const ODNOKLASSNIKI_SECRET		= 'CFBA761A4CAEE083D0EDFBEB';
	const ODNOKLASSNIKI_SCOPE 		= 'VALUABLE_ACCESS';
	const ODNOKLASSNIKI_REDIRECT	= '/auth/od';


	private $auth;

	private $adapter;

	private $authAdapter;

	private $usersModel;

	private $tableUsers;

	/**
	 * @var
	 */
	private $isAjax;

	/**
	 * Инициализация контролленра
	 */
	public function init()
	{
		$this->isAjax = $this->_request->isXmlHttpRequest();
		if ($this->isAjax) {
			// если это аякс запрос то отключаем layout
			$this->_helper->layout()->disableLayout();
		}

		$this->usersModel		= new Application_Model_DbTable_Users();
		$this->tableUsers 		= $this->usersModel->info('name');
		$this->auth 			 	= Zend_Auth::getInstance();
		$this->adapter 		 	= Zend_Db_Table::getDefaultAdapter();
		$this->authAdapter 	= new Zend_Auth_Adapter_DbTable( $this->adapter, $this->tableUsers, 'email','pass', 'MD5(?)' /* AND active = 1' */ );

	}

	public function indexAction()
	{
		$this->redirect('/auth/login');
	}

	/**
	 * Стандартная авторизация через сайт
	 * @return mixed
	 */
	public function loginAction()
	{
		if ( $this->auth->hasIdentity() ) {
			$this->redirect('/');
		}

		if ($this->isAjax) {
			$this->_helper->ViewRenderer->setNoRender();
		}

		$errorMsg = 'Неправильный логин или пароль!';

		$form = new Application_Form_Login();

		if ( $this->getRequest()->isPost() )
		{
			$formData = $this->getRequest()->getPost();
			if ( $form->isValid( $formData ) )
			{
				$this->authAdapter->setIdentity( $form->email->getValue() );
				$this->authAdapter->setCredential( $form->pass->getValue() );
				$result = $this->auth->authenticate( $this->authAdapter );

				if ( $result->isValid() ) {

					$this->_autorize();

					if ($this->isAjax) {
						return $this->_helper->json(array('status' => 'ok'));
					}

					$this->redirect('/');
				}
				else {

					if ($this->isAjax) {
						return $this->_helper->json(array('status' => 'fail', 'message' => App_Iconv::toUtf8($errorMsg) ));
					}

					$this->_helper->FlashMessenger($errorMsg);
				}

			}
			else {

				if ($this->isAjax) {
					return $this->_helper->json(array('status' => 'fail', 'message' =>  App_Iconv::toUtf8($errorMsg) ));
				}

				$this->_helper->FlashMessenger($errorMsg);
			}

		}

		$this->view->loginForm	= $form;
		$this->view->message 	= $this->getHelper('FlashMessenger')->getCurrentMessages();
	}


	/**
	 * Авторизация
	 * @internal param bool $bySocial
	 * @return bool
	 */
	private function _autorize()
	{
		$storage = $this->auth->getStorage();
		$storage_data = $this->authAdapter->getResultRowObject(
			null,
			array('pass', 'access_token'));

		// допишем данные профиля в сессию
		$profileModel = new Application_Model_DbTable_Profiles();
		$profileData = $profileModel->getProfile( $storage_data->profile_id );

		$storage->write( (object) array_merge( (array) $storage_data, (array) $profileData));

		$this->usersModel->lastVisit( $storage_data->id );

		return true;
	}

	/**
	 * Аутентификация
	 */
	private function _authenticate( $uid )
	{
		$this->authAdapter = new Zend_Auth_Adapter_DbTable(  $this->adapter, $this->tableUsers, 'social_id','pass', '? or 1 = 1'  );
		$this->authAdapter->setIdentity( $uid );
		$this->authAdapter->setCredential( true );

		return $this->auth->authenticate( $this->authAdapter );
	}

	/**
	 * получение кода автоизации
	 * @return bool|mixed
	 */
	private function _getCode()
	{
		$code = $this->getRequest()->getParam('code');
		return $code ? $code : false;
	}


	/**
	 * Авторизация через Vkontakte
	 */
	public function vkAction()
	{
		if( ! $code = $this->_getCode() ) {
			$login_url = "http://api.vk.com/oauth/authorize?client_id=" . $this::VKONTAKTE_APP_ID . "&scope=" . $this::VKONTAKTE_SCOPE . "&response_type=code&redirect_uri=" . $_SERVER['HTTP_X_FORWARDED_PROTO'] . '://' . $_SERVER["HTTP_HOST"]  . $this::VKONTAKTE_REDIRECT;
			$this->redirect($login_url);
		}

		$result = file_get_contents("https://oauth.vk.com/access_token?client_id=" . $this::VKONTAKTE_APP_ID."&scope=" . $this::VKONTAKTE_SCOPE . "&client_secret=". $this::VKONTAKTE_SECRET . "&code=" . $code . "&redirect_uri=" . urldecode($_SERVER['HTTP_X_FORWARDED_PROTO'] . '://' . $_SERVER["HTTP_HOST"] . $this::VKONTAKTE_REDIRECT));
		$userData = json_decode($result,true);

		if( isset( $userData['user_id'] ) && $userData['user_id'] == true ) {
			$userModel = new Application_Model_DbTable_Users();

			//Если пользователь уже существует в БД, то обновляем его
			if( $userModel->findBySocId( $userData['user_id'] ) ) {

				//UPDATE USER

			} else {
				// иначе регистрируем нового пользователя
				$result = file_get_contents('https://api.vkontakte.ru/method/getProfiles?uid=' . $userData['user_id'] . '&fields=uid,first_name,last_name,photo_rec&access_token=' . $userData['access_token']);
				$udata = json_decode($result,true);
				$udata = $udata['response'][0];

				$data = array(
					'social_id'			=> $udata['uid'],
					'access_token'	=> $userData['access_token'],
					'photo'				=> $udata['photo_rec'],
					'first_name'		=> App_Iconv::toCp1251($udata['first_name']),
					'last_name'		=> App_Iconv::toCp1251($udata['last_name']),
					'social_net'			=> 'vk'
				);

				if( $userModel->socRegAdd( $data ) ) {
					throw new Exception('Registration error');
				}
			}

			//Авторизация и аутентификация
			$result = $this->_authenticate( $userData['user_id'] );
			if ( $result->isValid() ) {
				$this->_autorize();
			}
		}
		$this->redirect('/');
	}


	/**
	 * Авторизация через Facebook
	 */
	public function fbAction()
	{
		if( ! $code = $this->_getCode() ) {
			$login_url = "https://www.facebook.com/dialog/oauth?client_id=" . $this::FACEBOOK_APP_ID . "&scope=" . $this::FACEBOOK_SCOPE . "&redirect_uri=" . $_SERVER['HTTP_X_FORWARDED_PROTO'] . '://' . $_SERVER["HTTP_HOST"]  . $this::FACEBOOK_REDIRECT;
			$this->redirect($login_url);
		}

		$result = file_get_contents("https://graph.facebook.com/oauth/access_token?client_id=" . $this::FACEBOOK_APP_ID . "&scope=" . $this::FACEBOOK_SCOPE . "&client_secret=" . $this::FACEBOOK_SECRET . "&code=" . $code . "&redirect_uri=" . urldecode($_SERVER['HTTP_X_FORWARDED_PROTO'] . '://' . $_SERVER["HTTP_HOST"] . $this::FACEBOOK_REDIRECT));
		parse_str($result);
		$result    	= file_get_contents('https://graph.facebook.com/me?access_token=' . $access_token );
		$userData 	= json_decode($result,true);

		if( isset( $userData['id'] ) && $userData['id'] == true ) {
			$userModel = new Application_Model_DbTable_Users();

			//Если пользователь уже существует в БД, то обновляем его
			if( $userModel->findBySocId( $userData['id'] ) ) {

				//UPDATE USER

			} else {
				// иначе регистрируем нового пользователя
				$data = array(
					'social_id'			=> $userData['id'],
					'access_token'	=> $access_token,
					'email'				=> $userData['email'],
					'photo'				=> 'http://graph.facebook.com/'.$userData['id'].'/picture',
					'first_name'		=> App_Iconv::toCp1251($userData['first_name']),
					'last_name'		=> App_Iconv::toCp1251($userData['last_name']),
					'social_net'			=> 'fb'
				);

				if( ! $userModel->socRegAdd( $data ) ) {
					throw new Exception('Registration error');
				}
			}

			//Авторизация и аутентификация
			$result = $this->_authenticate( $userData['id'] );
			if ( $result->isValid() ) {
				$this->_autorize();
			}
		}
		$this->redirect('/');
	}


	/**
	 * Авторизация через Odnoklassniki
	 */
	public function odAction()
	{
		if( ! $code = $this->_getCode() ) {
			echo $login_url = "http://www.odnoklassniki.ru/oauth/authorize?client_id=" . $this::ODNOKLASSNIKI_APP_ID . "&scope=" . $this::ODNOKLASSNIKI_SCOPE . "&response_type=code&redirect_uri=" .  urlencode($_SERVER['HTTP_X_FORWARDED_PROTO'] . '://' . $_SERVER["HTTP_HOST"]  . $this::ODNOKLASSNIKI_REDIRECT);
			$this->redirect($login_url);
		}


		$params = array(
			'code' => $code,
			'redirect_uri' => $_SERVER['HTTP_X_FORWARDED_PROTO'] . '://' . $_SERVER["HTTP_HOST"]  . $this::ODNOKLASSNIKI_REDIRECT,
			'grant_type' => 'authorization_code',
			'client_id' => $this::ODNOKLASSNIKI_APP_ID,
			'client_secret' => $this::ODNOKLASSNIKI_SECRET
		);

		$url = 'http://api.odnoklassniki.ru/oauth/token.do';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url); // url, куда будет отправлен запрос
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params))); // передаём параметры
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($curl);
		curl_close($curl);

		$tokenInfo = json_decode($result, true);

		if ( isset($tokenInfo['access_token']) ) {
			$sign = md5("application_key=" . $this::ODNOKLASSNIKI_PUBLIC . "method=users.getCurrentUser" . md5( $tokenInfo['access_token'] . $this::ODNOKLASSNIKI_SECRET ));

			$params = array(
				'method'          	=> 'users.getCurrentUser',
				'access_token'   	=> $tokenInfo['access_token'],
				'application_key'	=> $this::ODNOKLASSNIKI_PUBLIC,
				'sig'             		=> $sign
			);

			$userData = json_decode(file_get_contents('http://api.odnoklassniki.ru/fb.do?' . urldecode(http_build_query($params))), true);
		}

		if( isset( $userData['uid'] ) && $userData['uid'] == true ) {
			$userModel = new Application_Model_DbTable_Users();

			//Если пользователь уже существует в БД, то обновляем его
			if( $userModel->findBySocId( $userData['uid'] ) ) {

				//UPDATE USER

			} else {
				// иначе регистрируем нового пользователя
				$data = array(
					'social_id'			=> $userData['uid'],
					'access_token'	=> $tokenInfo['access_token'],
					'photo'				=> $userData['pic_2'],
					'first_name'		=> App_Iconv::toCp1251($userData['first_name']),
					'last_name'		=> App_Iconv::toCp1251($userData['last_name']),
					'social_net'			=> 'od'
				);

				if( ! $userModel->socRegAdd( $data ) ) {
					throw new Exception('Registration error');
				}
			}

			//Авторизация и аутентификация
			$result = $this->_authenticate( $userData['uid'] );
			if ( $result->isValid() ) {
				$this->_autorize();
			}
		}
		$this->redirect('/');
	}

}