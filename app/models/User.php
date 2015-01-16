<?php
class User extends Eloquent {

	public $timestamps = FALSE;
	
	public function tag(){
		return $this->hasMany('Tag');
	}
		
	public function transaction(){
		return $this->hasMany('Transaction');
	}
	
	public function notification(){
		return $this->hasOne('Notification');
	}
	
	public function bankTransaction(){
		return $this->hasMany('BankTransaction');
	}
	
	public function cashTransaction(){
		return $this->hasMany('CashTransaction');
	}

}

/* End of File: app/models/User.php */