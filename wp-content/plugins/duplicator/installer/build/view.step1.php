<?php
	// Exit if accessed directly
	if (! defined('DUPLICATOR_INIT')) {
		$_baseURL =  strlen($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];
		$_baseURL =  "http://" . $_baseURL;
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: $_baseURL");
		exit; 
	}
	//DETECT ARCHIVE FILES
	$zip_file_name  = "No package file found";
	$zip_file_count = 0;
	foreach (glob("*.zip") as $filename) {
		$zip_file_name = $filename;
		$zip_file_count++;
	}
	if ($zip_file_count > 1) {
		$zip_file_name = "Too many zip files in directory";
	}
	
	$req01a = @is_writeable($GLOBALS["CURRENT_ROOT_PATH"]) 	? 'Pass' : 'Fail';
	if (is_dir($GLOBALS["CURRENT_ROOT_PATH"])) {
		if ($dh = @opendir($GLOBALS["CURRENT_ROOT_PATH"])) {
			closedir($dh);
		} else {
			$req01a = 'Fail';
		}
	}
	$req01b   = ($zip_file_count == 1) ? 'Pass' : 'Fail';
	$req01    = ($req01a == 'Pass' && $req01b == 'Pass') ? 'Pass' : 'Fail';
	$safe_ini = strtolower(@ini_get('safe_mode'));
	$req02    =  $safe_ini  != 'on' || $safe_ini != 'yes' || $safe_ini != 'true' || ini_get("safe_mode") != 1 ? 'Pass' : 'Fail';
	$req03    = function_exists('mysqli_connect') ? 'Pass' : 'Fail';
	$php_compare  = version_compare(phpversion(), '5.2.17');
	$req04 = $php_compare >= 0 ? 'Pass' : 'Fail';
	$total_req = ($req01 == 'Pass' && $req02 == 'Pass' && $req03 == 'Pass' && $req04 == 'Pass') ? 'Pass' : 'Fail';
?>

