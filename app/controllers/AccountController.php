<?php

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;

class AccountController extends BaseController {

	protected $layout = 'account.master';

	//============================= Account Functions ===========================================
	//==========================================================================================

	public function getDashboard() {
		$data = app('request_data');
		$data['user'] = User::where('id', $data['id']) -> first();
		$data['user'] -> load('transaction');

		$data['sku_count'] = $this -> getSkuCount($data['user']);
		$data['account_balance'] = $this -> getAccountBalanceTimeline($data['user']);

		$friends =   (new FacebookRequest($data['fb_session'], 'GET', '/me/friends')) -> execute() -> getGraphObject(GraphUser::className()) -> asArray()['data'];
		$data['friend_count'] = 0;
		foreach($friends as $graph_user){
			$friend = User::where('id', $graph_user->id) -> first();
			if($friend['is_activated'] && $friend['is_social']){
				$data['friend_count']++;
			}
		}	
		$data['drink_count'] = DB::select('SELECT SUM(t.quantity*skus.standard_drinks) `total` FROM transactions AS t INNER JOIN skus ON t.sku_id = skus.id WHERE user_id = ?', array($data['user']->id))[0]->total / 100;
		if(!isset($data['drink_count'])) $data['drink_count'] = 0;
		
		
		$data['location'] = 'Dashboard';
		$data['description'] = 'An overview of your account';

		$this -> layout -> content = View::make('account.dashboard', $data);
	}

	public function getStatistics() {
		$data = app('request_data');
		$data['user'] = User::where('id', $data['id']) -> first();
		$data['user'] -> load('transaction', 'transaction.sku', 'bankTransaction');

		$data['sku_count'] = $this -> getSkuCount($data['user']);
		$data['account_balance'] = $this -> getAccountBalanceTimeline($data['user']);
		

		$data['location'] = 'Statistics';
		$data['description'] = 'Analysis of what you\'ve bought';
		$this -> layout -> content = View::make('account.statistics', $data);
	}

