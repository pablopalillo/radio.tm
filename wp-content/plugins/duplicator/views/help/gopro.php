<?php
DUP_Util::CheckPermissions('read');

require_once(DUPLICATOR_PLUGIN_PATH . '/views/javascript.php');
require_once(DUPLICATOR_PLUGIN_PATH . '/views/inc.header.php');
?>
<style>
    /*================================================
    PAGE-SUPPORT:*/
    div.dup-support-hlp-hdrs{
		text-align: center;
        padding:15px; 
        font-weight:bold; font-size:25px;
        background-image:-ms-linear-gradient(top, #FFFFFF 0%, #DEDEDE 100%);
        background-image:-moz-linear-gradient(top, #FFFFFF 0%, #DEDEDE 100%);
        background-image:-o-linear-gradient(top, #FFFFFF 0%, #DEDEDE 100%);
        background-image:-webkit-gradient(linear, left top, left bottom, color-stop(0, #FFFFFF), color-stop(1, #DEDEDE));
        background-image:-webkit-linear-gradient(top, #FFFFFF 0%, #DEDEDE 100%);
        background-image:linear-gradient(to bottom, #FFFFFF 0%, #DEDEDE 100%);
    }
    div.dup-compare-area {width:400px;  float:left; border:1px solid #dfdfdf; border-radius:4px; margin:10px; line-height:18px;box-shadow: 0 8px 6px -6px #ccc;}
	div.feature {background: #fff; padding:15px; margin: 2px; text-align: center; min-height: 20px}
	div.feature a {font-size:18px; font-weight: bold;}
	div.dup-compare-area div.feature div.info {display:none; padding:7px 7px 5px 7px; font-style: italic; color: #555; font-size: 14px}
	div.dup-gopro-header {text-align: center; margin: 5px 0 15px 0; font-size:18px; line-height: 30px}
	div.dup-gopro-header b {font-size: 35px}
	a.dup-check-it-btn {box-shadow: 5px 5px 5px 0px #999 !important; font-size: 20px !important; height:50px !important;   padding:10px 40px 0 40px !important;}

</style>

<script type="text/javascript">var switchTo5x = true;</script>
<script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "1a44d92e-2a78-42c3-a32e-414f78f9f484"});</script> 

<div class="wrap dup-wrap">
	
    <?php duplicator_header(__("Go Pro!", 'wpduplicator')) ?>
    <hr size="1" />

    <div style="width:850px; margin:auto; text-align: center;">

		<div style="line-height:28px">
			
			<h1 style="font-size:38px">
				<img src="<?php echo DUPLICATOR_PLUGIN_URL ?>assets/img/logo.png" style='text-align:top; margin:-8px 0'  />
				<?php DUP_Util::_e('Duplicator Pro Has Arrived!') ?>
			</h1>
			<h3 style="font-size:20px">
				<?php DUP_Util::_e('The simplicity of Duplicator') ?>
				<?php DUP_Util::_e('with the power the professional requires.') ?>
			</h3>
		</div>

        <!-- FREE-->
        <div class="dup-compare-area">
            <div class="dup-support-hlp-hdrs">
				<?php DUP_Util::_e('Duplicator Free') ?>
            </div>
			<div class="feature">
				<a href="javascript:void(0)" class="dup-info-click"><?php DUP_Util::_e('Backup Files &amp; Database') ?></a>
				<div class="info"><?php DUP_Util::_e('Compresses all your WordPress files and database into a compressed snapshot archive file.') ?></div>
			</div>
			<div class="feature">
				<a href="javascript:void(0)" class="dup-info-click"><?php DUP_Util::_e('Directory Filters') ?></a>
				<div class="info"><?php DUP_Util::_e('Filter out the directories and file extensions you want to include/exclude in your in your archive file.') ?></div>
			</div>
			<div class="feature">
				<a href="javascript:void(0)" class="dup-info-click"><?php DUP_Util::_e('Database Table Filters') ?></a>
				<div class="info"><?php DUP_Util::_e('Filter out only the database tables you want to include/exclude in your database creation script.') ?></div>
			</div>
			<div class="feature">
				<a href="javascript:void(0)" class="dup-info-click"><?php DUP_Util::_e('Migration Wizard') ?></a>
				<div class="info"><?php DUP_Util::_e('With just two files (archive &amp; installer.php) move your site to a new location.') ?></div>
			</div>
			<div class="feature"><i class="fa fa-times fa-lg"></i></div>
			<div class="feature"><i class="fa fa-times fa-lg"></i></div>
			<div class="feature"><i class="fa fa-times fa-lg"></i></div>
            <div class="feature"><i class="fa fa-times fa-lg"></i></div>
			<div class="feature"><i class="fa fa-times fa-lg"></i></div>
            <div class="feature"><i class="fa fa-times fa-lg"></i></div>
			
        </div> 

        <!-- PRO  -->
        <div class="dup-compare-area">
            <div class="dup-support-hlp-hdrs">
				<?php DUP_Util::_e('Duplicator Pro') ?>
			</div>
			<div class="feature">
				<a href="javascript:void(0)" class="dup-info-click"><?php DUP_Util::_e('Backup Files &amp; Database') ?></a>
				<div class="info"><?php DUP_Util::_e('Compresses all your WordPress files and database into a compressed snapshot archive file.') ?></div>
			</div>
			<div class="feature">
				<a href="javascript:void(0)" class="dup-info-click"><?php DUP_Util::_e('Directory Filters') ?></a>
				<div class="info"><?php DUP_Util::_e('Filter out the directories and file extensions you want to include/exclude in your in your archive file.') ?></div>
			</div>
			<div class="feature">
				<a href="javascript:void(0)" class="dup-info-click"><?php DUP_Util::_e('Database Table Filters') ?></a>
				<div class="info"><?php DUP_Util::_e('Filter out only the database tables you want to include/exclude in your database creation script.') ?></div>
			</div>
			<div class="feature">
				<a href="javascript:void(0)" class="dup-info-click"><?php DUP_Util::_e('Migration Wizard') ?></a>
				<div class="info"><?php DUP_Util::_e('With just two files (archive &amp; installer.php) move your site to a new location.') ?></div>
			</div>
			<div class="feature">
				<a href="javascript:void(0)" class="dup-info-click"><?php DUP_Util::_e('Scheduled Backups') ?></a>
				<div class="info"><?php DUP_Util::_e('Automate the creation of your packages to run at various scheduled intervals.') ?></div>
			</div>
            <div class="feature">
				<a href="javascript:void(0)" class="dup-info-click"><?php DUP_Util::_e('Dropbox Support') ?></a>
				<div class="info"><?php DUP_Util::_e('Backup up your entire site to Dropbox.') ?></div>
			</div>
            <div class="feature">
				<a href="javascript:void(0)" class="dup-info-click"><?php DUP_Util::_e('FTP Support') ?></a>
				<div class="info"><?php DUP_Util::_e('Backup up your entire site to an FTP server.') ?></div>
			</div>
			<div class="feature">
				<a href="javascript:void(0)" class="dup-info-click"><?php DUP_Util::_e('Customer Support') ?></a>
				<div class="info"><?php DUP_Util::_e('Server setups can be quite complex, with pro you get prompt help to get your site backed up and moved.') ?></div>
			</div>
			<div class="feature">
				<a href="javascript:void(0)" class="dup-info-click"><?php DUP_Util::_e('Enhanced Package Engine') ?></a>
				<div class="info"><?php DUP_Util::_e('Overhauled package engine is faster and processes larger sites without timing-out.') ?></div>
			</div>
			<div class="feature">
				<a href="javascript:void(0)" class="dup-info-click"><?php DUP_Util::_e('Package Templates') ?></a>
				<div class="info"><?php DUP_Util::_e('Reusable package configurations for flexible backups and custom site generation.') ?></div>
			</div>
			
        </div>
        <br style="clear:both" />
        <p style="text-align:center">
            <a href="http://snapcreek.com/duplicator?free-go-pro" target="_blank" class="button button-primary button-large dup-check-it-btn" >
                <?php DUP_Util::_e('Check It Out!') ?>
            </a>
        </p>
    </div>
</div><br/><br/><br/><br/>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$( "a.dup-info-click" ).click(function() {
			$(this).parent().find('.info').toggle();
		});
	});
</script>