=== Plugin Name ===
Contributors: Infragistics
Donate link: http://www.infragistics.com/
Tags: widgets, widget, fix, serialized, siteurl, DNS
Requires at least: 3.0.1
Tested up to: 3.5.1
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Widget-fixer fixes your widgets after you change to a new siteurl.

== Description ==

Widget-fixer works by recalculating the string lengths of the widget strings and updating 
the relevant numbers in the database so that your widgets will load again.   

**Disclaimer**
This plugin is provided "as is." It is free software licensed under the terms of the [GNU General Public License 2.0 (GPL)] (http://www.gnu.org/licenses/gpl.html "GNU General Public License 2.0"). It is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Infragistics is not liable for any damages or losses. 

== Installation ==

1. Backup your database.
1. Unzip `widget-fixer.zip` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Navigate the Settings -> Widget-fixer to start fixing your widgets.

== Frequently Asked Questions ==

= How do I get started? =
It is highly recommended that you take a backup of your database before using
this plugin.

= What does this plugin do? =
Widget-fixer is very basic and really does only one thing.  
It recalculates the string lengths of the widget strings and updates 
the relevant numbers in the database so that your widgets will work again.   

= Under what circumstances would you use this plugin? =
If you update your URLs and apply the updated URLs directly to the database, 
then any widgets with updated URLs will need to be fixed.  This plugin fixes
those widgets for you so that you do not need to redo your work.

= What if the plugin does not work? =
There is still hope.  Sometimes WordPress removes the corrupted portions of
code from the database when it loads.  If the plugin fails to fix your widgets,
you should try to run this from the command-line instead. There is a special way
to do this.  You should use the widget-fixer-cli.php file as follows.
1. Stop your site.
2. Restore your database.
3. Run:  php -q widget-fixer-cli.php /var/www/html/wp-config.php

Replace the "/var/www/html/" with the actual path to your wp-config.php file.
This method is generally quite reliable and successful.

= Is this supported? =
No, this is a free plugin that is intended to be used at your own risk.

= Where can I get more information about Infragistics? =
http://www.infragistics.com

== Screenshots ==

== Changelog ==

= 1.2 = 
* Bug fix - Added UTF8 support for MySQL connection to correct the character count.
* Bug fix - Reworked algorithm to enable text fields with style tags (requiring ;'s).
* Enhancement - Now re-serializes with built-in PHP function, rather than using a custom one.

= 1.1 =
* Added more widgets to fix.
* Added a CLI feature.
* Moved the major functions into a separate file.
* Added the ability view the source of the widgets that are fixed.
* Added support for themes.
* Fixed an issue with newline characters causing incorrect string length calculations

= 1.0 =
* This is the first release.

== Upgrade Notice ==

= 1.2 =
Now has better support for text fields.  Fixes even more widgets!
