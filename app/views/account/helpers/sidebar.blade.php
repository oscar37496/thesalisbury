@section('sidebar')
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
	<!-- Sidebar user panel -->
	<div class="user-panel">
		<div class="pull-left image">
			<img src="https://graph.facebook.com/v2.2/{{{ $id }}}/picture?height=45&width=45" class="img-circle" alt="User Image" />
		</div>
		<div class="pull-left info">
			<p>
				Hello, {{{ $user->first_name }}}
			</p>

			<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
		</div>
	</div>
	<!-- sidebar menu: : style can be found in sidebar.less -->
	<ul class="sidebar-menu">
		<li @if(strcmp($location, 'Dashboard') == 0) class="active" @endif>
			<a href="/account/dashboard"> <i class="fa fa-dashboard"></i> <span>Dashboard</span> </a>
		</li>
		<li @if(strcmp($location, 'Transactions') == 0) class="active" @endif>
			<a href="/account/transactions"> <i class="fa fa-table"></i> <span>Transactions</span> <!--<small class="badge pull-right bg-green">new</small>--> </a>
		</li>
		{{--<li @if(strcmp($location, 'Statistics') == 0) class="active" @endif>
			<a href="/account/statistics"> <i class="fa fa-bar-chart-o"></i> <span>Statistics</span> <!--<small class="badge pull-right bg-green">new</small>--> </a>
		</li>--}}
		<li @if(strcmp($location, 'Cards') == 0) class="active" @endif>
			<a href="/account/cards"> <i class="fa fa-credit-card"></i> <span>Cards</span></a>
		</li>
		@if( $user->is_social || $user->is_admin)
		<li @if(strcmp($location, 'Friends') == 0) class="active" @endif>
			<a href="/account/friends"> <i class="fa fa-users"></i> <span>Friends</span></a>
		</li>
		@endif
		@if( $user->is_charles)
		<li @if(strcmp($location, 'Message Users') == 0) class="active" @endif>
			<a href="/account/charles/users"> <i class="fa fa-envelope"></i> <span>Message Users</span> <small class="badge pull-right bg-orange">Charles</small></a>
		</li>
		@endif
		@if( $user->is_admin)
		<li @if(strcmp($location, 'Admin Dashboard') == 0) class="active" @endif>
			<a href="/account/admin/dashboard"> <i class="fa fa-dashboard"></i> <span>Admin Dashboard</span> <small class="badge pull-right bg-green">Admin</small></a> 
		</li>
		<li @if(strcmp($location, 'Stocktake') == 0) class="active" @endif>
			<a href="/account/admin/stocktake"> <i class="fa fa-barcode"></i> <span>Stocktake</span> <small class="badge pull-right bg-green">Admin</small></a>
		</li>		
		<li class="treeview @if(strcmp($location, 'All Users') == 0 || strcmp($location, 'All Cards') == 0 || strcmp($location, 'All Transactions') == 0) active @endif">
            <a href="#">
                <i class="fa fa-users"></i>
                <span>User Management</span>
                <i class="fa pull-right @if(strcmp($location, 'All Users') == 0 || strcmp($location, 'All Cards') == 0 || strcmp($location, 'All Transactions') == 0) fa-angle-down @else fa-angle-left @endif"></i>
            </a>
            <ul class="treeview-menu" style="display: @if(strcmp($location, 'All Users') == 0 || strcmp($location, 'All Cards') == 0 || strcmp($location, 'All Transactions') == 0) block @else none @endif;">
            	<li @if(strcmp($location, 'All Users') == 0) class="active" @endif>
					<a href="/account/admin/users"> <i class="fa fa-users"></i> <span>Users</span> <small class="badge pull-right bg-green">Admin</small></a> 
				</li>
				<li @if(strcmp($location, 'All Cards') == 0) class="active" @endif>
					<a href="/account/admin/cards"> <i class="fa fa-credit-card"></i> <span>Cards</span> <small class="badge pull-right bg-green">Admin</small></a> 
				</li>
				<li @if(strcmp($location, 'All Transactions') == 0) class="active" @endif>
					<a href="/account/admin/transactions"> <i class="fa fa-table"></i> <span>Transactions</span> <small class="badge pull-right bg-green">Admin</small></a> 
				</li>
            </ul>
        </li>
		@endif
		@if( $user->is_sysadmin)
		<li @if(strcmp($location, 'SysAdmin Dashboard') == 0) class="active" @endif>
			<a href="/account/sysadmin/dashboard"> <i class="fa fa-dashboard"></i> <span>Dashboard</span> <small class="badge pull-right bg-red">SysAdmin</small></a>
		</li>
		<li @if(strcmp($location, 'Operations') == 0) class="active" @endif>
			<a href="/account/sysadmin/operations"> <i class="fa fa-dollar"></i> <span>Operations</span> <small class="badge pull-right bg-red">SysAdmin</small></a>
		</li>
		<li @if(strcmp($location, 'Purchases') == 0) class="active" @endif>
			<a href="/account/sysadmin/purchases"> <i class="fa fa-dollar"></i> <span>Purchases</span> <small class="badge pull-right bg-red">SysAdmin</small></a>
		</li>
		<li @if(strcmp($location, 'Bank Transactions') == 0) class="active" @endif>
			<a href="/account/sysadmin/transactions"> <i class="fa fa-dollar"></i> <span>Bank Transactions</span> <small class="badge pull-right bg-red">SysAdmin</small></a>
		</li>
		<li @if(strcmp($location, 'Cash Transactions') == 0) class="active" @endif>
			<a href="/account/sysadmin/cashtransactions"> <i class="fa fa-dollar"></i> <span>Cash Transactions</span> <small class="badge pull-right bg-red">SysAdmin</small></a>
		</li>
		@endif
	</ul>
</section>
<!-- /.sidebar -->
@stop
