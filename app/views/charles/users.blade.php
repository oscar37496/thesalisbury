@include('account.helpers.helpers')

@section('head')
<!-- DATA TABLES -->
<link href="/css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />


<script type="text/javascript">
    function select_all(obj) {
        var text_val=eval(obj);
        text_val.focus();
        text_val.select();
        if (!document.all) return; // IE only
        r = text_val.createTextRange();
        r.execCommand('copy');
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
								<th>Last Message Sent</th>
								<th>Send Message</th>
								<th>Total Spent Last 7 days</th>
								<th>Total Spent</th>
								<th>Tab Balance</th>
							</tr>
						</thead>
						<tbody>
							@if( isset($users))
							@foreach ($users as $key => $user)
								<tr>
									<td><img src="https://graph.facebook.com/v2.2/{{{ $key }}}/picture?height=48&amp;width=48" height="48" width="48" class="img-circle" alt="User Image"></td>
									<td>{{{ $user['first_name'] }}}</td>
									<td>{{{ $user['middle_name'] }}}</td>
									<td>{{{ $user['last_name'] }}}</td>
									<td id="{{{ $key }}}">{{{ $user['last_messaged'] }}} </td>
									<td>
										<textarea style="display: none" id="{{{ $key }}}-text"onclick="select_all(this)">Hey mate, 
Your final tab balance is {{{ money_format('%n', $user['balance'] / 100 ) }}}
This will need to be paid as soon as possible.
This can be paid either by:
 
1. Bank transfer
The Salisbury 
BSB: 082-372 
Account: 55-347-4724 
Reference: Your name

2. In cash to ROOM XX T-RAD

Note: Your tab debt above will need to be paid before 5pm Wednesday, for you to be ticked off the list of outstanding debts we will give to the bouncers.
Thanks,
Salisbury Syndicate</textarea><button class="copy-button" type="button" data-clipboard-target="{{{ $key }}}-text" onclick="messageUser({{{ $key }}})">Message</button>	
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
								<th>Message</th>
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
<script>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '737486179667936',
          xfbml      : true,
          version    : 'v2.1'
        });
      };

      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "//connect.facebook.net/en_US/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));
    </script>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/zeroclipboard/2.2.0/ZeroClipboard.min.js"></script>

<script type="text/javascript">

var client = new ZeroClipboard($(".copy-button"));
function messageUser(id)
{

    FB.getLoginStatus(function(response) {
        if(response.status == 'connected')
        {
            FB.ui({
			  method: 'send',
			  link: 'http://thesalisbury.com.au/',
			  to: parseInt(id),
			}, updateLastMessaged(id)
			);
        }
        else if(response.status =='not_authorized'){
            FB.login(function(response) {
			   if (response.authResponse) {
			     FB.ui({
						  method: 'send',
						  link: 'http://thesalisbury.com.au/',
						  to: parseInt(id),
						}, updateLastMessaged(id)
						);	
			   } 
			 }, {scope: 'user_friends'});
        }
        else{
            FB.login(function(response) {
			   if (response.authResponse) {
			     FB.ui({
						  method: 'send',
						  link: 'http://thesalisbury.com.au/',
						  to: parseInt(id),
						}, updateLastMessaged(id)
						);	
			   } 
			 }, {scope: 'user_friends'});
        }
	})
	
};

function updateLastMessaged(id){
	$('#'+id).load('/account/charles/users/'+id);
	
};
</script>

@stop

