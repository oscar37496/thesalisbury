@section('account-dropdown') <!-- User Account: style can be found in dropdown.less -->
<li class="dropdown user user-menu">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="glyphicon glyphicon-user"></i> <span>{{{ $user->first_name.' '.$user->last_name }}} <i class="caret"></i></span> </a>
	<ul class="dropdown-menu">
		<!-- User image -->
		<li class="user-header bg-light-blue">
			<img src="https://graph.facebook.com/v2.2/{{{ $id }}}/picture?height=90&width=90" class="img-circle" alt="User Image" />
			<p>
				{{{ $user->first_name.' '.$user->last_name }}}
				<small>Member since {{{ date( 'F Y', strtotime($user->date_created)) }}}</small>
			</p>
		</li>
		<!-- Menu Footer-->
		<li class="user-footer">
			<div class="pull-right">
				<a href="/auth/fb/deauth" class="btn btn-default btn-flat">Sign out</a>
			</div>
		</li>
	</ul>
</li>
@stop