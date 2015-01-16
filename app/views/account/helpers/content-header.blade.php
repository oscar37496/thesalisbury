@section('content-header') 
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1> {{{ $location }}} <small>{{{ $description }}}</small></h1>
	<ol class="breadcrumb">
		<li>
			<a href="/account/dashboard"><i class="fa fa-dashboard"></i> Account</a>
		</li>
		<li class="active">
			{{{ $location }}}
		</li>
	</ol>
</section>
@stop