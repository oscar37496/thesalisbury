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
								<th></th>
								<th>First Name</th>
								<th>Middle Name</th>
								<th>Last Name</th>
								<th>Activated</th>
								<th>Social</th>
								<th>Total Spent Last 7 days</th>
								<th>Total Spent</th>
								<th>Tab Balance</th>
							</tr>
						</thead>
						<tbody>
							@if( isset($users))
							@foreach ($users as $key => $user)
								<tr id="{{{ $key }}}">
									<td><img src="https://graph.facebook.com/v2.2/{{{ $key }}}/picture?height=48&amp;width=48" height="48" width="48" class="img-circle" alt="User Image"></td>
									<td>{{{ $user['first_name'] }}}</td>
									<td>{{{ $user['middle_name'] }}}</td>
									<td>{{{ $user['last_name'] }}}</td>
									<td>@if($user['is_activated'])
											Activated <button type="button" onclick="loadAjax({{{ $key }}}, '/account/admin/users/{{{ $key }}}/deactivate')">Deactivate</button>
										@else
											Deactivated <button type="button" onclick="loadAjax({{{ $key }}}, '/account/admin/users/{{{ $key }}}/activate')">Activate</button>
										@endif	
									</td>
									<td>
										@if($user['is_social'])
											Social <button type="button" onclick="loadAjax({{{ $key }}}, '/account/admin/users/{{{ $key }}}/remove-social')">Remove Social</button>
										@else
											Not Social <button type="button" onclick="loadAjax({{{ $key }}}, '/account/admin/users/{{{ $key }}}/make-social')">Make Social</button>
										@endif	
									</td>
									<td>{{{ money_format('%n', $user['total_spent_last_week'] / 100 ) }}}</td>
									<td>{{{ money_format('%n', $user['total_spent'] / 100 ) }}}</td>
									<td>{{{ money_format('%n', $user['balance'] / 100 ) }}}</td>
								</tr>
							@endforeach 
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th></th>
								<th>First Name</th>
								<th>Middle Name</th>
								<th>Last Name</th>
								<th>Activated</th>
								<th>Social</th>
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
<script src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js" type="text/javascript"></script>
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

