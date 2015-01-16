<?php
class Transaction extends Eloquent{

	public $timestamps = FALSE;
	
	public function sku(){
		return $this->belongsTo('Sku', 'sku_id');
	}
	
	public function tag(){
		return $this->belongsTo('Tag', 'tag_id', 'id');
	}
	
	public function user(){
		return $this->belongsTo('User', 'user_id');
	}
}

/* End of File: app/models/Transaction.php */