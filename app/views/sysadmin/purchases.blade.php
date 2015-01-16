@include('account.helpers.helpers')

@section('head')
<!-- DATA TABLES -->
<link href="/css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
	function linkBank(id, transactionId)
    {
        $('#'+id).load('/account/sysadmin/purchases/bank/'+id+'/'+transactionId);
    };
    function linkCash(id){
    	$('#'+id).load('/account/sysadmin/purchases/cash/'+id);
    }
</script>
@stop

@section('content')
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div id="ajax-message"></div>
			<div class="box">
				<div class="box-body table-responsive">
					<table id="example1" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Date</th>
								<th>Description</th>
								<th>Volume (ml)</th>
								<th>Cost</th>
								<th>Linked Item</th>
								<th>Bank Transaction</th>
								<th>Make Cash Transaction</th>
							</tr>
						</thead>
						<tbody>
							@if( isset($purchases))
							@foreach ($purchases as $purchase)
								<tr>
									<td>{{{ $purchase->timestamp }}}</td>
									<td>{{{ $purchase->ingredient->name }}}</td>
									<td>{{{ $purchase -> volume }}}</td>
									<td>{{{ money_format('%n', $purchase->cost / 100 ) }}}</td>
									<td id="{{{ $purchase->id }}}">
										@if( $purchase -> bank_transaction_id != NULL)
										{{{ $purchase->bank_transaction->date.' '.money_format('%n', $purchase->bank_transaction->amount / 100 ).' '.$purchase->bank_transaction->description }}} 
										@elseif ($purchase -> cash_transaction_id != NULL)
										{{{ $purchase->cash_transaction->date.' '.money_format('%n', $purchase->cash_transaction->amount / 100 ).' '.$purchase->cash_transaction->description }}}
										@else
										No Transaction Linked 
										@endif
									</td>
									<td><select name="Bank Transactions" id="transactions" onchange="linkBank({{{ $purchase->id }}}, this.value);">
									@foreach($bank_transactions as $transaction)
									<option value="{{{ $transaction-> id }}}">{{{ $transaction->date.' '.money_format('%n', $transaction->amount / 100 ).' '.$transaction->description }}}</option>
									@endforeach
									</select></td>
									<td><button type="button" onclick="linkCash({{{ $purchase->id }}})">Link Cash</button></td>
								</tr>
							@endforeach 
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th>Date</th>
								<th>Amount</th>
								<th>Type</th>
								<th>Description</th>
								<th>User</th>
								<th>Set Type</th>
								<th>Balance</th>
							</tr>
						</tfoot>
					</table>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
	</div>

</section><!-- /.content -->
@stop

@section('foot')
<!-- DATA TABES SCRIPT -->
<script src="../../js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="../../js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<!-- page script -->
<script type="text/javascript">
	$(function() {
		$("#example1").dataTable({
			"aoColumnDefs": [
		      { "bSearchable": false, "aTargets": [ 5 ] }
		    ]
		});
		$('#example2').dataTable({
			"bPaginate" : true,
			"bLengthChange" : false,
			"bFilter" : false,
			"bSort" : true,
			"bInfo" : true,
			"bAutoWidth" : false
		});
	}); 
</script>



@stop

