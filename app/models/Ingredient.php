<?php
class Ingredient extends Eloquent{

	public $timestamps = FALSE;
	
	public function stocktake(){
		return $this->hasMany('Stocktake');
	}
	
	public function purchase(){
		return $this->hasMany('Purchase');
	}
}

/* End of File: app/models/Sku.php */