<?php

class HomeController extends BaseController{
	
	protected $layout = 'landing.master';
	
	public function getLanding(){
		$this->layout->content = View::make('landing.master');
	}
	
}
