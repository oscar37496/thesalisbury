@include('account.helpers.helpers')

@section('head')
<!-- Morris chart -->
<link href="/css/morris/morris.css" rel="stylesheet" type="text/css" />
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
					<h3> {{{ $user->transaction->count() }}} </h3>
					<p>
						Transactions
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
					<h3> {{{ substr(money_format('%.0n', $user->balance/100), 0, -3) }}}<sup style="font-size: 20px">{{{ substr(number_format(abs($user->balance/100), 2),-3) }}}</sup>
					</h3>
					<p>
						Account Balance
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
					<h3> {{{ substr(number_format($drink_count, 1), 0, -2) }}}<sup style="font-size: 20px">{{{ substr(number_format($drink_count, 1), -2) }}}</sup>
					</h3>
					<p>
						Standard Drinks Bought
					</p>
				</div>
				<div class="icon">
					<i class="ion ion-beer"></i>
				</div>
				<a href="/account/statistics" class="small-box-footer"> More info <i class="fa fa-arrow-circle-right"></i> </a>
			</div>
		</div><!-- ./col -->
		@if($user->is_social)
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-yellow">
				<div class="inner">
					<h3> {{{ $friend_count }}} </h3>
					<p>
						Friends with Tabs
					</p>
				</div>
				<div class="icon">
					<i class="ion ion-ios-people"></i>
				</div>
				<a href="/account/friends" class="small-box-footer"> More info <i class="fa fa-arrow-circle-right"></i> </a>
			</div>
		</div><!-- ./col -->
		@endif
	</div><!-- /.row -->

	<!-- Main row -->
	<div class="row">
		<div class="col-md-6">
            <!-- Line chart -->
            <div class="box box-primary">
                <div class="box-header">
                    <i class="fa fa-dollar"></i>
                    <h3 class="box-title">Account Balance</h3>
                </div>
                <div class="box-body">
                    <div class="chart" id="account-balance" style="height: 300px;"></div>
                </div><!-- /.box-body-->
            </div><!-- /.box -->

        </div><!-- /.col (LEFT) -->
        <div class="col-md-6">
            
			<!-- DONUT CHART -->
            <div class="box box-danger">
                <div class="box-header">
                	<i class="fa fa-beer"></i>
                    <h3 class="box-title">Drinks Purchased</h3>
                </div>
                <div class="box-body chart-responsive">
                    <div class="chart" id="drinks-purchased" style="height: 300px; position: relative;"></div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->

        </div><!-- /.col (RIGHT) -->
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
                
                // LINE CHART
                var line = new Morris.Line({
                    element: 'account-balance',
                    resize: true,
                    data: {{$account_balance or '[]'}},
                    smooth: false,
                    xkey: 'time',
                    ykeys: ['balance'],
                    labels: ['Balance'],
                    lineColors: ['#3c8dbc'],
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
