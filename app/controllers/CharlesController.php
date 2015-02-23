<?php

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;

class CharlesController extends BaseController {

	protected $layout = 'account.master';

	
	//============================ Charles Message Page ======================================
	//========================================================================================
	
	public function getCharlesUsers() {

		$data = app('request_data');
		$data['user'] = User::where('id', $data['id']) -> first();

		$data['location'] = 'Message Users';
		$data['description'] = 'Message users on Facebook';

		$users = User::all();
		
		
		foreach ($users as $user) {
			if ($user['is_activated']) {
				$friend['is_activated'] = TRUE;
			} else {
				$friend['is_activated'] = FALSE;
			}
			if ($user['is_social']) {
				$friend['is_social'] = TRUE;
			} else {
				$friend['is_social'] = FALSE;
			}
			$friend['first_name'] = $user['first_name'];
			$friend['middle_name'] = $user['middle_name'];
			$friend['last_name'] = $user['last_name'];
			$friend['balance'] = $user['balance'];
			$friend['last_messaged'] = (isset($user['last_messaged']) ? date('M j', strtotime($user['last_messaged'])) : 'Never');

			$friend['total_spent'] = DB::select('SELECT SUM(price * quantity) `total` FROM transactions WHERE user_id = ? AND sku_id <> 0', array($user['id']))[0] -> total;
			$friend['total_spent_last_week'] = DB::select('SELECT SUM(price * quantity) `total` FROM transactions WHERE user_id = ? AND sku_id <> 0 AND timestamp >= FROM_UNIXTIME(?)', array($user['id'], time() - (7 * 24 * 60 * 60)))[0] -> total;
			$data['users'][$user['id']] = $friend;

		}

		$this -> layout -> content = View::make('charles.users', $data);

	}
	
	//================================ Charles AJAX Functions ==================================
	//==========================================================================================
	
	public function updateLastMessaged($id){
		DB::update('UPDATE users SET last_messaged = CURRENT_TIMESTAMP() WHERE id=?', array($id));
		return date('M j');
	}
	

}
