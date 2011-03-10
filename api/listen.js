var http = require("http");
var event = require("http");
var requestTimeout = 30;
var requests = new Array();
var responses = new Array();

this.cleaner = setInterval(checkPending(), 30000);

function checkPending()
{
	var now = new Date().getTime()/1000;
	console.log("Cleaning");
	for (var user in this.responses)
	{
		console.log("Cleaning");
		for (var request in this.user)
		{
			console.log("Cleaning");
			if (now - request['timestamp'] > requestTimeout)
			{
				console.log("Ending session: " + request.req['user'] + " - " + request.req['deviceid']);
				delete request;
			}
		}
	}
	console.log("Done Cleaning");
}
/*
http.createServer(function(request, response)
{
	request_info = require('url').parse(request.url, true);
	var user = request_info['query']['user'];
	var deviceid = request_info['query']['deviceid'];


	//response.writeHead(200, { "Content-Type": "text/plain"});
	//response.end(request.url + ": responded to "+user+"!!!");
	response.req = request_info;
	response.req['timestamp'] = parseInt((new Date().getTime())/1000);
	
	if (responses[user] == null) responses[user] = new Array();
	responses[user][deviceid] = (response);
	console.log("Request for user info: " + user + " ID: " + deviceid + " timestamp: " + response.req['timestamp']);
	
	//responses[request_info.query.user] = (response);
	//userwaits.push(request_info.query.user);
}).listen(8000);
*/
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
/*
function sendUpdate(user)
{
	console.log("Sending update for: " + user);
	if (responses[user] == null) return;
		for (var request in responses[user])
		{
			var data = 'username='+user+'&format=json&time='+request.req['timestamp'];
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
			//var site = http.createClient(80, "localhost");
			var feed = http.request(options, function(res)
			{
				res.setEncoding('utf8');
				code = res.statusCode;
				head = res.headers['Content-Type'];
				
				res.on('data', function(chunk) {
						response += chunk;
				});
				res.on("end", function() {
					//var this_current = this_current;
					request.writeHead(code, {'content-type': 'text/plain', "Access-Control-Allow-Origin": "*"});
					request.write(response);
					request.end();
					delete request;
				});
			});
			//feed.write();
			feed.end();
		}
}
*/