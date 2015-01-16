<?php
class Tag extends Eloquent{
	
	public $timestamps = FALSE;
	public $incrementing = FALSE;
	
	public function transaction(){
		return $this->hasMany('Transaction', 'tag_id');
	}
	
	public function user(){
		return $this->belongsTo('User');
	}
	
}

/* End of File: app/models/Tag.php */