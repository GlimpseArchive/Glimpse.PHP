A client side Glimpse into whats going on in your server

Overview
--------
At its core Glimpse allows you to debug your PHP application right in the browser. Glimpse allows you to "Glimpse" into what's going on in your web server. In other words what Firebug is to debugging your client side code, Glimpse is to debugging your server within the client.

Fundamentally Glimpse is made up of 3 different parts, all of which are extensible and customizable for any platform:

* Glimpse Server Module (the one you are browsing right now)
* Glimpse Client Side Viewer 
* Glimpse Protocol


Getting started
---------------
Installing Glimpse in a PHP application is very straightforward. Glimpse is supported starting with PHP 5.2 or higher.

* For PHP 5.2, copy the source folder of this repository to your server and add <?php include '/path/to/glimpse/index.php'; ?> as early as possible in your PHP script.
* For PHP 5.3, copy the glimpse.phar file from the build folder of this repository to your server and add <?php include 'phar://path/to/glimpse.phar'; ?> as early as possible in your PHP script.

From that moment, navigate to your web application and append the ?glimpseFile=Config query string to enable/disable Glimpse. Optionally, a client name can also be specified to distinguish remote requests.


How it Works
------------
On the Server:

1. Server collects all server side information that will aid in debugging (i.e. PHP configuration, environment, session variables, trace data, etc)
2. It does this by running through a pipeline of server side data providers that can be dynamically controlled and added to under plugin architecture
3. Before the response is send, the server formats this data in accordance with the Glimpse Protocol and serializes it as JSON
4. Depending on whether it is a Ajax request or not, the server embeds the JSON in the HTTP Header or in the content of the page

On the Client:

5. Depending on whether it is a Ajax request or not, the picks up the JSON data and to the data set by executing a pipeline of client side data providers that can be dynamically controlled and added to under plugin architecture
6. The client side module then dynamically renders a client side UI (similar to Firebug Lite) that lets you view this data

Glimpse can be turned on or off by a series of different mechanistic, but at its core if the Glimpse cookie is present the server will provide the "debug" data - as a security measure, the request for debug data is "authenticated". Via the plugin model, this authentication check can have any logic that is required by the site to ensure that unauthorized users don't have access to sensitive debug data.

 
Server Implementations 
----------------------
Given the scope of the project and what it can do, the concept isn't restricted to any one platform. Hence, once mature, Glimpse Server Module will be available on all major web platforms. 

Platforms currently supported:

* ASP.Net Web Forms 
* ASP.Net MVC 
* PHP (the one you are currently browsing)

Platforms soon to be supported supported:

* Ruby on Rails 

NOTE - If you would like help develop a Glimpse Server Module for a given platform please let us know.


Client Implementations 
----------------------
To start with the Glimpse Client Side Viewer is simply a light weight JavaScript "plugin" that understands the Glimpse Protocol and knows how to render the data. From a technology standpoint we currently use jQuery as the client side framework.

Eventually, we would like to have actual browser plugins that provide richer functionality and experience, but the JavaScript version of the Glimpse Client Side Viewer is surprisingly well featured, intuitive and provides a high fidelity experience. We also hope to have a version for mobile ready soon which customizes the viewing/usage experience when using a mobile device.

![Glimpse Client](/Glimpse/Glimpse/raw/master/Doco/Glimpse.png "Glimpse Client")


Protocol
-------- 
Details on the Glimpse protocol can be found at http://getglimpse.com/Protocol.