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
	
	//============================= Helper Functions ===========================================
	//==========================================================================================
	
	private function getAssets(){
		$tab_assets = - DB::select('SELECT SUM(balance) `total` FROM users WHERE balance < 0')[0]->total;
		$bank_balance = ($t=BankTransaction::latest('date')->first())?$t->balance:0; 
		//DB::select('SELECT balance FROM bank_transactions ORDER BY date DESC LIMIT 0, 1')[0]->balance;
		$bank_balance = ($bank_balance > 0 ? $bank_balance : 0);
		$cash = DB::select('SELECT SUM(amount) `total` FROM cash_transactions')[0]->total - DB::select("SELECT SUM(amount) `total` FROM bank_transactions WHERE app_type = 'CASHDEPOSIT'")[0]->total;
		$stock_value = $this->getStockValue();
		return $tab_assets + $bank_balance + $cash + $stock_value;
	}
	
	private function getLiabilities(){
		$tab_liabilities = DB::select('SELECT SUM(balance) `total` FROM users WHERE balance > 0')[0]->total;
		$bank_balance = ($t=BankTransaction::latest('date')->first())?$t->balance:0;
		$bank_balance = ($bank_balance < 0 ? -$bank_balance : 0);
		return $tab_liabilities + $bank_balance;
	}

	private function getBankBalance(){
		$bank_balance = ($t=BankTransaction::latest('date')->first())?$t->balance:0;
		return $bank_balance;
	}
	
	private function getCashOnHand(){
		$bank_balance = ($t=BankTransaction::latest('date')->first())?$t->balance:0;
		$bank_balance = $bank_balance > 0 ? $bank_balance : 0;
		$cash = DB::select('SELECT SUM(amount) `total` FROM cash_transactions')[0]->total - DB::select("SELECT SUM(amount) `total` FROM bank_transactions WHERE app_type = 'CASHDEPOSIT'")[0]->total;
		return $bank_balance + $cash;
	}
	
	private function getStockValue(){
		return $this->getStockValueByDate(time());
	}

	private function getStockValueByDate($time){
		$stock_volumes = DB::select(
			'SELECT t1.ingredient_id, t1.volume, t1.timestamp FROM stocktakes t1 WHERE t1.timestamp = (SELECT MAX(t2.timestamp) FROM stocktakes t2 WHERE t2.timestamp <= FROM_UNIXTIME(?) AND t2.ingredient_id = t1.ingredient_id)', array($time));
		$stock_value = 0;
		foreach($stock_volumes as $stock_volume){
			$purchases = DB::select('SELECT volume, cost, timestamp FROM purchases WHERE ingredient_id = ? AND timestamp < FROM_UNIXTIME(?) ORDER BY timestamp DESC', array($stock_volume->ingredient_id, $time));
			
			if(isset($purchases[0])){
				$j=0;
				while(isset($purchases[$j]) && $purchases[$j]->timestamp > $stock_volume->timestamp){
					//$stock_volume->volume += $purchases[$j]->volume;
					$stock_value += $purchases[$j]->cost;
					$j++;
				}
				
				$volume_iterated = 0;
				$i = 0;
				$value = 0;
				while(isset($purchases[$i]) && $volume_iterated <= $stock_volume->volume){
					$volume_iterated += $purchases[$i]->volume;
					$value += $purchases[$i]->cost;
					$i++;
				}
				$value += - ($volume_iterated - $stock_volume->volume) * ($purchases[$i-1]->cost / $purchases[$i-1]->volume);
				$stock_value += $value;
			}
		}
		
		return $stock_value;
	}

	private function getPayouts(){
		return -DB::select("SELECT SUM(amount) `total` FROM bank_transactions WHERE app_type = 'PAYOUT'")[0]->total
					-DB::select("SELECT SUM(amount) `total` FROM cash_transactions WHERE type = 'PAYOUT'")[0]->total;
	}
	
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
