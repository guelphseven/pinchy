# Day 1: Unnamed Message System

## What's that?
We are writing a universal notification service that will allow people to receive messages wherever they are. You can receive the messages you want, where you wnat, when you want them.

This is a messaging system which sends arbitrary blobs of data to XML feeds. That's the idea, at least. Where we've ended up is an odd mix of tumblr and the 'facebook wall'. It's a little bit like the extension google wrote to jabber for gchat. We're going to use it for a notification system, for a chat system, for location based news/web/files - a bunch of cool stuff. But we needed this basis first.

## What do I do to build / use it?
It really just comes down to a database and some PHP. You submit an HTTP request with authentication, destination, and payload POST data, and it inserts the payload to the destination XML feed. Users can then make requests to the XML feed to pull data down.
