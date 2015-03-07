=== XCloner - Backup and Restore===
Contributors: xcloner
Donate link: http://www.xcloner.com/
Tags: backup, restore, plugin, database, full backup, cloner, xcloner, theme, files, upload, wordpress backup, backup plugin, database backup, database restore, site move, transfer, blog transfer, BuddyPress
Requires at least: 2.0.2
Tested up to: 3.9
Stable tag: 3.1.1

XCloner is a full backup and restore plugin for Wordpress, it will backup and restore both files and database. www.xcloner.com

== Description ==

www.XCloner.com

XCloner is a Backup and Restore component designed for PHP/Mysql websites, it can work as a native plugin for WordPress and Joomla!.

XCloner design was specifically created to Generate custom backups of any LAMP website through custom admin inputs, and to be able to Restore the clone on any other location with the help of the automatic Restore script we provide, independent from the main package!

XCloner Backup tool uses Open Source standards like TAR and Mysql formats so you can rest assured your backups can be restored in a variety of ways, giving you more flexibility and full control.

XCloner Generate, Move and Restore process:

   1. Generate and Store the backups
   2. Move the backup and restore script to the new location
   3. Restore the backup by launching the XCloner.php restore script

Features:

   * Backup and Restore any PHP/Mysql  application
   * Create custom backups
   * Generate automatic backups based on cronjobs
   * Restore your backups anywhere
   * Share your custom backups with your clients


== Installation ==

1. Upload the plugin directory to wp-content/plugins directory
2. Activate the plugin
3. Create directory administrator/backups and make it writeable under your Wordpress site root
4. Access it from the Plugins->XCloner menu

UPGRADE:

If you plan on upgrading your XCloner installation, to keep it's original setting, please keep a copy after your wp-content/plugins/xcloner-backup-and-restore/cloner.config.php file, and re-upload it after the upgrade.

== Frequently Asked Questions ==

= How do I restore my backup? =

If the inside Clone option fails for some reason, you need to:

1. upload the backup archive to the new restore site
2. upload the XCloner.php and TAR.php files in the same location as the backup from 1., you can find these files in directory wp-content/plugins/xcloner/restore/ on the original site
3. start the XCloner.php restore script in your browser


== Screenshots ==

1. Login screen
2. Welcome screen
3. Config options General tab
4. COnfig options System tab
5. View Backups
6. Generate Backup -> database options
7. Generate Backup -> files options
8. Restore screen

== Changelog ==

= 3.1.1 = 
* added CSRF protection

= 3.1.0 =
* added Wordpress login-less integration
* plugin settings are now saved to database
* security audit and hardening

= 3.0.8 =
* added russian language support

= 3.0.7 =
* added sftp support for backup transfer, thanks Todd Bluhm - dynamicts.com

= 3.0.6 =
* added php 5.4 compatibility

= 3.0.4 =
* LFI vulnerability fix

= 3.0.3 =
* added amazon ssl option box
* moved the compress option to the System tab, don't use it unless you know what you are doing!

= 3.0.1 =
* several important security and bug fixes

= 3.0 =
* incremental database backup
* incremental file system scan
* backup size limit and option to split it into additional archives, default 2GB
* exclude files larger than a certain size option
* incremental files restore
* JQuery Start interface

= 2.2.1 =
* Added JSON AJAX interface to the Generate Backup process
* Added incremental filesystem scan
* several bug fixes
* php >=5.2.0 version check

= 2.1.2 =
* Added Amazon S3 cron storage support

= 2.1 =
* Initial release

== Upgrade Notice ==

= 3.0.3 =
Please check changelog!
