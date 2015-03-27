@include('account.helpers.helpers')

@section('head')
<!-- DATA TABLES -->
<link href="/css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
<script>
function loadAjax(id)
{
$("#"+id).load('/account/admin/stocktake/'+id+'/'+$('#text'+id).val())
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
								<th>Name</th>
								<th>Current Volume (ml)</th>
								<th>New Volume (ml)</th>
								<th>Submit</th>
							</tr>
						</thead>
						<tbody>
							@if( isset($ingredients))
							@foreach ($ingredients as $ingredient)
								<tr>
									<td>{{{ $ingredient['name'] }}}</td>
									<td id="{{{$ingredient['id']}}}">
										@if( $isset($db_stocktake = DB::select('SELECT volume FROM stocktakes WHERE ingredient_id = ? ORDER BY timestamp DESC LIMIT 0, 1', array($ingredient['id']))
										{{{ $db_stocktake[0]->volume }}}</td>
										@endif
									<td><input type="text" id="text{{{$ingredient['id']}}}" /></td>
									<td><input type="submit" value="Submit" onclick="loadAjax({{{$ingredient['id']}}})" /></td>
								</tr>
							@endforeach 
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th>Name</th>
								<th>Current Volume (ml)</th>
								<th>New Volume (ml)</th>
								<th>Submit</th>
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
</script>
@stop

