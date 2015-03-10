jQuery(document).ready(function() {

	var globalUrl;
	var step = "r1";
	var count = 0;
	var counter = 0;
	var counter_old = 0;

	jQuery("#progressbar").progressbar({ value: 0 });

	jQuery.ajaxSetup({
	"error":function(request, status, error) {
	//reset state here;
		jQuery("#error").show();
		jQuery("#errorText").append(status+" -- "+error);
		jQuery("#errorText").append("<br /><br />JSON url: "+globalUrl);
	}});

	function getSize(bytes, conv){

		return (bytes/conv).toFixed(2);

		}
	function appendIcon(icon){

		return '<span class="ui-icon ui-icon-'+icon+'" style="float:left;"></span>';

		}

	function xclonerRecurseMYSQL(url){

		globalUrl = url;
		step = "r1";

		jQuery.getJSON(url, function(json) {

		if(!json){
			jQuery("#error").show();
			jQuery("#errorText").text(url);
		}

		if(json.dumpsize && !json.endDump){
					jQuery("#mysqlProcess").append(" ("+getSize(json.dumpsize, 1024*1024)+" MB) <br />");
				}

		if(json.newDump){
				count++;
				//jQuery("#mysqlProcess").append(appendIcon("arrowthick-1-e"));
				if(json.databaseName!="")
					jQuery("#mysqlProcess").append("<b>["+json.databaseName+"]</b> <span id='db"+count+"'></span> tables ");
				counter = parseInt(json.startAtLine);

		}else{
				jQuery("#db"+count).text(json.startAtLine - counter);
			}

		if(!parseInt(json.finished)){
		//get next records

			jQuery("#db"+count).text(json.startAtLine - counter);

			recurseUrl = "../wp-content/plugins/xcloner-backup-and-restore/index2.php?task=recurse_database&nohtml=1&dbbackup_comp="+json.dbbackup_comp+"&dbbackup_drop="+json.dbbackup_drop+"&startAtLine="+json.startAtLine+"&startAtRecord="+json.startAtRecord+"&dumpfile="+json.dumpfile;
			xclonerRecurseMYSQL(recurseUrl);

			}
		else{

			jQuery("#fileSystem").show();
			var recurseUrl="../wp-content/plugins/xcloner-backup-and-restore/index2.php?task=recurse_files&mode=start&nohtml=1";
			xclonerRecurseJSON(recurseUrl);

			}


		});
	}

	function xclonerRecurseJSON(url){

		jQuery("#result").hide();

		globalUrl = url;
		step = "r2";

		jQuery.getJSON(url, function(json) {

		if(!json){
			jQuery("#error").show();
			jQuery("#errorText").text(url);
		}

		if(!parseInt(json.finished)){

			jQuery("#recurseStatus").text(json.tfiles);

			var recurseUrl = "../wp-content/plugins/xcloner-backup-and-restore/index2.php?task=recurse_files&mode="+json.mode+"&nohtml=1";
			xclonerRecurseJSON(recurseUrl);

			}
		else{
			var size = parseFloat(json.size)/(1024*1024);
			jQuery("#recurseStatus").text(" done! (Estimated size:"+size.toFixed(2)+"MB) in "+json.tfiles+" files");
			jQuery("#result").show();
			returnUrl = "plugins.php?page=xcloner_show&option=com_cloner&lines="+json.tfiles+"&task=refresh&backup="+backupFile+"&excl_manual=";
			xclonerGetJSON(returnUrl);

			}


		});
	}

	function xclonerGetJSON(url){

	globalUrl = url;
	step = "r3";

	jQuery.getJSON(url, function(json) {

		if(!json){
			jQuery("#error").show();
			jQuery("#errorText").append(url);
		}

		var percent = parseInt(json.percent);
		jQuery("#progressbar").progressbar({ value: percent });
		jQuery("#backupSize").text(json.backupSize);
		jQuery("#nFiles").text(json.startf);
		jQuery("#percent").text(json.percent);
		if(!json.finished){
			var url = "plugins.php?page=xcloner_show&option="+json.option+"&task="+json.task+"&json="+json.json+"&startf="+json.startf+"&lines="+json.lines+"&backup="+json.backup+"&excl_manual="+json.excl_manual;
			xclonerGetJSON(url);
		}else{

			jQuery("#complete").show();
			jQuery("#nFiles").text(json.lines);
			jQuery("#backupFiles").text(json.lines);
			jQuery("#backupSizeComplete").text(json.backupSize);
			jQuery("#backupName").text(json.backup);
			jQuery( "#dialog:ui-dialog" ).dialog( "destroy" );
			jQuery( "#dialog-message" ).dialog({
				modal: true,
				width: 600,
				buttons: {
					Close: function() {
						jQuery( this ).dialog( "close" );
					}
				}
			});

		}

	});

	}

	jQuery("#retry").click(function(){
		jQuery("#error").hide();
		jQuery("#errorText").empty();
		if(step == "r1"){
			xclonerRecurseMYSQL(globalUrl);
		}
		else
		if(step == "r2"){
			xclonerRecurseJSON(globalUrl);
		}
		else if(step == "r3"){
			xclonerGetJSON(globalUrl);
		}
	});

});
