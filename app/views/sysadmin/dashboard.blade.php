@include('account.helpers.helpers')

@section('head')
<!-- Morris chart -->
<link href="/css/morris/morris.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
function setCash($amount){
	$int_amount = Math.round($amount*100);
	$('#cash-amount').load('/account/sysadmin/dashboard/cash/'+$int_amount);
	
};
function setCredit($id, $amount){
	$int_amount = Math.round($amount*100);
	$('#credit-amount').load('/account/sysadmin/dashboard/credit/'+$id+'/'+$int_amount);
	
};
</script>
@stop

@section('content')
<!-- Main content -->
<section class="content">

	<!-- Small boxes (Stat box) -->
	<div class="row">
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-aqua">
				<div class="inner">
					<h3> {{{ substr(money_format('%n',$assets/100),0,-3) }}}<sup style="font-size: 20px">{{{ substr(number_format(abs($assets/100), 2),-3) }}}</sup>
					</h3>
					<p>
						Assets
					</p>
				</div>
				<div class="icon">
					<i class="ion ion-bag"></i>
				</div>
				<a href="/account/transactions" class="small-box-footer"> More info <i class="fa fa-arrow-circle-right"></i> </a>
			</div>
		</div><!-- ./col -->
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-green">
				<div class="inner">
					<h3> {{{ substr(money_format('%n',$liabilities/100),0,-3) }}}<sup style="font-size: 20px">{{{ substr(number_format(abs($liabilities/100), 2),-3) }}}</sup>
					</h3>
					<p>
						Liabilities
					</p>
				</div>
				<div class="icon">
					<i class="ion ion-stats-bars"></i>
				</div>
				<a href="/account/statistics" class="small-box-footer"> More info <i class="fa fa-arrow-circle-right"></i> </a>
			</div>
		</div><!-- ./col -->
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-red">
				<div class="inner">
					<h3> {{{ substr(money_format('%n',$cash_on_hand/100),0,-3) }}}<sup style="font-size: 20px">{{{ substr(number_format(abs($cash_on_hand/100), 2),-3) }}}</sup>
					</h3>
					<p>
						Cash On Hand
					</p>
				</div>
				<div class="icon">
					<i class="ion ion-card"></i>
				</div>
				<a href="/account/cards" class="small-box-footer"> More info <i class="fa fa-arrow-circle-right"></i> </a>
			</div>
		</div><!-- ./col -->
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-yellow">
				<div class="inner">
					<h3> {{{ substr(money_format('%n',($stock_value)/100),0,-3) }}}<sup style="font-size: 20px">{{{ substr(number_format(abs(($stock_value)/100), 2),-3) }}}</sup>
					</h3>
					<p>
						Value of Stock
					</p>
				</div>
				<div class="icon">
					<i class="ion ion-ios-people"></i>
				</div>
				<a href="/account/friends" class="small-box-footer"> More info <i class="fa fa-arrow-circle-right"></i> </a>
			</div>
		</div><!-- ./col -->
	</div><!-- /.row -->
	
	<div class="row">
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-blue">
				<div class="inner">
					<h3>{{{ substr(money_format('%n',$net_tabs/100),0,-3) }}}<sup style="font-size: 20px">{{{ substr(number_format(abs($net_tabs/100), 2),-3) }}}</sup>
					</h3>
					<p>
						Net Tabs
					</p>
				</div>
				<div class="icon">
					<i class="ion ion-bag"></i>
				</div>
				<a href="/account/transactions" class="small-box-footer"> More info <i class="fa fa-arrow-circle-right"></i> </a>
			</div>
		</div><!-- ./col -->
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-purple">
				<div class="inner">
					<h3>{{{ substr(money_format('%n',$equity/100),0,-3) }}}<sup style="font-size: 20px">{{{ substr(number_format(abs($equity/100), 2),-3) }}}</sup>
					</h3>
					<p>
						Equity
					</p>
				</div>
				<div class="icon">
					<i class="ion ion-stats-bars"></i>
				</div>
				<a href="/account/statistics" class="small-box-footer"> More info <i class="fa fa-arrow-circle-right"></i> </a>
			</div>
		</div><!-- ./col -->
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-teal">
				<div class="inner">
					<h3> {{{ substr(money_format('%n',$payout/7/100),0,-3) }}}<sup style="font-size: 20px">{{{ substr(number_format(abs($payout/7/100), 2),-3) }}}</sup>
					</h3>
					<p>
						Payouts Per Person to Date
					</p>
				</div>
				<div class="icon">
					<i class="ion ion-card"></i>
				</div>
				<a href="/account/cards" class="small-box-footer"> More info <i class="fa fa-arrow-circle-right"></i> </a>
			</div>
		</div><!-- ./col -->
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-maroon">
				<div class="inner">
					<h3> {{{ substr(money_format('%n',($takings_to_date)/100),0,-3) }}}<sup style="font-size: 20px">{{{ substr(number_format(abs(($takings_to_date)/100), 2),-3) }}}</sup>
					</h3>
					<p>
						Profit to Date
					</p>
				</div>
				<div class="icon">
					<i class="ion ion-ios-people"></i>
				</div>
				<a href="/account/friends" class="small-box-footer"> More info <i class="fa fa-arrow-circle-right"></i> </a>
			</div>
		</div><!-- ./col -->
	</div><!-- /.row -->

	<div class="row">
        <div class="col-md-12">
            
            <!-- Line chart -->
            <div class="box box-primary">
                <div class="box-header">
                    <i class="fa fa-dollar"></i>
                    <h3 class="box-title">Profit to Date</h3>
                </div>
                <div class="box-body">
                    <div id="profit-timeline" style="height: 300px;"></div>
                </div><!-- /.box-body-->
            </div><!-- /.box -->
            
			

        </div><!-- /.col (RIGHT) -->
	</div><!-- /.row (main row) -->

	<!-- Main row -->
	<div class="row">
		<div class="col-md-6">
            
			
		
            <!-- Line chart -->
            <div class="box box-primary">
                <div class="box-header">
                    <i class="fa fa-dollar"></i>
                    <h3 class="box-title">Bank Balance</h3>
                </div>
                <div class="box-body">
                    <div id="bank-balance" style="height: 300px;"></div>
                </div><!-- /.box-body-->
            </div><!-- /.box -->

        </div><!-- /.col (LEFT) -->
        <div class="col-md-6">
        <!-- DONUT CHART -->
            <div class="box box-danger">
                <div class="box-header">
                	<i class="fa fa-beer"></i>
                    <h3 class="box-title">Products Sold</h3>
                </div>
                <div class="box-body chart-responsive">
                    <div class="chart" id="drinks-purchased" style="height: 300px; position: relative;"></div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
        
	</div><!-- /.row (main row) -->
	
	

