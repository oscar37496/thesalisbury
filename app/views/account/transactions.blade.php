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
					<table id="transactions" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Date</th>
								<th>Tag</th>
								<th>Item</th>
								<th>Price</th>
								<th>Quantity</th>
								<th>Total</th>
								<th>Balance</th>
							</tr>
						</thead>
						<tbody>
							@if( isset($transactions))
							@foreach ($transactions as $transaction)
								<tr>
									<td>{{{ $transaction->timestamp }}}</td>
									<td>
										@if( $transaction->tag_description == NULL)
										No Card Presented
										@else
										{{{ $transaction->tag_description }}}
										@endif
									</td>
									<td>{{{ $transaction->sku_description }}}</td>
									<td>{{{ (money_format('%n', $transaction->price / 100 )) }}}</td>
									<td>{{{ $transaction->quantity }}}</td>
									<td>{{{ money_format('%n', $transaction->total / 100 ) }}}</td>
									<td>{{{ money_format('%n', $transaction->balance / 100 ) }}}</td>
								</tr>
							@endforeach 
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th>Date</th>
								<th>Tag</th>
								<th>Item</th>
								<th>Price</th>
								<th>Quantity</th>
								<th>Total</th>
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
		$("#transactions").dataTable({
			"order": [ 0, 'asc' ]
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

