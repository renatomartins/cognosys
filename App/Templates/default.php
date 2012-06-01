<!DOCTYPE HTML>
<html>
<head>
	<title>Cognosys Sample</title>
</head>

<body>
<div id='container'>
	<div id='header'></div>
	
	<div id='center'>
		<div id='sidebar-left'> 
			<div id='menu'>
				<div class='icon'><a href="/">Home</a></div>
				<div class='icon'><a href="/news">News</a></div>
				<div class='icon'><a href="/workspace">Workspace</a></div>
			</div>
			
		</div>

		<?=alerts($this->alert())?>
	
		<div id='content'> 
			<div><!-- just padding -->
				<?=$this->view()?>
			</div>
		</div>

	</div>
	
	<div id='footer'></div>

</div>

</body>
</html>
