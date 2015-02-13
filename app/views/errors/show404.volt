<!--
	- View
	@ Called by the ErrorsController / show404Action
-->
<!DOCTYPE HTML>

<html lang="en">
<head>
	<meta charset="utf-8">
	<title>404 Error</title>

	{{ stylesheet_link('//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css', false) }}

	<style>
	.spacer30 { margin-top : 30px; }
	.error { margin-top : 5em; }
	</style>
</head>
<body>
	<div class="container">
		<div class="spacer30"></div>
		<div class="col-md-5">
			{#{{ image('public/assets/img/logo.png') }}#}
		</div>
		<div class="col-md-7 text-left">
			<div class="page-header">
			<h1 class="error">404 Error</h1>
			<p>
				It's looking like you may have taken a wrong turn.<br/>
				Don't worry ... it happens to the best of us.
			</p>
		</div>
		</div>
	</div>

</body>
</html>