	public function getTransactions() {
		$data = app('request_data');
		$data['user'] = User::where('id', $data['id']) -> first();
		$data['user'] -> load('transaction', 'transaction.tag', 'transaction.sku', 'bankTransaction', 'cashTransaction');
		
		$data['transactions'] = DB::select("SELECT t.timestamp, tags.description AS tag_description, skus.description AS sku_description, t.price, t.quantity, (-t.quantity*t.price) AS total, 0 AS balance FROM transactions AS t
				INNER JOIN skus ON t.sku_id = skus.id
				LEFT JOIN tags ON t.tag_id = tags.id
				WHERE t.user_id = ?
			UNION ALL SELECT date, 'Bank Transaction', 'Credit Added', amount, 1, amount, 0 FROM bank_transactions WHERE user_id = ? 
			UNION ALL SELECT timestamp, 'Bank Transaction', 'Credit Added', amount, 1, amount, 0 FROM cash_transactions WHERE user_id = ?
			ORDER BY timestamp", array($data['user']->id, $data['user']->id, $data['user']->id));
		$balance = 0;
		foreach($data['transactions'] as $transaction){
			$transaction->balance = ($balance += $transaction->total);
		}
		$data['location'] = 'Transactions';
		$data['description'] = 'A list of all your purchases';
		$this -> layout -> content = View::make('account.transactions', $data);
	}

	public function getCards() {
		$data = app('request_data');
		$data['user'] = User::where('id', $data['id']) -> first();
		$data['user'] -> load('transaction', 'transaction.tag', 'tag');
		
		$data['tags'] = DB::select("(SELECT tags.id, tags.description, tags.is_activated, COUNT(t.quantity) AS transaction_count, SUM(t.quantity) AS drink_count, SUM(t.price*t.quantity) AS total FROM tags 
		LEFT JOIN transactions AS t ON tags.id = t.tag_id WHERE tags.user_id = ?)
		UNION
		(SELECT NULL, 'Manual Transaction', NULL, COUNT(quantity), SUM(quantity), SUM(price*quantity) FROM transactions WHERE user_id = ? AND tag_id IS NULL)", array($data['user']->id, $data['user']->id));
		$data['location'] = 'Cards';
		$data['description'] = 'All the cards linked to your account';
		$this -> layout -> content = View::make('account.cards', $data);
	}

	public function getFriends() {
	
    	$data = app('request_data');
		$data['user'] = User::where('id', $data['id']) -> first();
		if( (!$data['user']->is_social) && (!$data['user']->is_admin))
			return Redirect::to('/account/dashboard');
		
		$data['location'] = 'Friends';
		$data['description'] = 'Your friends that have Salisbury Tabs';
		
		$friends =   (new FacebookRequest($data['fb_session'], 'GET', '/me/friends')) -> execute() -> getGraphObject(GraphUser::className()) -> asArray()['data'];
		foreach($friends as $graph_user){
			$user = User::where('id', $graph_user->id) -> first();
			if($user['is_activated'] && $user['is_social']){
				$friend['first_name'] = $user['first_name'];
				$friend['last_name'] = $user['last_name'];
				$friend['balance'] = $user['balance'];
				
				$friend['total_spent'] = DB::select('SELECT SUM(price * quantity) `total` FROM transactions WHERE user_id = ? AND sku_id <> 0', array($user['id']))[0]->total;
				$friend['total_spent_last_week'] = DB::select('SELECT SUM(price * quantity) `total` FROM transactions WHERE user_id = ? AND sku_id <> 0 AND timestamp >= FROM_UNIXTIME(?)', array($user['id'], time() - (7 * 24 * 60 * 60) ))[0]->total;
				$data['friends'][$user['id']] = $friend;
			}
		}		
		
		$this -> layout -> content = View::make('account.friends', $data);

	}


	//============================= Helper Functions ===========================================
	//==========================================================================================
	
	private function getProfitTimeline(){
		//types :
		//cash - 1
		//bank - 2
		//purchase - 3
		//stocktake - 4
		//transaction - 5
		$a = array();
		$ct = CashTransaction::all();
		foreach ($ct as $c) {
			$data['type'] = 1;
			$data['value'] = $c;
			$time = strtotime($c['timestamp']);
			while(isset($a[$time])){
				$time++;
			}
			$a[$time] = $data;
		}
		$bt = BankTransaction::all();
		foreach ($bt as $c) {
			$data['type'] = 2;
			$data['value'] = $c;
			$time = strtotime($c['date']);
			while(isset($a[$time])){
				$time++;
			}
			$a[$time] = $data;
		}
		$purchases = Purchase::all();
		foreach ($purchases as $c) {
			$data['type'] = 3;
			$data['value'] = $c;
			$time = strtotime($c['timestamp']);
			while(isset($a[$time])){
				$time++;
			}
			$a[$time] = $data;
		}
		$stocktakes = Stocktake::all();
		foreach ($stocktakes as $c) {
			$data['type'] = 4;
			$data['value'] = $c;
			$time = strtotime($c['timestamp']);
			while(isset($a[$time])){
				$time++;
			}
			$a[$time] = $data;
		}
		$transactions = Transaction::all();
		foreach ($transactions as $c) {
			$data['type'] = 5;
			$data['value'] = $c;
			$time = strtotime($c['timestamp']);
			while(isset($a[$time])){
				$time++;
			}
			$a[$time] = $data;
		}
		ksort($a);
		$payouts = 0;
		$stock_value = 0;
		$cash_balance = 0;
		$bank_balance = 0;
		$positive_tab_balance = 0;
		$negative_tab_balance = 0;
		$output = array();
		foreach ($a as $time => $c) {
			switch ($c['type']) {
			    case 1: //cash-transaction
			    	if(strcmp($c['value']['type'], 'PAYOUT')==0){
			    		$payouts += -$c['value']['amount'];
			    	}elseif(strcmp($c['value']['type'], 'TABCREDIT')==0){
			    		$balance = -DB::select("SELECT SUM(quantity*price) balance FROM transactions WHERE user_id=? AND timestamp <= ?",
			    								 array($c['value']['user_id'], $c['value']['timestamp']))[0]->balance
												 +DB::select("SELECT SUM(amount) balance FROM bank_transactions WHERE app_type='TABCREDIT' AND user_id=? AND date <= ?",
			    								 array($c['value']['user_id'], $c['value']['timestamp']))[0]->balance
												 +DB::select("SELECT SUM(amount) balance FROM cash_transactions WHERE type='TABCREDIT' AND user_id=? AND timestamp <= ?",
			    								 array($c['value']['user_id'], $c['value']['timestamp']))[0]->balance;
						if($c['value']['amount']>0){
			    			$positive_tab_balance += ($balance+$c['value']['amount'])<0 ? 0 : ( ($balance<0) ? $balance+$c['value']['amount'] : $c['value']['amount'] );
			    			$negative_tab_balance += ($balance+$c['value']['amount'])<0 ? -$c['value']['amount'] : ( ($balance<0) ? -($balance) : 0 );
						}else{
							$positive_tab_balance += ($balance+$c['value']['amount'])>0 ? $c['value']['amount'] : ( ($balance>0) ? -($balance) : 0 );
			    			$negative_tab_balance += ($balance+$c['value']['amount'])>0 ? 0 : ( ($balance>0) ? -($balance+$c['value']['amount']) : -$c['value']['amount'] );
						}
					}elseif(strcmp($c['value']['type'], 'PURCHASE')==0){
			    		$stock_value += -$c['value']['amount'];
			    	}
			        $cash_balance += $c['value']['amount'];
			        break;
			    case 2: //bank-transaction
			    	if(strcmp($c['value']['app_type'], 'CASHDEPOSIT') == 0) $cash_balance += -$c['value']['amount'];
					if(strcmp($c['value']['app_type'], 'PURCHASE') == 0) $stock_value += -$c['value']['amount'];
					if(strcmp($c['value']['app_type'], 'PAYOUT') == 0) $payouts += -$c['value']['amount'];
					if(strcmp($c['value']['app_type'], 'TABCREDIT') == 0) {
						$balance = -DB::select("SELECT SUM(quantity*price) balance FROM transactions WHERE user_id=? AND timestamp <= ?",
			    								 array($c['value']['user_id'], $c['value']['timestamp']))[0]->balance
												 +DB::select("SELECT SUM(amount) balance FROM bank_transactions WHERE app_type='TABCREDIT' AND user_id=? AND date <= ?",
			    								 array($c['value']['user_id'], $c['value']['timestamp']))[0]->balance
												 +DB::select("SELECT SUM(amount) balance FROM cash_transactions WHERE type='TABCREDIT' AND user_id=? AND timestamp <= ?",
			    								 array($c['value']['user_id'], $c['value']['timestamp']))[0]->balance;
						if($c['value']['amount']>0){
			    			$positive_tab_balance += ($balance+$c['value']['amount'])<0 ? 0 : ( ($balance<0) ? $balance+$c['value']['amount'] : $c['value']['amount'] );
			    			$negative_tab_balance += ($balance+$c['value']['amount'])<0 ? -$c['value']['amount'] : ( ($balance<0) ? -($balance) : 0 );
						}else{
							$positive_tab_balance += ($balance+$c['value']['amount'])>0 ? $c['value']['amount'] : ( ($balance>0) ? -($balance) : 0 );
			    			$negative_tab_balance += ($balance+$c['value']['amount'])>0 ? 0 : ( ($balance>0) ? -($balance+$c['value']['amount']) : -$c['value']['amount'] );
						}
					}
			        $bank_balance = $c['value']['balance'];
			        break;
				case 3: //purchase
			      	$stock_value = $this->getStockValueByDate(strtotime($c['value']['timestamp']));
			        break;
				case 4: //stocktake
			      	$stock_value = $this->getStockValueByDate(strtotime($c['value']['timestamp']));
			        break;
				case 5: //transaction
			        $balance = -DB::select("SELECT SUM(quantity*price) balance FROM transactions WHERE user_id=? AND timestamp <= ?",
		    								 array($c['value']['user_id'], $c['value']['timestamp']))[0]->balance
											 +DB::select("SELECT SUM(amount) balance FROM bank_transactions WHERE app_type='TABCREDIT' AND user_id=? AND date <= ?",
		    								 array($c['value']['user_id'], $c['value']['timestamp']))[0]->balance
											 +DB::select("SELECT SUM(amount) balance FROM cash_transactions WHERE type='TABCREDIT' AND user_id=? AND timestamp <= ?",
		    								 array($c['value']['user_id'], $c['value']['timestamp']))[0]->balance;
					if(-$c['value']['quantity']*$c['value']['price']>0){
		    			$positive_tab_balance += ($balance-$c['value']['quantity']*$c['value']['price'])<0 ? 0 : ( ($balance<0) ? $balance-$c['value']['quantity']*$c['value']['price'] : -$c['value']['quantity']*$c['value']['price'] );
		    			$negative_tab_balance += ($balance-$c['value']['quantity']*$c['value']['price'])<0 ? $c['value']['quantity']*$c['value']['price'] : ( ($balance<0) ? -($balance) : 0 );
					}else{
						$positive_tab_balance += ($balance-$c['value']['quantity']*$c['value']['price'])>0 ? -$c['value']['quantity']*$c['value']['price'] : ( ($balance>0) ? -($balance) : 0 );
		    			$negative_tab_balance += ($balance-$c['value']['quantity']*$c['value']['price'])>0 ? 0 : ( ($balance>0) ? -($balance-$c['value']['quantity']*$c['value']['price']) : +$c['value']['quantity']*$c['value']['price'] );
					}
			        break;
			}
			$output[] = ['time' => date('c',$time), 
						'assets' => ($stock_value + $cash_balance + ($bank_balance>0 ? $bank_balance : 0) + $negative_tab_balance) / 100, 
						'liabilities' => (($bank_balance<0 ? -$bank_balance : 0) + $positive_tab_balance) / 100,
						'payouts' => $payouts/100,
						'profit' => ($payouts + $stock_value + $cash_balance + $bank_balance + $negative_tab_balance - $positive_tab_balance)/100];
		}
		if(isset($output))
			return json_encode($output);
		
	}
	
	private function getBankBalanceTimeline() {
		$bank_transactions = BankTransaction::all();
		if(isset($bank_transactions))
		foreach ($bank_transactions as $transaction) {
			$account_balance[] = ['time' => $transaction->date, 'balance' => $transaction->balance / 100];
		};
		if(isset($account_balance))
			return json_encode($account_balance);
	}

	private function getAccountBalanceTimeline($user) {
		$account_balance[] = ['time' => $user -> date_created, 'balance' => 0];
		
		$transactions = DB::select("SELECT timestamp, (-quantity*price) AS amount FROM transactions WHERE user_id = ? UNION ALL
			SELECT date, amount FROM bank_transactions WHERE user_id = ? UNION ALL
			SELECT timestamp, amount FROM cash_transactions WHERE user_id = ?
			ORDER BY timestamp", array($user->id, $user->id, $user->id));
		$balance = 0;
		foreach($transactions as $t){
			$account_balance[] = ['time' => $t->timestamp, 'balance' => ($balance += $t->amount)/ 100];
		}
		$account_balance[] = ['time' => date('Y-m-d G:i:s'), 'balance' => $user -> balance / 100];
		if(isset($account_balance))
			return json_encode($account_balance);
	}

	private function getSkuCount($user) {
		if(isset($user)){
			$transactions = $user->transaction;
		} else {
			$transactions = Transaction::all();
		}
		foreach ($transactions as $transaction) {
			if ($transaction['sku'] != NULL) {
				if (!isset($skus[$transaction['sku']['id']])) {
					$skus[$transaction['sku']['id']]['label'] = $transaction['sku']['description'];
					$skus[$transaction['sku']['id']]['value'] = 0;
				}
				$skus[$transaction['sku']['id']]['value'] = $skus[$transaction['sku']['id']]['value'] + $transaction['quantity'];
			}
		}

		if (isset($skus))
			return json_encode(array_values($skus));
	}

}