</section><!-- /.content -->
@stop

@section('foot')
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="/js/plugins/morris/morris.min.js" type="text/javascript"></script>
<!-- page script -->
        <script type="text/javascript">
            $(function() {
                "use strict";
                
                // BANK CHART
                var line = new Morris.Line({
                    element: 'bank-balance',
                    resize: true,
                    data: {{$bank_timeline or '[]'}},
                    smooth: false,
                    xkey: 'time',
                    ykeys: ['balance'],
                    labels: ['Balance'],
                    lineColors: ['#3c8dbc'],
                    hideHover: 'auto'
                });
                
                // PROFIT CHART
                var profitline = new Morris.Line({
                    element: 'profit-timeline',
                    resize: true,
                    data: {{ $profit_timeline or '[]'}},
                    smooth: false,
                    xkey: 'time',
                    ykeys: ['assets', 'liabilities', 'payouts', 'profit'],
                    labels: ['Assets', 'Liabilities', 'Payouts to Date', 'Profit to Date'],
                    lineColors: ['#3c8dbc',"#f56954", "#00a65a",'#f39c12'],
                    hideHover: 'auto'
                });

                //DONUT CHART
                var donut = new Morris.Donut({
                    element: 'drinks-purchased',
                    resize: true,
                    colors: ["#3c8dbc", "#f56954", "#00a65a"],
                    data: {{ $sku_count or '[]'}},
                    hideHover: 'auto'
                });

            });
        </script>
@stop
