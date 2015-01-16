@section('notifications')
@if($user->is_admin)
@if( isset($notifications) )
<!-- Notifications: style can be found in dropdown.less -->
<li class="dropdown messages-menu">
	<a href="#" id="new-user-notification-button" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-user"></i> @if(count($notifications) > 0)<span id="notification-count" class="label label-danger">{{{ count($notifications) }}}</span>@endif</a>
	<ul class="dropdown-menu" id="new-user-notifications">
		<li class="header">
			{{{ count($notifications) }}} new users pending approval
		</li>
		<li>
			<!-- inner menu: contains the actual data -->
			<ul class="menu">
				@foreach($notifications as $notification)				
				<li id="{{{ $notification['id'] }}}">
					<a href="#" onclick="loadNotification({{{ $notification['id'] }}}, '/account/admin/notifications/{{{ $notification['id'] }}}/activate')"> 
						<div class="pull-left">
							<img src="https://graph.facebook.com/v2.2/{{{ $notification->user->id }}}/picture?height=45&amp;width=45" class="img-circle" alt="User Image">
						</div> 
						<h4>
                            {{{ $notification->user->first_name . ' ' . $notification->user->last_name }}}
                            <small><i class="ion ion-person-add"></i> </small>
                        </h4>
                        <p>Click to activate this user</p>
					</a>
				</li>
				@endforeach
			</ul>
		</li>
		<li class="footer">
			<a href="#" onclick="loadNotification('new-user-notifications','/account/admin/notifications/0/clear')">Clear all notifications</a>
		</li>
	</ul>
</li>
<!-- Tasks: style can be found in dropdown.less -->
@endif
@endif
@stop