<script type="text/javascript">
	/** **********************************************
	* METHOD:  Performs Ajax post to extract files and create db
	* Timeout (10000000 = 166 minutes) */
	Duplicator.runDeployment = function() {
		
		var $form = $('#dup-step1-input-form');
		$form.parsley('validate');
		if (! $form.parsley('isValid')) {
			return;
		}
	
		var msg =  "Continue installation with the following settings?\n\n";
			msg += "Server: " + $("#dbhost").val() + "\nDatabase: " + $("#dbname").val() + "\n\n";
			msg += "WARNING: Be sure these database parameters are correct!\n";
			msg += "Entering the wrong information WILL overwrite an existing database.\n";
			msg += "Make sure to have backups of all your data before proceeding.\n\n";
			
		var answer = confirm(msg);
		if (answer) {
			$.ajax({
				type: "POST",
				timeout: 10000000,
				dataType: "json",
				url: window.location.href,
				data: $form.serialize(),
				beforeSend: function() {
					Duplicator.showProgressBar();
					$form.hide();
					$('#dup-step1-result-form').show();
				},			
				success: function(data, textStatus, xhr){ 
					if (typeof(data) != 'undefined' && data.pass == 1) {
						$("#ajax-dbhost").val($("#dbhost").val());
						$("#ajax-dbport").val($("#dbport").val());
						$("#ajax-dbuser").val($("#dbuser").val());
						$("#ajax-dbpass").val($("#dbpass").val());
						$("#ajax-dbname").val($("#dbname").val());
						$("#ajax-dbcharset").val($("#dbcharset").val());
						$("#ajax-dbcollate").val($("#dbcollate").val());
						$("#ajax-logging").val($("#logging").val());
						$("#ajax-json").val(escape(JSON.stringify(data)));
						setTimeout(function() {$('#dup-step1-result-form').submit();}, 1000);
						$('#progress-area').fadeOut(700);
					} else {
						Duplicator.hideProgressBar();
					}
				},
				error: function(xhr) { 
					var status = "<b>server code:</b> " + xhr.status + "<br/><b>status:</b> " + xhr.statusText + "<br/><b>response:</b> " +  xhr.responseText;
					$('#ajaxerr-data').html(status);
					Duplicator.hideProgressBar();
				}
			});	
		} 
	};

	/** **********************************************
	* METHOD: Accetps Useage Warning */
	Duplicator.acceptWarning = function() {
		if ($("#accept-warnings").is(':checked')) {
			$("#dup-step1-deploy-btn").removeAttr("disabled");
		} else {
			$("#dup-step1-deploy-btn").attr("disabled", "true");
		}
	};

	/** **********************************************
	* METHOD: Go back on AJAX result view */
	Duplicator.hideErrorResult = function() {
		$('#dup-step1-result-form').hide();			
		$('#dup-step1-input-form').show(200);
	};
	
	/** **********************************************
	* METHOD: Shows results of database connection 
	* Timeout (45000 = 45 secs) */
	Duplicator.dlgTestDB = function () {		
		$.ajax({
			type: "POST",
			timeout: 45000,
			url: window.location.href + '?' + 'dbtest=1',
			data: $('#dup-step1-input-form').serialize(),
			success: function(data){ $('#dbconn-test-msg').html(data); },
			error:   function(data){ alert('An error occurred while testing the database connection!  Contact your server admin to make sure the connection inputs are correct!'); }
		});
		
		$('#dbconn-test-msg').html("Attempting Connection.  Please wait...");
		$("#dup-step1-dbconn-status").show(500);
		
	};
	
	Duplicator.showDeleteWarning = function () {
		($('#dbaction-empty').prop('checked')) 
			? $('#dup-step1-warning-emptydb').show(300)
			: $('#dup-step1-warning-emptydb').hide(300);
	};
	
	Duplicator.togglePort = function () {
		
		$('#dup-step1-dbport-btn').hide();
		$('#dbport').show();
	}
	
	
	//DOCUMENT LOAD
	$(document).ready(function() {
		$('#dup-step1-dialog-data').appendTo('#dup-step1-result-container');
		$( "input[name='dbaction']").click(Duplicator.showDeleteWarning);
		Duplicator.acceptWarning();
		Duplicator.showDeleteWarning();		
	});
</script>


