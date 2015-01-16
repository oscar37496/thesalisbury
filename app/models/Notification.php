<?php
class Notification extends Eloquent{

	public $timestamps = FALSE;
	
	public function user(){
		return $this->belongsTo('User', 'user_id');
	}
}

/* End of File: app/models/Transaction.php */