<?php

class TransactionController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$transactions = Transaction::all();
		foreach($transactions as $transaction){
			$response[] = [
                'id' => $transaction->id,
                'sku_id' => $transaction->sku_id,
                'tag_id' => $transaction->tag_id,
                'user_id' => $transaction->user_id,
                'quantity' => $transaction->quantity,
                'price' => $transaction->price,
                'balance' => $transaction->balance,
                'terminal' => $transaction->terminal,
                'timestamp' => $transaction->timestamp
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
    $transaction = new Transaction;

    $transaction->sku_id = Input::get('sku_id');
    $transaction->tag_id = Input::get('tag_id');
	$transaction->user_id = Input::get('user_id');
	$transaction->quantity = Input::get('quantity');
    $transaction->price = Input::get('price');
    $transaction->terminal = Input::get('terminal');
	$transaction->timestamp = Input::get('timestamp');
	$transaction->save();

    return Response::json($transaction->jsonSerialize(), 200);
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
