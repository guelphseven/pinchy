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
				data: "username="+username+"&hash="+hash+"&chat="+openchat+"&message="+text
			});
			$('#message').val("");
		}
	}
}

function openChatRequest(user)
{
	$('#chatwindows').append("<div id='chat-"+user+"' class='messages'></div>");
	$('body').find("#id-"+user).hide();
	
	getMessages(user);
	chatwindows.push(user);
	$('body').find("#id-"+user).addClass("open");
	if (openchat == "")
	{
		openchat = user;
		$('body').find("#id-"+user).show();
		$('body').find("#id-"+user).addClass("selected");
	}
}
function acceptUser(user)
{
	$("#chatwindows").append("<div class='#request' id='add-"+user+"'><button>Accept</button></div>");
}
function addUser()
{
	$('body').find('#request').remove();
	$("#chatwindows").append("<div class='request' id='request'><input type='text' id='requestname'/><button>Send Request</button><button onclick='cancelRequest()'>Cancel</button></div>");
	var pos = $('#chatwindows').position();
	$('body').find('#request').css('top', pos.top);
	$('body').find('#request').css('left', pos.left);
}
function cancelRequest()
{
	$('body').find('#request').remove();
}
function accept(user)
{
	$.ajax({
		url: "acceptRequest.php",
		type: "POST",
		data: "username="+username+"&hash="+hash+"&user="+user,
		success: function(response)
		{
			if (response == "OK")
			{
				getUsers();
			}
		}
	});
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

function checkNewConnections()
{
	$.ajax({
		url: "checkConnections.php",
		type: "POST",
		data: "username="+username+"&hash="+hash,
		success: function(response)
		{
			res = $.parseJSON(response);
			if (res['message'] == "Request")
			{
				//addUser(res['user']);
			}
			else if(res['message'] == "Accepted")
			{
				getUsers();
			}
			else
			{
				openChatRequest(user);
			}
			checkNewConnections();
		}
		
	});
}

function getMessages(user)
{
	$.ajax({
		url: "checkMessages.php",
		type: "POST",
		data: "username="+username+"&hash="+hash+"&chat="+user,
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
		data: "user="+username+"&hash="+hash,
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
