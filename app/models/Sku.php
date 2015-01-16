<?php
class Sku extends Eloquent{

	public $timestamps = FALSE;
	
	public function transaction(){
		return $this->hasMany('Transaction');
	}
	
}

/* End of File: app/models/Sku.php */