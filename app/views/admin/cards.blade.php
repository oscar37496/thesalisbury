@include('account.helpers.helpers')

@section('head')
<!-- DATA TABLES -->
<link href="/css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />

<script>
function loadAjax(id, url)
{
$("#"+id).load(url)
}
</script>
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
								<th>Owner</th>
								<th>Tag UID</th>
								<th>Tag Description</th>
								<th>Activated</th>
								<th>Transactions</th>
								<th>Amount Spent</th>
							</tr>
						</thead>
						<tbody>
							@if( isset($tags))
							@foreach ($tags as $tag)
								<tr @if( isset($tag['id'])) id="{{{ $tag['id'] }}}" @endif>
									
									@if( isset($tag['id']))
									<td>{{{ $tag['user']['first_name'].
										' '.$tag['user']['middle_name'].
										(isset($tag['user']['middle_name'])?' ':'').
										$tag['user']['last_name'] }}}</td>
									<td>{{{ $tag['id'] }}}</td>
									<td>{{{ $tag['description'] }}}</td>
									<td>
										@if( $tag['is_activated'])
										Active <button type="button" onclick="loadAjax('{{{ $tag['id'] }}}', '/account/admin/cards/{{{ $tag['id'] }}}/deactivate')">Deactivate</button>
										@else
										Deactivated <button type="button" onclick="loadAjax('{{{ $tag['id'] }}}', '/account/admin/cards/{{{ $tag['id'] }}}/activate')">Activate</button>
										@endif
									</td>
									@else
									<td></td>
									<td></td>
									<td>Manual Transactions</td>
									<td></td>
									@endif
									<td>{{{ $tag['count'] }}}</td>
									<td>{{{ money_format('%n', $tag['total'] / 100 ) }}}</td>
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