<!-- =========================================
VIEW: STEP 1- INPUT -->
<form id='dup-step1-input-form' method="post" class="content-form"  parsley-validate>
	<input type="hidden" name="action_ajax" value="1" />
	<input type="hidden" name="action_step" value="1" />
	<input type="hidden" name="package_name"  value="<?php echo $zip_file_name ?>" />
	
	<div class="dup-logfile-link">
		<select name="logging" id="logging">
		    <option value="1" selected="selected">Light Logging</option>
		    <option value="2">Detailed Logging</option>
			<!--option value="3">Debug Logging</option-->
		</select>
	</div>
	<h3 style="margin-bottom:5px">
	    Step 1: Files &amp; Database
	</h3>
	<hr size="1" />
	
	<!-- CHECKS: FAIL -->
	<?php if ( $total_req == 'Fail')  :	?>
	
		<div class="dup-box">
			<div class="dup-box-title">
				<div id="system-circle" class="circle-fail"></div> &nbsp; Requirements: Fail
				<div class="dup-box-arrow"></div>
			</div>
			<div class="dup-box-panel" style="display:none">	
				<div id="dup-step1-result-container"></div>
			</div> 
		</div><br/>
	
    	<i id="dup-step1-sys-req-msg">
			This installation will not be able to proceed until the system requirements pass. Please validate your system requirements by clicking on the button above. 
			In order to get these values to pass please contact your server administrator, hosting provider or visit the online FAQ.
		</i><br/>
    		    
    	<div style="line-height:28px; font-size:14px; padding:0px 0px 0px 30px; font-weight:normal">
    	    <b>Helpful Resources:</b><br/>
    	    &raquo; <a href="http://lifeinthegrid.com/duplicator-faq" target="_blank">Common FAQs</a> <br/>
    	    &raquo; <a href="http://lifeinthegrid.com/duplicator-guide" target="_blank">User Guide</a> <br/>
    	    &raquo; <a href="http://lifeinthegrid.com/duplicator-hosts" target="_blank">Approved Hosts</a> <br/>
    	</div><br/>
	
	<!-- CHECKS: PASS -->
	<?php else : ?>	
	
	
		<div class="dup-box">
			<div class="dup-box-title">
				<div id="system-circle" class="circle-pass"></div> &nbsp; Requirements: Pass
				<div class="dup-box-arrow"></div>
			</div>
			<div class="dup-box-panel" style="display:none">	
				<div id="dup-step1-result-container"></div>
			</div> 
		</div><br/>
	
    	<div class="title-header">
    	    MySQL Database
    	</div>
    	<table class="dup-step1-inputs">
			<tr>
				<td>Action</td>
				<td>
					<div class="dup-step1-modes">
						<input type="radio" name="dbaction" id="dbaction-create" value="create" checked="checked" />
						<label for="dbaction-create">Create New Database</label>
					</div>
					<div class="dup-step1-modes">
						<input type="radio" name="dbaction" id="dbaction-empty" value="empty" />
						<label for="dbaction-empty">Connect and Remove All Data</label>						
					</div>
				</td>
			</tr>			
    	    <tr>
				<td>Host</td>
				<td>
					<input type="text" name="dbhost" id="dbhost" parsley-required="true" value="<?php echo htmlspecialchars($GLOBALS['FW_DBHOST']); ?>" placeholder="localhost" style="width:410px" />
					<input id="dup-step1-dbport-btn" type="button" onclick="Duplicator.togglePort()" style="" value="Port: <?php echo htmlspecialchars($GLOBALS['FW_DBPORT']); ?>" />
					<input name="dbport" id="dbport" type="text" style="width:80px; display:none" value="<?php echo htmlspecialchars($GLOBALS['FW_DBPORT']); ?>" />
				</td>
			</tr>
			<tr>
				<td>Name</td>
				<td><input type="text" name="dbname" id="dbname"  parsley-required="true" value="<?php echo htmlspecialchars($GLOBALS['FW_DBNAME']); ?>"  placeholder="new or existing database name"  /></td>
			</tr>
			<tr>
				<td>User</td>
				<td><input type="text" name="dbuser" id="dbuser" parsley-required="true" value="<?php echo htmlspecialchars($GLOBALS['FW_DBUSER']); ?>" placeholder="valid database username" /></td>
			</tr>
    	    <tr>
				<td>Password</td>
				<td><input type="text" name="dbpass" id="dbpass" value="<?php echo htmlspecialchars($GLOBALS['FW_DBPASS']); ?>"  placeholder="valid database user password"   /></td>
			</tr>
    	</table>
		
		
		<!-- =========================================
		DIALOG: DB CONNECTION CHECK  -->
		<div id="dup-step1-dbconn">
			<input id="dup-step1-dbconn-btn" type="button" onclick="Duplicator.dlgTestDB()" style="" value="Test Connection" />
			<div id="dup-step1-dbconn-status" style="display:none">
				<div style="padding: 0px 10px 10px 10px;">		
					<div id="dbconn-test-msg" style="min-height:80px"></div>
				</div>
				<small><a href="javascript:void()" onclick="$('#dup-step1-dbconn-status').hide(1000)">Hide Connection Details</a></small>
			</div>
		</div>

    	<!-- !!DO NOT CHANGE/EDIT OR REMOVE THIS SECTION!!
    	If your interested in Private Label Rights please contact us at the URL below to discuss
    	customizations to product labeling: http://lifeinthegrid.com	-->
    	<a href="javascript:void(0)" onclick="$('#dup-step1-cpanel').toggle(250)"><b>Need Setup Help...</b></a>
    	<div id='dup-step1-cpanel' style="display:none">
    	    <div style="padding:10px 0px 0px 10px;line-height:22px">
    		&raquo; Check out the <a href="http://lifeinthegrid.com/duplicator-tutorials" target="_blank">video tutorials &amp; guides</a> <br/>
    		&raquo; Get help from our <a href="http://lifeinthegrid.com/labs/duplicator/resources/" target="_blank">resources page</a>
    	    </div>
    	</div><br/><br/>
    		    
    	<a href="javascript:void(0)" onclick="$('#dup-step1-adv-opts').toggle(250)"><b>Advanced Options...</b></a>
    	<div id='dup-step1-adv-opts' style="display:none">
    	    <table class="dup-step1-inputs">
    		<tr><td colspan="2"><input type="checkbox" name="zip_manual"  id="zip_manual" value="1" /> <label for="zip_manual">Manual package extraction</label></td></tr>
    		<tr><td colspan="2"><input type="checkbox" name="ssl_admin" id="ssl_admin" <?php echo ($GLOBALS['FW_SSL_ADMIN']) ? "checked='checked'" : ""; ?> /> <label for="ssl_admin">Enforce SSL on Admin</label></td></tr>
			<tr><td colspan="2"><input type="checkbox" name="ssl_login" id="ssl_login" <?php echo ($GLOBALS['FW_SSL_LOGIN']) ? "checked='checked'" : ""; ?> /> <label for="ssl_login">Enforce SSL on Login</label></td></tr>
			<tr><td colspan="2"><input type="checkbox" name="cache_wp" id="cache_wp" <?php echo ($GLOBALS['FW_CACHE_WP']) ? "checked='checked'" : ""; ?> /> <label for="cache_wp">Keep Cache Enabled</label></td></tr>
			<tr><td colspan="2"><input type="checkbox" name="cache_path" id="cache_path" <?php echo ($GLOBALS['FW_CACHE_PATH']) ? "checked='checked'" : ""; ?> /> <label for="cache_path">Keep Cache Home Path</label></td></tr>
    		<tr><td colspan="2"><input type="checkbox" name="dbnbsp" id="dbnbsp" value="1" /> <label for="dbnbsp">Fix non-breaking space characters</label></td></tr>
    		<tr><td style="width:130px">MySQL Charset</td><td><input type="text" name="dbcharset" id="dbcharset" value="<?php echo $_POST['dbcharset'] ?>" /> </td></tr>
    		<tr><td>MySQL Collation </td><td><input type="text" name="dbcollate" id="dbcollate" value="<?php echo $_POST['dbcollate'] ?>" /> </tr>
    	    </table>
    	</div>

		<!-- NOTICES  -->
    	<div id="dup-step1-warning">
    	    <b>WARNINGS &amp; NOTICES</b> 
    	    <p>
				<b>Disclaimer:</b> 
				This plugin require above average technical knowledge. Please use it at your own risk and always back up your database and files beforehand using another backup
				system besides the Duplicator. If you're not sure about how to use this tool then please enlist the guidance of a technical professional.  <u>Always</u> test 
				this installer in a sandbox environment before trying to deploy into a production setting.
			</p>    
    	    <p>
				<b>Database:</b>
				Do not connect to an existing database unless you are 100% sure you want to remove all of it's data. Connecting to a database 
				that already exists will permanently DELETE all data in that database. This tool is designed to populate and fill a database with NEW data from a duplicated
				database using the SQL script in the package name above.
			</p>    
    	    <p>
				<b>Setup:</b>
				Only the archive and installer.php file should be in the install directory, unless you have manually extracted the package and checked the 
				'Manual Package Extraction' checkbox. All other files will be OVERWRITTEN during install.  Make sure you have full backups of all your databases and files 
				before continuing with an installation.</p>    
    	    <p>
				<b>Manual Extraction:</b> 
				Manual extraction requires that all contents in the package are extracted to the same directory as the installer.php file.  
				Manual extraction is only needed when your server does not support the ZipArchive extension.  Please see the online help for more details.
			</p>			    
    	    <p>
				<b>After Install:</b>When you are done with the installation remove the installer.php, installer-data.sql and the installer-log.txt files from your directory. 
				These files contain sensitive information and should not remain on a production system.
			</p><br/>
    	</div>
    		    
    	<div id="dup-step1-warning-check">
    	    <input id="accept-warnings" name="accpet-warnings" type="checkbox" onclick="Duplicator.acceptWarning()" /> <label for="accept-warnings">I have read all warnings &amp; notices</label><br/>
			<div id="dup-step1-warning-emptydb">
				The remove action will delete <u>all</u> tables and data from the database!
			</div>
    	</div><br/><br/><br/>
    		    
    	<div class="dup-footer-buttons">
    	    <input id="dup-step1-deploy-btn" type="button" value=" Run Deployment " onclick="Duplicator.runDeployment()" />
    	</div>				
	<?php endif; ?>	
