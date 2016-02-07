@include('account.helpers.helpers')

@section('head')
<!-- Morris chart -->
<link href="/css/morris/morris.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
function setCash(amount){
	var int_amount = Math.round(amount*100);
	$('#cash-amount').load('/account/sysadmin/operations/cash/'+int_amount);
	
};
function setCredit(id, amount){
	var int_amount = Math.round(amount*100);
	$('#credit-amount').load('/account/sysadmin/operations/credit/'+id+'/'+int_amount);
	
};
function setPayout(id, amount){
	var int_amount = Math.round(amount*100);
	$('#payout-status').load('/account/sysadmin/operations/payout/'+id+'/'+int_amount);
	
};
function setPurchase(desc, cost){
	var int_cost = Math.round(cost*100);
	$('#purchase-amount').load('/account/sysadmin/operations/purchase/'+int_cost+'/'+desc);
	
};
function setTag(user_id, tag_id){
	$('#new-tag').load('/account/sysadmin/operations/tag/'+user_id+'/'+tag_id);
	
};
</script>
@stop

@section('content')
<!-- Main content -->
<section class="content">

	<!-- Main row -->
	<div class="row">
		<div class="col-md-6">
            
			<!-- DONUT CHART -->
            <div class="box box-danger">
                <div class="box-header">
                	<i class="fa fa-dollar"></i>
                    <h3 class="box-title">Upload Bank Transactions</h3>
                </div>
                {{ Form::open(array('url' => '/account/sysadmin/transactions/upload', 'files' => true)) }}
                <div class="box-body">
                    {{ Form::file('csv') }}
                </div><!-- /.box-body -->
                <div class="box-footer">
                    {{ Form::submit('Upload') }}
                </div><!-- /.box-body -->
				{{ Form::close() }}
				<a href="/account/sysadmin/operations/csv">Download Transactions</a>
            </div><!-- /.box -->
            
            <div class="box box-danger">
                <div class="box-header">
                	<i class="fa fa-dollar"></i>
                    <h3 class="box-title">Reconcile Tills</h3>
                </div>
                <div class="box-body">
                    <p id="cash-amount">
                    	Current {{{ money_format('%n',($current = DB::select('SELECT SUM(amount) `total` FROM cash_transactions')[0]->total - DB::select("SELECT SUM(amount) `total` FROM bank_transactions WHERE app_type = 'CASHDEPOSIT'")[0]->total)/100) }}}
                    </p>
                    <p>New $<input type="text" id="text-cash-amount" placeholder="4000"/></p>
                </div><!-- /.box-body -->
                <div class="box-footer">
                	<input type="submit" value="Reconcile" onclick="setCash($('#text-cash-amount').val())" />
                </div><!-- /.box-body -->
            </div><!-- /.box -->
            
            <div class="box box-danger">
                <div class="box-header">
                	<i class="fa fa-dollar"></i>
                    <h3 class="box-title">Add Tab Cash Credit</h3>
                </div>
                <div class="box-body">
                    <p id="credit-amount"></p>
                    <p>Credit to add $<input type="text" id="credit-amount-text" placeholder="50"/>
                    <select name="Users" id="credit-user-dropdown">
						<option>None</option>
						@foreach($users as $user)
						<option value="{{{ $user-> id }}}">{{{ $user->first_name }}}{{{ ($user->middle_name ? ' '.$user->middle_name.' ' : ' ') }}}{{{ $user->last_name }}}</option>
						@endforeach
					</select>
                </div><!-- /.box-body -->
                <div class="box-footer">
                	<input type="submit" value="Add" onclick="setCredit($('#credit-user-dropdown').val(),$('#credit-amount-text').val())" />
                </div><!-- /.box-body -->
            </div><!-- /.box -->
            
        </div><!-- /.col (RIGHT) -->
		<div class="col-md-6">
            <div class="box box-danger">
                <div class="box-header">
                	<i class="fa fa-dollar"></i>
                    <h3 class="box-title">Add Purchase/Payout</h3>
                </div>
                <div class="box-body">
                    <p id="purchase-amount"></p>
                    <p>Description <input type="text" id="purchase-description-text" placeholder="Genuine Vodka O Vat"/></p>
					<p>Cost $ <input type="text" id="purchase-cost-text" placeholder="450"/></p>
                </div><!-- /.box-body -->
                <div class="box-footer">
                	<input type="submit" value="Add" onclick="setPurchase(encodeURIComponent($('#purchase-description-text').val()),$('#purchase-cost-text').val())" />
                </div><!-- /.box-body -->
            </div><!-- /.box -->
			
			
			<div class="box box-danger">
                <div class="box-header">
                	<i class="fa fa-credit-card"></i>
                    <h3 class="box-title">Add Tag to Account</h3>
                </div>
                <div class="box-body">
                    <p id="new-tag"></p>
                    <input type="text" id="tag-id-text" />
                    <select name="Users" id="tag-user-dropdown">
						<option>None</option>
						@foreach($users as $user)
						<option value="{{{ $user-> id }}}">{{{ $user->first_name }}}{{{ ($user->middle_name ? ' '.$user->middle_name.' ' : ' ') }}}{{{ $user->last_name }}}</option>
						@endforeach
					</select>
                </div><!-- /.box-body -->
                <div class="box-footer">
                	<input type="submit" value="Add" onclick="setTag($('#tag-user-dropdown').val(),$('#tag-id-text').val())" />
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col (LEFT) -->
		
		
        
	</div><!-- /.row (main row) -->

</section><!-- /.content -->
@stop

