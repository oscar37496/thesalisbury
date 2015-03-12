@include('account.helpers.helpers')

@section('head')
<!-- DATA TABLES -->
<link href="/css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
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
								<th>Amount</th>
								<th>Type</th>
								<th>Description</th>
								<th>Linked Item</th>
								<th>Set User</th>
								<th>Set Type</th>
								<th>Balance</th>
							</tr>
						</thead>
						<tbody>
							@if( isset($transactions))
							@foreach ($transactions as $transaction)
								<tr>
									<td>{{{ $transaction->timestamp }}}</td>
									<td>{{{ money_format('%n', $transaction -> amount / 100 ) }}}</td>
									<td>{{{ $transaction -> app_type }}}</td>
									<td>{{{ $transaction -> description }}}</td>
									<td id="{{{ $transaction->id }}}">
										@if('CASHDEPOSIT' !== $transaction->app_type )
											@if( 'PAYOUT' === $transaction->app_type )
												{{{ $transaction -> app_description }}}
											@elseif( isset($transaction -> user))
												{{{ $transaction -> user -> first_name . ' ' . $transaction -> user -> last_name }}} 
											@elseif (isset($transaction -> purchase))
												@foreach($transaction -> purchase as $purchase)
												{{{ $purchase -> volume . 'ml of ' . $purchase -> name .' on '.date('M j',strtotime($purchase -> timestamp)) }}} 
												@endforeach
											@else
												No Item Linked 
											@endif
										@endif</td>
									<td>@if(strcmp('CASHDEPOSIT', $transaction->type) != 0)
										<select name="Users" id="Users" onchange="linkTransaction({{{ $transaction->id }}}, this.value);">
									<option value="0">None</option>
									@foreach($users as $user)
									<option value="{{{ $user-> id }}}">{{{ $user->first_name }}}{{{ ($user->middle_name ? ' '.$user->middle_name.' ' : ' ') }}}{{{ $user->last_name }}}</option>
									@endforeach
									</select>
									@endif</td>
									<td>
										@if(strcmp('CASHDEPOSIT', $transaction->type) != 0)
										<button type="button" onclick="linkTransaction({{{ $transaction->id }}},'CASHDEPOSIT')">Deposit</button>
										<button type="button" onclick="linkTransaction({{{ $transaction->id }}},'PAYOUT')">Payout</button>
										@endif
									</td>
									<td>{{{ money_format('%n', $transaction->balance / 100 ) }}}</td>
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

<script type="text/javascript">
	function linkTransaction(transactionId, userId)
    {
        $.ajax({
               type: "GET",
               url: "/account/sysadmin/cashtransactions/" + transactionId + '/' + userId,
               success: function(result){
                 $("#ajax-message").html('<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>             <b>Success!</b> Transaction was assigned to '
                + result + '</div>');
                 $("#"+transactionId).html(result)
               }
             });
    };
    jQuery.extend( jQuery.fn.dataTableExt.oSort, {
	    "currency-pre": function ( a ) {
	        a = (a==="-") ? 0 : a.replace( /[^\d\-\.]/g, "" );
	        return parseFloat( a );
	    },
	 
	    "currency-asc": function ( a, b ) {
	        return a - b;
	    },
	 
	    "currency-desc": function ( a, b ) {
	        return b - a;
	    }
	} );
</script>

@stop

