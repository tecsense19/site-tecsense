<!DOCTYPE html>

<html>

<head>

	<!-- Basic Page Info -->

	<meta charset="utf-8">

	<!-- <title>DeskApp - Bootstrap Admin Dashboard HTML Template</title> -->
	<title>Tec-Sense Admin Dashboard</title>



	<!-- Site favicon -->

	<link rel="apple-touch-icon" sizes="180x180" href="{{ URL::asset('public/vendors/images/apple-touch-icon.png') }}">

	<link rel="icon" type="image/png" sizes="32x32" href="{{ URL::asset('public/vendors/images/tec-favicon.png') }}">

	<link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('public/vendors/images/tec-favicon.png') }}">



	<!-- Mobile Specific Metas -->

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">



	<!-- Google Font -->

	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

	<!-- CSS -->

	<link rel="stylesheet" type="text/css" href="{{ URL::asset('public/vendors/styles/core.css') }}">

	<link rel="stylesheet" type="text/css" href="{{ URL::asset('public/vendors/styles/icon-font.min.css') }}">

	<link rel="stylesheet" type="text/css" href="{{ URL::asset('public/src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}">

	<link rel="stylesheet" type="text/css" href="{{ URL::asset('public/src/plugins/datatables/css/responsive.bootstrap4.min.css') }}">

	<link rel="stylesheet" type="text/css" href="{{ URL::asset('public/vendors/styles/style.css') }}">

	<link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/app.css') }}">



	<!-- Global site tag (gtag.js) - Google Analytics -->

	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>

	<script>

		window.dataLayer = window.dataLayer || [];

		function gtag(){dataLayer.push(arguments);}

		gtag('js', new Date());



		gtag('config', 'UA-119386393-1');

		

		// Define a JavaScript variable with the APP_URL

        var appUrl = "{{ env('APP_URL') }}" + "/";

	</script>

</head>

<body>