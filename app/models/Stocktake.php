<?php
class Stocktake extends Eloquent{

	public $timestamps = FALSE;
	
	public function ingredient(){
		return $this->belongsTo('Ingredient', 'ingredient_id');
	}
}

/* End of File: app/models/Sku.php */