</form>


<!-- =========================================
VIEW: STEP 1 - AJAX RESULT
Auto Posts to view.step2.php  -->
<form id='dup-step1-result-form' method="post" class="content-form" style="display:none">
	<input type="hidden" name="action_step" value="2" />
	<input type="hidden" name="package_name" value="<?php echo $zip_file_name ?>" />
	<input type="hidden" name="logging" id="ajax-logging"  />	
	<input type="hidden" name="dbhost" id="ajax-dbhost" />
	<input type="hidden" name="dbport" id="ajax-dbport" />
	<input type="hidden" name="dbuser" id="ajax-dbuser" />
	<input type="hidden" name="dbpass" id="ajax-dbpass" />
	<input type="hidden" name="dbname" id="ajax-dbname" />
	<input type="hidden" name="json"   id="ajax-json" />
	<input type="hidden" name="dbcharset" id="ajax-dbcharset" />
	<input type="hidden" name="dbcollate" id="ajax-dbcollate" />
	
    <div class="dup-logfile-link"><a href="installer-log.txt" target="_blank">installer-log.txt</a></div>
	<h3>Step 1: Files &amp; Database</h3>
	<hr size="1" />
	    
	<!--  PROGRESS BAR -->
	<div id="progress-area">
	    <div style="width:500px; margin:auto">
		<h3>Processing Files &amp; Database Please Wait...</h3>
		<div id="progress-bar"></div>
		<i>This may take several minutes</i>
	    </div>
	</div>
	    
	<!--  AJAX SYSTEM ERROR -->
	<div id="ajaxerr-area" style="display:none">
	    <p>Please try again an issue has occurred.</p>
	    <div style="padding: 0px 10px 10px 0px;">
			<div id="ajaxerr-data">An unknown issue has occurred with the file and database setup process.  Please see the installer-log.txt file for more details.</div>
			<div style="text-align:center; margin:10px auto 0px auto">
				<input type="button" onclick='Duplicator.hideErrorResult()' value="&laquo; Try Again" /><br/><br/>
				<i style='font-size:11px'>See online help for more details at <a href='http://lifeinthegrid.com/support' target='_blank'>support.lifeinthegrid.com</a></i>
			</div>
	    </div>
	</div>
