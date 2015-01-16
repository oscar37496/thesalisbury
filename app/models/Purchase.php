<?php
class Purchase extends Eloquent{

	public $timestamps = FALSE;
	
	public function ingredient(){
		return $this->belongsTo('Ingredient', 'ingredient_id');
	}
	
	public function bankTransaction(){
		return $this->belongsTo('BankTransaction','bank_transaction_id');
	}
	
	public function cashTransaction(){
		return $this->belongsTo('CashTransaction','cash_transaction_id');
	}
}

/* End of File: app/models/Sku.php */