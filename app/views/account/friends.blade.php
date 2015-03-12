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
								<th></th>
								<th>First Name</th>
								<th>Last Name</th>
								<th>Total Spent Last 7 days</th>
								<th>Total Spent</th>
								<th>Tab Balance</th>
							</tr>
						</thead>
						<tbody>
							@if( isset($friends))
							@foreach ($friends as $key => $friend)
								<tr>
									<td><img src="https://graph.facebook.com/v2.2/{{{ $key }}}/picture?height=48&amp;width=48"  height="48" width="48" class="img-circle" alt="User Image"></td>
									<td>{{{ $friend['first_name'] }}}</td>
									<td>{{{ $friend['last_name'] }}}</td>
									<td>{{{ money_format('%n', $friend['total_spent_last_week'] / 100 ) }}}</td>
									<td>{{{ money_format('%n', $friend['total_spent'] / 100 ) }}}</td>
									<td>{{{ money_format('%n', $friend['balance'] / 100 ) }}}</td>
								</tr>
							@endforeach 
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th></th>
								<th>First Name</th>
								<th>Last Name</th>
								<th>Total Spent Last 7 days</th>
								<th>Total Spent</th>
								<th>Tab Balance</th>
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

