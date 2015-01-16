@if( !$clear )
<a href="#"> 
	<div class="pull-left">
		<img src="https://graph.facebook.com/v2.2/{{{ $notification->user->id }}}/picture?height=45&amp;width=45" class="img-circle" alt="User Image">
	</div> 
	<h4>
        {{{ $notification->user->first_name . ' ' . $notification->user->last_name }}}
        <small><i class="ion ion-person-add"></i> </small>
    </h4>
    <p><span class="label label-success">User Activated</span></p>
</a>
@else
<li class="header">
	{{{ 0 }}} new users pending approval
</li>
<li>
	<!-- inner menu: contains the actual data -->
	<ul class="menu">

	</ul>
</li>
<li class="footer">
	<a href="#" onclick="loadNotification('new-user-notifications','/account/admin/notifications/0/clear')">Clear all notifications</a>
</li>
@endif