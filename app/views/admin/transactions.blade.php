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
			<div class="box">
				<div class="box-body table-responsive">
					<table id="example1" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>User</th>
								<th>Date</th>
								<th>Tag ID</th>
								<th>Tag</th>
								<th>Item</th>
								<th>Price</th>
								<th>Quantity</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
							@if( isset($transactions))
							@foreach ($transactions as $transaction)
								<tr>
									<td>{{{ $transaction['user']['first_name'].
										' '.$transaction['user']['middle_name'].
										(isset($transaction['user']['middle_name'])?' ':'').
										$transaction['user']['last_name'] }}}</td>
									<td>{{{ $transaction['timestamp'] }}}</td>
									<td>{{{ $transaction['tag']['id'] }}}</td>
									<td>
										@if( $transaction['tag']['id'] == NULL)
										No Card Presented
										@else
										{{{ $transaction['tag']['description'] }}}
										@endif
									</td>
									<td>@if( $transaction['sku_id'] == 0)
										Credit Added
										@else
										{{{ $transaction['sku']['description'] }}}
										@endif
									</td>
									<td>{{{ (money_format('%n', $transaction['price'] / 100 )) }}}</td>
									<td>{{{ $transaction['quantity'] }}}</td>
									<td>{{{ money_format('%n', $transaction['quantity'] * $transaction['price'] / 100 ) }}}</td>
								</tr>
							@endforeach 
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th>User</th>
								<th>Date</th>
								<th>Tag ID</th>
								<th>Tag</th>
								<th>Item</th>
								<th>Price</th>
								<th>Quantity</th>
								<th>Total</th>
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
		$("#example1").dataTable();
		$('#example2').dataTable({
			"bPaginate" : true,
			"bLengthChange" : false,
			"bFilter" : false,
			"bSort" : true,
			"bInfo" : true,
			"bAutoWidth" : false
		});
	});
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

