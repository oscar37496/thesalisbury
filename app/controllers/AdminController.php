<?php

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;

class AdminController extends BaseController {

	protected $layout = 'account.master';
	
	//========================== Begin Admin Pages ==========================================
	//=======================================================================================
	
	public function getAdminStocktake() {
		$data = app('request_data');
		$data['user'] = User::where('id', $data['id']) -> first();
		$data['location'] = 'Stocktake';
		$data['description'] = 'Take inventory of all stock';
		
		$data['ingredients'] = Ingredient::all();
		$this -> layout -> content = View::make('admin.stocktake', $data);
	}

	public function getAdminTransactions() {
		$data = app('request_data');
		$data['user'] = User::where('id', $data['id']) -> first();
		$data['location'] = 'All Transactions';
		$data['description'] = 'A list of all transactions';
		$data['transactions'] = Transaction::all();
		$data['transactions'] -> load('user', 'sku');
		$this -> layout -> content = View::make('admin.transactions', $data);
	}

	public function getAdminCards() {
		$data = app('request_data');
		$data['user'] = User::where('id', $data['id']) -> first();
		$tags = Tag::all();
		$tags -> load('user');
		foreach ($tags as $tag) {
			$id = $tag['id'];
			$data['tags'][$id] = $tag;
			$data['tags'][$id]['count'] = 0;
			$data['tags'][$id]['total'] = 0;
		}
		$data['tags']['manual']['count'] = 0;
		$data['tags']['manual']['total'] = 0;
		$transactions = Transaction::all();
		foreach ($transactions as $transaction) {
			if ($transaction['sku_id'] != 0) {
				if (!isset($transaction['tag_id'])) {
					$transaction['tag_id'] = 'manual';
				}
				$data['tags'][$transaction['tag_id']]['count'] += $transaction['quantity'];
				$data['tags'][$transaction['tag_id']]['total'] += $transaction['quantity'] * $transaction['price'];
			}
		}
		$data['location'] = 'All Cards';
		$data['description'] = 'All the cards linked to Salisbury Tabs';
		$this -> layout -> content = View::make('admin.cards', $data);
	}

	public function getAdminUsers() {

		$data = app('request_data');
		$data['user'] = User::where('id', $data['id']) -> first();

		$data['location'] = 'All Users';
		$data['description'] = 'All users with an account';

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

			$friend['total_spent'] = DB::select('SELECT SUM(price * quantity) `total` FROM transactions WHERE user_id = ? AND sku_id <> 0', array($user['id']))[0] -> total;
			$friend['total_spent_last_week'] = DB::select('SELECT SUM(price * quantity) `total` FROM transactions WHERE user_id = ? AND sku_id <> 0 AND timestamp >= FROM_UNIXTIME(?)', array($user['id'], time() - (7 * 24 * 60 * 60)))[0] -> total;
			$data['users'][$user['id']] = $friend;

		}

		$this -> layout -> content = View::make('admin.users', $data);

	}

	//============================ Admin AJAX Functions ======================================
	//========================================================================================

	public function setStocktake($id, $volume){
		$stocktake = new Stocktake();
		$stocktake -> ingredient_id = $id;
		$stocktake -> volume = $volume;
		$stocktake -> save();
		return $volume.'ml';
	}
	
	public function cardAction($id, $action) {
		setlocale(LC_MONETARY, "en_US.UTF-8");
		if (strcmp($action, "activate") == 0) {
			$is_activated = TRUE;
		} else if (strcmp($action, "deactivate") == 0) {
			$is_activated = FALSE;
		} else {
			return App::abort();
		}
		$tag = Tag::where('id', $id) -> first();
		if (isset($tag)) {
			$tag['is_activated'] = $is_activated;
			$tag -> save();
			$data['tag'] = $tag;
			return View::make('admin.ajax.cards', $data);
		}
		return App::abort();
	}

	public function userAction($id, $action) {
		setlocale(LC_MONETARY, "en_US.UTF-8");
		if (strcmp($action, "activate") == 0) {
			return $this -> setUserActivation($id, TRUE);
		} else if (strcmp($action, "deactivate") == 0) {
			return $this -> setUserActivation($id, FALSE);
		} else if (strcmp($action, "make-social") == 0) {
			return $this -> setUserSocial($id, TRUE);
		} else if (strcmp($action, "remove-social") == 0) {
			return $this -> setUserSocial($id, FALSE);
		}
		return App::abort();
	}

	public function notificationAction($id, $action) {
		if ($action === "activate") {
			$data['notification'] = Notification::where('id', $id) -> first();
			$user = User::where('id', $data['notification']['user_id']) -> first();
			$user -> is_activated = TRUE;
			$user -> save();
			$data['notification'] -> delete();
			$data['clear'] = FALSE;
			return View::make('admin.ajax.notifications', $data);
		} else if ($action === "clear") {
			DB::table('notifications') -> delete();
			$data['clear'] = TRUE;
			return View::make('admin.ajax.notifications', $data);
		} else {
			return App::abort();
		}
	}

	private function setUserActivation($id, $is_activated) {
		$user = User::where('id', $id) -> first();
		if (isset($user)) {
			$user['is_activated'] = $is_activated;
			$user -> save();
		}

		$data['total_spent'] = DB::select('SELECT SUM(price * quantity) `total` FROM transactions WHERE user_id = ? AND sku_id <> 0', array($user['id']))[0] -> total;
		$data['total_spent_last_week'] = DB::select('SELECT SUM(price * quantity) `total` FROM transactions WHERE user_id = ? AND sku_id <> 0 AND timestamp >= FROM_UNIXTIME(?)', array($user['id'], time() - (7 * 24 * 60 * 60)))[0] -> total;
		$data['user'] = $user;

		return View::make('admin.ajax.users', $data);
	}

	private function setUserSocial($id, $is_social) {
		$user = User::where('id', $id) -> first();
		if (isset($user)) {
			$user['is_social'] = $is_social;
			$user -> save();
		}

		$data['total_spent'] = DB::select('SELECT SUM(price * quantity) `total` FROM transactions WHERE user_id = ? AND sku_id <> 0', array($user['id']))[0] -> total;
		$data['total_spent_last_week'] = DB::select('SELECT SUM(price * quantity) `total` FROM transactions WHERE user_id = ? AND sku_id <> 0 AND timestamp >= FROM_UNIXTIME(?)', array($user['id'], time() - (7 * 24 * 60 * 60)))[0] -> total;
		$data['user'] = $user;

		return View::make('admin.ajax.users', $data);
	}

}
