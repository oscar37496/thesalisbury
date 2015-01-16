<?php

class TagController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$response = array();
		$tags = Tag::all();
		foreach($tags as $tag){
			if($tag->is_activated != 0 ){
				$response[] = [
	                'id' => $tag->id,
	                'description' => $tag->description,
	                'user_id' => $tag->user_id,
	            ];
			}
		}
		return Response::json($response, 200);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$tag = new Tag;

		$tag->user_id = Input::get('user_id');
		$tag->description = Input::get('description');
		$tag->is_activated = TRUE;

	    $tag->save();
	
	    return Response::json($tag->jsonSerialize(), 200);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
