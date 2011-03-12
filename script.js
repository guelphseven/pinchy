var missed = 0;
var listener;

$(document).ready(function()
{
	setTimeout("loadFeed()", 500);
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
	$('#allowedbutton').click(function (event)
	{
		loadAllowed();
	});

	//setTimeout("listen()", 1000);

});
function listen()
{
	if (page == 0)
	{
		listener = $.ajax({
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
}
function scrollPinches(pinches)
{
	if (page == 0)
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
	page = 0;
	listen();
}

function loadSendForm()
{
	page = 1;
	listener.abort();
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
					success: function(response)
					{
						//alert(response);
						loadFeed();
					},
					error: function(request, status, error)
					{
						//alert(request.responseText);
						unableToSend($('#recipient').val());
					}
				});
			});
		}
	});
}
function addUser()
{
	//$('#addbutton').disable();
	$('#adduser').hide();
	$('#adduser').before("\
	<div class='feed'>\
		<form>\
			<span><input type='text' name='user'/>\
			<a href='#' id='submituser'>+</a></span>\
		</form>\
		<div class='clear'>$nbsp;</div>\
	</div>\
	");
	$('#submituser').button();
	$('#submituser').css('float', 'right');
	$('#submituser').css('color', 'black');
	$('#submituser').css('margin-right', '8px');
	$('.feed form').submit(function(event)
	{
		event.preventDefault();
		$('#submituser').click();
		return false;
	});
	$('#submituser').click(function()
	{
		formdata = $('.feed form').serialize();
		$.ajax({
			url: "add.php",
			type: "POST",
			data: formdata,
			success: function(response)
			{
				loadAllowed();
			},
			error: function(response)
			{
				loadAllowed();
			}
		});
	});
}
function loadAllowed()
{
	page = 2;
	listener.abort();
	$('.feed').remove();
	$.ajax({
		url: "allowed.php",
		type: "GET",
		success: function(response)
		{
			$('#content').html(response);
			$('.feed a').button();
			$('.feed a').css('color', 'black');
			$('#adduser a').css('width', '433px');
			$('#adduser a').css('float', 'right');
			$('#adduser a').css('margin-right', '10px');
			
			$('#addbutton').click(function()
			{
				//alert($('#addbutton').button("option", "disabled"));
					addUser();
			});
		}
	});
}

function deleteUser(id)
{
	$.ajax({
		url: "remove.php",
		type: "POST",
		data: "user="+id,
		success: function(response)
		{
			loadAllowed();
		},
		error: function(response)
		{
			loadAllowed();
		}
	});
}

function unableToSend(user)
{
	$('#content').html("<div class='error'>You are unable to send to user: "+user+"</div>");
}