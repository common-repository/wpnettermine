=== WPNetTermine ===
Contributors: zottto
Tags: hausmanager, nettermine, booking
Requires at least: 3.5.1
Tested up to: 4.0
Stable tag: 0.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress integration for the NetTermine module of HausManager (www.hausmanager.de).

== Description ==

This plugin integrates the NetTermine booking form of HausManager into your wordpress website.
Normally this is done via Iframe, but this plugin also offers the possibility to add the booking form
as plain HTML which also means, that the wordpress theme css styles are applied to the form.

Please see the website of HausManager and NetTermine for more details:

* [HausManager](http://www.computer-lan.de/produkte-und-loesungen/software-loesungen-fuer-bildungsstaetten.html)
* [NetTermine](http://nettermine.de/)

== Installation ==

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'WPNetTermine'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `wpnettermine.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `wpnettermine.zip`
2. Extract the `wpnettermine` directory to your computer
3. Upload the `wpnettermine` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard


== Frequently Asked Questions ==

= Can I use this booking form without using HausManager and NetTermine? =

No. You need to use the HausManager software and the NetTermine add-on.
This plugin only loads the NetTermine booking form for your NetTermine object ID.

= How is the booking form integrated? =

You can include the booking form as IFrame or as plain HTML which has the advantage
of using the theme styles automatically.

== Screenshots ==

1. Admin settings page
2. Booking form integration in example page

== Changelog ==

= 0.0.3 =
* Official release.

= 0.0.2 =
* Internal release.
* Fixed encoding problems.

= 0.0.1 =
* First release.
