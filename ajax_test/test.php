<html>
	<head>
		<title>Test AJAX</title>
		<link rel="stylesheet" href="style.css">

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
		<script>
			var delta = 0;
			$(document).ready(function(){
				test(15);

			});
			function test(timeout)
			{
				delta = 0;
				$("#message").html("Test "+timeout+": ");
				var inter = setInterval("timer()", 1000);
				$.ajax({
					url: "testwait.php",
					type: "POST",
					data: "length="+timeout,
					success: function(response)
					{
						if (response == "OK")
						{
							$("#log").append("Success in: "+delta+"<br/>");
							var objDiv = $("#log");
							objDiv.animate({ scrollTop: objDiv.attr("scrollHeight") }, 1000);
							clearInterval(inter);
							test(timeout*2);
						}
						//clearInterval(inter);
					},
					error: function()
					{
						$("#message").html("Connection died in: ");
						clearInterval(inter);
					}
				});
			}
			function timer()
			{
				delta += 1;
				$("#timer").html(delta + "s");
			}
		</script>
		<style>
			.container
			{
				width: 600px;
				height: 400px;
				margin: auto;
			}
			.current
			{
				width: 100%;
				height: 23px;
				display: block;
			}
			.log
			{
				width: 100%;
				height: 373px;
				display: block;
				overflow: auto;
				border: 1px solid black;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="current">
				<span id="message" class="display:inline"></span>
				<span id="timer" class="display:inline"></span>
			</div>
			<div class="log" id="log">
			</div>
		</div>
	</body>
</html>