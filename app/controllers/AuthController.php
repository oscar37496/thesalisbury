<?php

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;

class AuthController extends BaseController {


	function authFacebook() {
		session_start();
		FacebookSession::setDefaultApplication($_ENV['facebook_api_id'], $_ENV['facebook_api_secret']);
		$helper = new FacebookRedirectLoginHelper(action('AuthController@authFacebookCallback'));
		$required_scope = 'user_friends';
		return Redirect::to($helper -> getLoginUrl(array('scope' => $required_scope)));
	}

	function authFacebookCallback() {
		session_start();
		FacebookSession::setDefaultApplication($_ENV['facebook_api_id'], $_ENV['facebook_api_secret']);
		$helper = new FacebookRedirectLoginHelper(action('AuthController@authFacebookCallback'));
		try {
			$session = $helper -> getSessionFromRedirect();
		} catch(FacebookRequestException $e) {

			return Redirect::to('/') -> with('error', "Exception occured, code: " . $e -> getCode() . " with message: " . $e -> getMessage());
		} catch(\Exception $e) {
			return Redirect::to('/') -> with('error', 'Server Error');
		}
		if (isset($session) && $session) {
			$response =   (new FacebookRequest($session, 'GET', '/me')) -> execute();
			$object = $response -> getGraphObject();
			$me = $response -> getGraphObject(GraphUser::className());
			$id = $me -> getID();
			$user = User::where('id', $id);
			if ($user -> first() != NULL) {
				if ($user -> first() -> is_activated) {
					Session::put('fb_session', $session);
					//Session::put('fb_user', $me);
					Session::put('id', $id);
					//Session::put('user', $user -> first());
					return Redirect::to('account/dashboard/#');
				} else {
					Session::flush();
					return Redirect::to('/#') -> with('error_message', 'Your account has not been activated, please see one of the syndicate to activate your account');
				}
			} else {
				$user = new User();
				$user -> id = $me -> getId();
				if(($user -> first_name = $me -> getFirstName()) == NULL) $user -> first_name = "";
				if(($user -> middle_name = $me -> getMiddleName()) == NULL) $user -> middle_name = "";
				if(($user -> last_name = $me -> getLastName()) == NULL) $user -> last_name = "";
				if(($user -> gender = $object -> getProperty('gender')) == NULL) $user -> gender = "male";			
				$notification = new Notification();
				$notification -> user_id = $user -> id;
				$user -> save();
				$notification -> save();
				Session::flush();
				return Redirect::to('/#') -> with('error_message', 'Your account has not been activated, please see one of the syndicate to activate your account');
			}
		} else {
			return Redirect::to('/#') -> with('error_message', 'Session Error');
		}
	}

	function deauthFacebook() {
		if (Session::has('fb_session')) {
			FacebookSession::setDefaultApplication($_ENV['facebook_api_id'], $_ENV['facebook_api_secret']);
			$session = Session::get('fb_session');
			$helper = new FacebookRedirectLoginHelper(action('AuthController@authFacebookCallback'));
			$helper -> getLogoutUrl($session, action('HomeController@getLanding'));
			Session::flush();
			return Redirect::to('/#');
		} else {
			return Redirect::to('/#');
		}
	}

}