</form>


<!-- =========================================
PANEL: SERVER CHECKS  -->
<div id="dup-step1-dialog" title="System Status" style="display:none">
<div id="dup-step1-dialog-data" style="padding: 0px 10px 10px 10px;">
	
	<b>Archive Name:</b> <?php echo $zip_file_name; ?> <br/>
	<b>Package Notes:</b> <?php echo empty($GLOBALS['FW_PACKAGE_NOTES']) ? 'No notes provided for this pakcage.' : $GLOBALS['FW_PACKAGE_NOTES']; ?><br/><br/>
					
	<!-- SYSTEM REQUIREMENTS -->
	<b>REQUIREMENTS</b> &nbsp; <i style='font-size:11px'>click links for details</i>
	<hr size="1"/>
	<table style="width:100%">
	<tr>
		<td style="width:300px"><a href="javascript:void(0)" onclick="$('#dup-SRV01').toggle(400)">Root Directory</a></td>
		<td class="<?php echo ($req01 == 'Pass') ? 'dup-pass' : 'dup-fail' ?>"><?php echo $req01; ?></td>
	</tr>
	<tr>
		<td colspan="2" id="dup-SRV01" class='dup-step1-dialog-data-details'>
		<?php
		echo "<i>Path: {$GLOBALS['CURRENT_ROOT_PATH']} </i><br/>";
		printf("<b>[%s]</b> %s <br/>", $req01a, "Is Writable by PHP");
		printf("<b>[%s]</b> %s <br/>", $req01b, "Contains only one zip file<div style='padding-left:70px'>Result = {$zip_file_name} <br/> <i>Note: Manual extraction still requires the archive.zip file</i> </div> ");
		?>
		</td>
	</tr>
	<tr>
		<td>Safe Mode Off</td>
		<td class="<?php echo ($req02 == 'Pass') ? 'dup-pass' : 'dup-fail' ?>"><?php echo $req02; ?></td>
	</tr>
	<tr>
		<td><a href="http://us2.php.net/manual/en/mysqli.installation.php" target="_blank">MySQLi Support</a></td>
		<td class="<?php echo ($req03 == 'Pass') ? 'dup-pass' : 'dup-fail' ?>"><?php echo $req03; ?></td>
	</tr>
	<tr>
		<td valign="top">
		PHP Version: <?php echo phpversion(); ?><br/>
		<i style="font-size:10px">(PHP 5.2.17+ is required)</i>
		</td>
		<td class="<?php echo ($req04 == 'Pass') ? 'dup-pass' : 'dup-fail' ?>"><?php echo $req04; ?> </td>
	</tr>
	</table>

	<!-- SYSTEM CHECKS -->
	<b>CHECKS</b><hr style='margin-top:-2px' size="1"/>
	<table style="width:100%">
	<tr>
		<td style="width:300px"></td>
		<td></td>
	</tr>
	<tr>
		<?php if (stristr($_SERVER['SERVER_SOFTWARE'], 'apache') !== false): ?>
			<td><b>Web Server:</b> Apache</td>
			<td><div class='dup-pass'>Good</div></td>
		<?php elseif (stristr($_SERVER['SERVER_SOFTWARE'], 'LiteSpeed') !== false): ?> 
			<td><b>Web Server:</b> LiteSpeed</td>
			<td><div class='dup-ok'>OK</div></td>
		<?php elseif (stristr($_SERVER['SERVER_SOFTWARE'], 'nginx') !== false): ?> 
			<td><b>Web Server:</b> Nginx</td>
			<td><div class='dup-ok'>OK</div></td>
		<?php elseif (stristr($_SERVER['SERVER_SOFTWARE'], 'lighttpd') !== false): ?> 
			<td><b>Web Server:</b> Lighthttpd</td>
			<td><div class='dup-ok'>OK</div></td>
		<?php elseif (stristr($_SERVER['SERVER_SOFTWARE'], 'iis') !== false): ?> 
			<td><b>Web Server:</b> Microsoft IIS</td>
			<td><div class='dup-ok'>OK</div></td>
		<?php else: ?>
			<td><b>Web Server:</b> Not detected</td>
			<td><div class='dup-fail'>Caution</div></td>
		<?php endif; ?>				
	</tr>
	<tr>
		<?php
		$open_basedir_set = ini_get("open_basedir");
		if (empty($open_basedir_set)):
		?>
			<td><b>Open Base Dir:</b> Off
			<td><div class='dup-pass'>Good</div>
		<?php else: ?>
			<td><b>Open Base Dir:</b> On</td>
			<td><div class='dup-fail'>Caution</div></td>
		<?php endif; ?>
	</tr>
	</table>

	<hr class='dup-dots' />
	<!-- SAPI -->
	<b>PHP SAPI:</b>  <?php echo php_sapi_name(); ?><br/>
	<b>PHP ZIP Archive:</b> <?php echo class_exists('ZipArchive') ? 'Is Installed' : 'Not Installed'; ?> <br/>
	<b>CDN Accessible:</b> <?php echo ( DupUtil::is_url_active("ajax.aspnetcdn.com", 443) && DupUtil::is_url_active("ajax.googleapis.com", 443)) ? 'Yes' : 'No'; ?> <br/>
	Need an <a href='http://lifeinthegrid.com/duplicator-hosts' target='_blank'>approved</a> Duplicator hosting provider?

</div>
</div>



