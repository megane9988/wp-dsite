=== Better Widgets ===
Contributors: shaunandrews
Tags: widgets
Requires at least: 3.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Stable tag: trunk
Version: 0.3

Make it easier to manage your site's widgets.

== Description ==

Lets make widgets better. This plugin moves your wigdet areas (some call them sidebars) to the left, making them the focus of the screen. The list of available widgets now lives on the right. Available widgets are contained in their own scrollable div, helping resolve the age-old problem of scrolling and dragging. And widgets get some icons!

Your active widgets get a little love, too. Saving a widget no closes the controls, and you'll get some feedback that your widget has saved.

This plugin is under active development and may break some things. Also, its not very friendly across various screen sizes, yet.

== Installation ==

Install this plugin just like any normal WordPress plugin, by dropping the folder into your wp-content/plugins/ folder.

== Changelog ==

= 0.3 =
Commenting out the wp_widget_control() function to stop causing a fatal error. This means no widget icons or fancy widget descriptions. Hoping to find a better way to do this.

= 0.2 =
Lots of changes. This plugin now requires WordPress 3.8, as the widgets screen in core has had some significant updates. Adding a checkmark to show that you've saved a widget. New icons for default widgets (and some Jetpack widgets, too).

= 0.1 =
Starting development with a concept UI. Some things don't actually work. Experimental tagging of media items is commented out. Delete doesnt actually delete. Search is limited to the items viewable on the page.