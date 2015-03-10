=== RiotSchedule ===
Contributors: mattyribbo
Donate link: http;//www.mattyribbo.co.uk/projects/riotschedule
Tags: Schedule, Week, Shows, TV, Radio, Times
Requires at least: 3.0
Tested up to: 3.8
Stable tag: 1.1.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

RiotSchedule is a show scheduling plugin that allows you to create a fully interactive schedule with linked posts.

The plugin supports widgets, and has shortcodes to allow you to display your schedule

== Description ==

RiotSchedule is a post based scheduling plugin. The plugin allows you to create a schedule, each with their own linked posts to display content on.

The plugin support widgets and has shortcodes to allow you to display your scheduled posts. Public AJAX calls also exist (to be documented). The plugin can also format the data within <div> and <ul>/<li> allowing you to style your listings without fiddling around with plugin code!

List of shortcodes (I will document these shortly!)
[riotschedule]
[riotschedule-day]
[riotschedule-list]
[riotschedule-nownext]
[riotschedule-pid]

To see a site based around RiotSchedule (along with some site-specific enhancements) visit http://www.hubradio.co.uk and have a look under 'Shows' and 'Schedule'. If you also look on the Listen Live / RadioPlayer you can see using some custom built AJAX queries I can display the current show on the RadioPlayer.

== Installation ==

1. Upload the `riotschedule` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create your scheduled items using the 'Scheduled Items' menu option. It's like creating a post/page but you also put in schedule details.
4. Create a page to put your schedule on. [riotschedule] will display a full week schedule in table form, [riotschedule-day] will display scheduled items for the current day in a list format, [riotschedule-day day=x] will display whatever is scheduled for day x (Insert a number between 1 - 7, going Mon - Sun)

== Frequently asked questions ==

= Can I theme my schedule show page? =
Yes you can. What I suggest to do for now is create the shortcodes or widgets and customise the css classes.
= It's not working?! =
Please give me a buzz on my email - matt@mattyribbo.co.uk - and I will try my best to help.

== Screenshots ==

See http://www.mattyribbo.co.uk/projects/riotschedule

== Changelog ==

= 1.1.1 =
* Fixed issue with new installations
* Another change.
= 1.1 = 
* Introduced 'Unscheduled Items' where temporary scheduled items can override normal 'Scheduled Items'
= 1.0 =
* Initial Release from internal 

== To-do ==
* Code tidy-up
* Wordpress coding standardisation
* Remove Hub specific naming on divs
* Proper multi-schedule support (at the moment it's a bit hit and miss, you might get overlap or just default to schedule 1

== Credits ==
This plugin was forked and based from the 'Weekly Schedule' plugin by Yannick Lefebvre. 
Originally written for use on Hub Radio. Apologies if there are any hub specific wording/code in there.