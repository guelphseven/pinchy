var chatwindows = new Array();
var openchat = "";
var usersJSON = "{}";
var users = new Array();

$(document).ready(function()
{
	getUsers();
	$('#message').keypress(function(e)
	{
		if(e.which == 13)
		{
			sendMessage();
		}
	});
});

function sendMessage()
{
	if (openchat != "")
	{
		var text = $('#message').val();
		
		if (text != "")
		{
			$.ajax({
				url: "sendMessage.php",
				type: "POST",
				data: "username="+username+"&chat="+openchat+"&message="+text
			});
			$('#message').val("");
		}
	}
}

function openChat(user)
{
	if (!contains(chatwindows, user))
	{
		if (openchat != "")
		{
			$('body').find("#chat-"+openchat).hide();
			$('body').find("#id-"+openchat).removeClass("selected");
		}
		$('#chatwindows').append("<div id='chat-"+user+"' class='messages'></div>");
		openchat = user;
		getMessages(user);
		chatwindows.push(user);
		$('body').find("#id-"+user).addClass("open");
		$('body').find("#id-"+user).addClass("selected");
	}
	else{
		$('body').find("#chat-"+openchat).hide();
		$('body').find("#chat-"+user).show();
		$('body').find("#id-"+user).addClass("selected");
		$('body').find("#id-"+openchat).removeClass("selected");
		openchat = user;
	}
}

function getMessages(user)
{
	$.ajax({
		url: "checkMessages.php",
		type: "POST",
		data: "username="+username+"&chat="+user,
		success: function(response)
		{
			var objDiv = $('body').find("#chat-"+user);
			$('body').find("#chat-"+user).append("<span class='message'>"+response+"</span>");
			objDiv.animate({ scrollTop: objDiv.attr("scrollHeight") }, 1000);
			getMessages(user);
		}
	});
}

function getUsers()
{
	$.ajax({
		url: "getUsers.php",
		type: "POST",
		data: "user="+username,
		success: function(response){
			if (usersJSON != response)
			{
				usersJSON = response;
				users = $.parseJSON(response);
				var i =0;
				while(true)
				{
					if (users[i] == null) break;
					$('#userlist').append("<span id='id-"+users[i]+"' class='user' onclick=\"openChat('"+users[i]+"')\">"+users[i]+"</span>");
					i++;
				}
			}
			//setTimeout("getUsers()", 20000);
		},
		error: function(){
			alert("error");
		}
	});
}

function contains(a, obj){
  for(var i = 0; i < a.length; i++) {
    if(a[i] == obj){
      return true;
    }
  }
  return false;
}
