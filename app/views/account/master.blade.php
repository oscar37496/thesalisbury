<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>@yield('title')</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	
	<link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed.png" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	
	<meta property="og:image" content="/apple-touch-icon-precomposed.png"/>
	<meta property="fb:app_id" content="737486179667936" />
	<meta property="og:description" content="The Salisbury is the premier student bar at Sydney University. Located and St Paul's College in Camperdown, it has been run by the students of the college since it was established in 1970." />
	
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<!-- Ionicons -->
	<link href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
	<script>
	function loadNotification(id, url)
	{
		$("#"+id).load(url, function(){
			if(id == 'new-user-notifications'){
				$('#notification-count').parent().html('<i class=\"fa fa-user\"></i>');
			}else{
				var count = $('#notification-count').html() - 1;
				if (count >= 1){
					$('#notification-count').html(count--);
				} else {
					$('#notification-count').parent().html('<i class=\"fa fa-user\"></i>');
				}
			}
		})
	}
	</script>
	@yield('head')
	<!-- Theme style -->
	<link href="/css/AdminLTE.css" rel="stylesheet" type="text/css" />

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
	  ga('create', 'UA-48868606-2', 'auto');
	  ga('require', 'linkid', 'linkid.js');
	  ga('send', 'pageview');
	
	</script>
	
</head>
<body class="skin-black">
	
	<!-- header logo: style can be found in header.less -->
	<header class="header">
		<a href="/" class="logo"> <!-- Add the class icon to your logo image or logo icon to add the margining --> Salisbury </a>
		<!-- Header Navbar: style can be found in header.less -->
		<nav class="navbar navbar-static-top" role="navigation">
			<!-- Sidebar toggle button-->
			<a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
			<div class="navbar-right">
				<ul class="nav navbar-nav">
					<!-- Messages: style can be found in dropdown.less-->
					
					@yield('notifications')
					
					@yield('account-dropdown')

				</ul>
			</div>
		</nav>
	</header>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<!-- Left side column. contains the logo and sidebar -->
		<aside class="left-side sidebar-offcanvas">
			@yield('sidebar')
		</aside>

		<!-- Right side column. Contains the navbar and content of the page -->
		<aside class="right-side">
			@yield('content-header')
			@yield('content')
		</aside><!-- /.right-side -->
	</div><!-- ./wrapper -->

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js" type="text/javascript"></script>
	<!-- AdminLTE App -->
	<script src="/js/AdminLTE/app.js" type="text/javascript"></script>
	@yield('foot')
</body>
</html>
