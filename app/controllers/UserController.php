<?php

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = User::where('is_activated', 1)->get();
		
		foreach($users as $user){
			$response[] = [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,
                'last_name' => $user->last_name,
                'balance' => $user->balance,
                'gender' => $user->gender,
                'is_card_only' => ($user->is_card_only == 0 ? FALSE : TRUE),
				'is_admin' => ($user->is_admin == 0 ? FALSE : TRUE),
				'is_activated' => ($user->is_activated == 0 ? FALSE : TRUE),
				'date_created' => $user->date_created
            ];
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
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = User::find($id);
		
		$response[] = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'middle_name' => $user->middle_name,
            'last_name' => $user->last_name,
            'balance' => $user->balance,
            'gender' => $user->gender,
            'is_card_only' => ($user->is_card_only == 0 ? FALSE : TRUE),
			'is_admin' => ($user->is_admin == 0 ? FALSE : TRUE),
			'is_activated' => ($user->is_activated == 0 ? FALSE : TRUE),
			'date_created' => $user->date_created
		];
		
		return Response::json($response, 200);
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
