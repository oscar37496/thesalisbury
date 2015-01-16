@include('account.helpers.helpers')

@section('head')
<!-- Morris chart -->
<link href="/css/morris/morris.css" rel="stylesheet" type="text/css" />
@stop

@section('content')
<!-- Main content -->
<section class="content">

	<!-- Main row -->
	<div class="row">
		<div class="col-xs-12">
            <!-- Line chart -->
            <div class="box box-primary">
                <div class="box-header">
                    <i class="fa fa-dollar"></i>
                    <h3 class="box-title">Account Balance</h3>
                </div>
                <div class="box-body">
                    <div id="account-balance" style="height: 300px;"></div>
                </div><!-- /.box-body-->
            </div><!-- /.box -->

        </div><!-- /.col (LEFT) -->
		<div class="col-md-6">
            <!-- Line chart -->
            <div class="box box-primary">
                <div class="box-header">
                    <i class="fa fa-dollar"></i>
                    <h3 class="box-title">Spending Habits</h3>
                </div>
                <div class="box-body">
                    <div id="spending-habits" style="height: 300px;"></div>
                </div><!-- /.box-body-->
            </div><!-- /.box -->

        </div><!-- /.col (LEFT) -->
        <div class="col-md-6">
            
			<!-- DONUT CHART -->
            <div class="box box-primary">
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
                    data: {{$account_balance}},
                    smooth: false,
                    xkey: 'time',
                    ykeys: ['balance'],
                    labels: ['Balance'],
                    lineColors: ['#3c8dbc'],
                    hideHover: 'auto'
                });
                
                // LINE CHART
                var line = new Morris.Line({
                    element: 'spending-habits',
                    resize: true,
                    data: {{$account_balance}},
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
                    data: {{ $sku_count }},
                    hideHover: 'auto'
                });

            });
        </script>
@stop
