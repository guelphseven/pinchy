var http = require("http");
var event = require("http");
var timer = require("timers");
var requestTimeout = 240;
var responses = [];

this.cleaner = timer.setInterval(function(){checkPending();}, 60000);

function checkPending()
{
	var now = new Date().getTime()/1000;
	console.log("Cleaning");
	for (var user in responses)
	{
		for (var device in responses[user])
		{
			if (now - responses[user][device]['info']['timestamp'] > requestTimeout)
			{
				console.log("Ending session: " + responses[user][device]['info']['user'] + " - " + responses[user][device]['info']['deviceid']);
				responses[user][device]['response'].writeHead(408, {'content-type': 'text/plain', "Access-Control-Allow-Origin": "*"});
				responses[user][device]['response'].write("Timeout");
				responses[user][device]['response'].end();
				delete responses[user][device];
			}
		}
	}
}

http.createServer(function(request, response)
{
	request_info = require('url').parse(request.url, true);
	var user = request_info['query']['user'];
	var deviceid = request_info['query']['deviceid'];

	if (responses[user] == null) responses[user] = new Array();
	responses[user][deviceid] = new Array();
	responses[user][deviceid]['info'] = request_info['query'];
	responses[user][deviceid]['info']['timestamp'] = parseInt((new Date().getTime())/1000);
	
	responses[user][deviceid]['response'] = response;

	console.log("Request for user info: " + user + " ID: " + deviceid + " timestamp: " + responses[user][deviceid]['info']['timestamp']);
}).listen(8000);

event.createServer(function(request, response)
{
	request_info = require('url').parse(request.url, true);
	var user = request_info['query']['user'];
	console.log("Update for: " + user);
	if (responses[user] != null)
	{	
		sendUpdate(user);
		response.writeHead(200, { "Content-Type": "text/plain"});
		response.end("OK");
	}
	else
	{
		response.writeHead(200, { "Content-Type": "text/plain"});
		response.end("Not Waiting");
	}
}).listen(8080);

function sendUpdate(user)
{
	console.log("Sending update for: " + user);
	if (responses[user] == null) return;
		for (var device in responses[user])
		{
			var data = 'username='+user+'&format=json&time='+responses[user][device]['info']['timestamp'];
			var header = {
				'Host': 'http://kmonk.g7',
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
			};
			var options = {
				host: 'kmonk.g7',
				port: 80,
				path: '/api/read.php?'+data,
				method: 'GET'
			};
			var response = '';

			var feed = http.request(options, function(res)
			{
				res.setEncoding('utf8');
				code = res.statusCode;
				head = res.headers['Content-Type'];
				
				res.on('data', function(chunk) {
						response += chunk;
				});
				res.on("end", function() {
					responses[user][device]['response'].writeHead(code, {'content-type': 'text/plain', "Access-Control-Allow-Origin": "*"});
					responses[user][device]['response'].write(response);
					responses[user][device]['response'].end();
					delete responses[user][device];
				});
			});
			feed.end();
		}
}
