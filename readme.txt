=== Plugin Name ===
Contributors: Workbox
Author URI: http://www.workbox.com/
Tags: Google Analytics
Requires at least: 2.0.2
Tested up to: 3.1
Stable tag: trunk


Makes Google Analytics track clicks to any type of file you host on your web server.

== Description ==

Makes Google Analytics track clicks to any type of file you host on your web server.
You set which file types are tracked.
No need to edit or customize URLs.

[Plugin Page Link](http://blog.workbox.com/wordpress-plugin-google-analytics-track-files/)

== Installation ==

Download and activate the plugin.
The plugin options page (Google Analitics Options) will be available under Settings in the Admin panel.

Plugin options.

1. Google Analitics ID - enter your Google Analytics ID.
2. Domain - domain name for which the tracking is being configured. By default, the domain is set to the domain name of the site where the plugin is installed.
3. Include jQuery Script. The plugin uses jQuery library. If your site is already using this library, select "NO". If your site is not using it yet - select "YES" (in this case the library will load from the plugin catalog).
4. File extensions you want to track - list of file extensions you want to track, comma-delimited. Only letters, digits and underscore and space. To track pdf files, write "pdf".
5. Enable Plugin? turns the plugin on and off.

If your Google Analytics tracking code is already in the page template, please remove it. The plugin will insert the code automatically.

== Frequently Asked Questions ==

1. What happens if you don't remove your Google Analytics code and install our plugin as well?
Answer: Either there will be a javascript error (the page will load fine) or the stats will be submitted twice. So we strongly recommend that you remove your Google Analytics code when you install the plugin.
== Screenshots ==
1. Plugin Settings page


