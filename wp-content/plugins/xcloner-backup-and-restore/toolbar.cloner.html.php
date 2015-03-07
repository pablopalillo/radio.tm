<?php
/**
* XCloner
* Oficial website: http://www.joomlaplug.com/
* -------------------------------------------
* Creator: Liuta Romulus Ovidiu
* License: GNU/GPL
* Email: admin@joomlaplug.com
* Revision: 1.0
* Date: July 2007
**/


/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

function XCloner_button($action, $text='', $js=''){
	if($action == "cancel")
		$icon = "ui-icon-cancel";
	elseif($action == "generate")
		$icon = "ui-icon-arrowthick-1-e";
	elseif($action == "config")
		$icon = "ui-icon-circle-check";
	else
		$icon = "ui-icon-arrowreturnthick-1-e";
	?>
	<script>
	jQuery(function() {
		jQuery( "#<?php echo $text?>" ).button({
										icons:{
											primary: '<?php echo $icon;?>'
											}
									})
									.click(function(){
											document.adminForm.task.value='<?php echo $action?>';document.adminForm.submit();
											})
									.css({ 'text-transform':'uppercase', width: '110px', 'padding-top': '10px', 'padding-bottom': '10px' });
	});
	</script>

	<button id="<?php echo $text?>"><?php echo $text?></button>

	<?php

}

class TOOLBAR_cloner {

  function _LOGIN() {
    XCloner_button('dologin','Login',false);
    XCloner_button('cancel','Cancel',false);
  }
  function _GENERATE() {
    XCloner_button('clone','Clone',false);
    XCloner_button('move','Move',false);
    XCloner_button('view','Back',false);
  }
  function _CONFIRM() {
    XCloner_button('generate','Continue',false);
    XCloner_button('cancel','Cancel',false);
  }
  function _CLONE() {
    XCloner_button('continue','Continue',false);
    XCloner_button('view','Cancel',false);
  }
  function _CONFIG() {
    XCloner_button('config', 'Save');
    XCloner_button('cancel', 'Cancel');
  }

  function _LANG_EDIT() {
    XCloner_button('save_lang_apply','Apply');
    XCloner_button('save_lang', 'Save');
    XCloner_button('cancel_lang', 'Cancel');
  }

  function _LANG_ADD() {
    XCloner_button('add_lang_new', 'New');
    XCloner_button('cancel_lang', 'Cancel');
  }

  function _LANG() {
    XCloner_button('add_lang','New');
	XCloner_button('edit_lang', 'Edit');
    XCloner_button('del_lang', 'Delete');
    XCloner_button('cancel','Cancel');
  }

  function _RENAME() {
    XCloner_button('rename_save', 'Save');
    XCloner_button('rename_cancel', 'Cancel');
  }
  function _VIEW() {
    XCloner_button('clone','Clone',true);
    XCloner_button('move','Move',true);
    XCloner_button('rename','Rename',true);
    XCloner_button('remove','Delete');
    XCloner_button('cancel','Cancel');
  }
  function _DEFAULT() {

    XCloner_button('logout','Logout');
    XCloner_button('cancel','Cancel');

  }
}
?>
