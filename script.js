var missed = 0;

$(document).ready(function()
{
	loadFeed();
	$('.container a').button();
	$('.container a').css('color', 'black');
	$('.container a').css('width', '200px');
	$('#feedbutton').click(function (event)
	{
		loadFeed();
	});
	$('#sendbutton').click(function (event)
	{
		loadSendForm();
	});

	setTimeout("listen()", 1000);

});
function listen()
{
	$.ajax({
		url: "http://kmonk.g7:8000",
		method: "GET",
		data: "user="+username+"&deviceid="+deviceid,
		success: function(response)
		{
			var newPinches = $.parseJSON(response);
			scrollPinches(newPinches);
			missed = 0;
			listen();
		},
		error: function(response)
		{
			missed++;
			if (missed <  15)
			{
				listen();
			}
		}
	});
}
function scrollPinches(pinches)
{
	//alert(pinches['feeditems'][0]['origin']);
	for (i=0; i<pinches['feeditems'].length; i++)
	{
		//alert("wee");
		$('.feedcontainer').prepend(
"<div class='feed'>\
	<div class='message'><span>"+pinches['feeditems'][i]['post']+"</span></div>\
	<div class='messagefooter'>\
		<div class='from'><span>"+pinches['feeditems'][i]['origin']+"</span></div>\
		<div class='date'><span>"+pinches['feeditems'][i]['time']+"</span></div>\
	</div>\
</div>"
		);
		if ( $('.feed').length > numPinches )
		{
			$('.feed:last').remove();
		}
	}
}

function loadFeed()
{
	$.ajax({
		url: "getFeed.php",
		type: "GET",
		success: function(response)
		{
			$('#content').html(response);
		}
	});
}

function loadSendForm()
{
	$('.feed').remove();
	$.ajax({
		url: "sendMessage.php",
		type: "GET",
		success: function(response)
		{
			$('#content').html(response);
			$('form #submit').button();
			$('form #submit').css('float', 'right');
			$('form #submit').click(function()
			{
				var formdata=$('form').serialize();
				$.ajax({
					url: "api/write.php",
					type: "POST",
					data: formdata,
					success: function()
					{
						loadFeed();
					}
				});
			});
		}
	});
}