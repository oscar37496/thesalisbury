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
								<th>Tag UID</th>
								<th>Tag Description</th>
								<th>Activated</th>
								<th>Transactions</th>
								<th>Drinks Bought</th>
								<th>Amount Spent</th>
							</tr>
						</thead>
						<tbody>
							@if( isset($tags))
							@foreach ($tags as $tag)
								<tr>
									@if( isset($tag->id))
									<td>{{{ $tag->id }}}</td>
									<td>{{{ $tag->description }}}</td>
									<td>@if( $tag->is_activated)
										Activated
										@else
										Deactivated
										@endif
									</td>
									@else
									<td></td>
									<td>Manual Transactions</td>
									<td></td>
									@endif
									<td>{{{ $tag->transaction_count or '0' }}}</td>
									<td>{{{ $tag->drink_count or '0' }}}</td>
									<td>@if(isset($tag->total)){{{ money_format('%n', $tag->total / 100 ) }}}@else 0 @endif</td>
								</tr>
								
							@endforeach 
							@endif
						</tbody>
						<!--<tfoot>
							<tr>
								<th>Date</th>
								<th>Tag</th>
								<th>Item</th>
								<th>Price</th>
								<th>Quantity</th>
								<th>Total</th>
								<th>Balance</th>
							</tr>
						</tfoot>--> 
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
</script>
@stop

