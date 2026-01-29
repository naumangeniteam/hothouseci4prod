<script src="<?= esc($ASSET_INCLUDE_URL) ?>js/ripple.js"></script>
<script src="<?= esc($ASSET_INCLUDE_URL) ?>js/pcoded.min.js"></script>
<script src="<?= esc($ASSET_INCLUDE_URL) ?>js/menu-setting.min.js"></script>
<script src="<?= esc($ASSET_INCLUDE_URL) ?>js/jquery.validate.js"></script>
<!-- notification Js -->
<script src="<?= esc($ASSET_INCLUDE_URL) ?>js/plugins/bootstrap-notify.min.js"></script>
<script src="<?= esc($ASSET_INCLUDE_URL) ?>js/manoj.js"></script>	
<!-- datatable Js -->
<script src="<?= esc($ASSET_INCLUDE_URL) ?>js/plugins/jquery.dataTables.min.js"></script>
<script src="<?= esc($ASSET_INCLUDE_URL) ?>js/plugins/dataTables.bootstrap4.min.js"></script>

<script src="<?= esc($ASSET_INCLUDE_URL) ?>js/ckeditor/ckeditor.js" type="text/javascript"></script>
<script src='<?= esc($ASSET_INCLUDE_URL) ?>js/chosen.jquery.min.js'></script>
<script type="text/javascript">
function create_editor_for_textarea(textareaid)
{	
	if (document.getElementById(textareaid)) {
		// Replace the <textarea id="Description"> with a CKEditor
		// instance, using default configuration.
		CKEDITOR.replace(textareaid, {filebrowserBrowseUrl :'<?= esc($ASSET_INCLUDE_URL) ?>js/ckeditor/filemanager/browser/default/browser.html?Connector=<?= esc($ASSET_INCLUDE_URL) ?>js/ckeditor/filemanager/connectors/php/connector.php',
		filebrowserImageBrowseUrl : '<?= esc($ASSET_INCLUDE_URL) ?>js/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=<?= esc($ASSET_INCLUDE_URL) ?>js/ckeditor/filemanager/connectors/php/connector.php',
		filebrowserFlashBrowseUrl :'<?= esc($ASSET_INCLUDE_URL) ?>js/ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector=<?= esc($ASSET_INCLUDE_URL) ?>js/ckeditor/filemanager/connectors/php/connector.php',
		filebrowserUploadUrl :'<?= esc($ASSET_INCLUDE_URL) ?>js/ckeditor/filemanager/connectors/php/upload.php?Type=File',
		filebrowserImageUploadUrl : '<?= esc($ASSET_INCLUDE_URL) ?>js/ckeditor/filemanager/connectors/php/upload.php?Type=Image',
		filebrowserFlashUploadUrl : '<?= esc($ASSET_INCLUDE_URL) ?>js/ckeditor/filemanager/connectors/php/upload.php?Type=Flash',
		allowedContent:true,
		height: '200px'/*,
		toolbar: [
				{ name: 'document'},	// Defines toolbar group with name (used to create voice label) and items in 3 subgroups.
				[ 'Cut', 'Copy', 'Paste',],			// Defines toolbar group without name.
				{ name: 'basicstyles', items: [ 'Bold', 'Italic' ] }
			]*/
		});	
	}
}

window.onload = function() {
    create_editor_for_textarea('description'); // Call this function on page load
    create_editor_for_textarea('summary'); // Call this function on page load
    create_editor_for_textarea('artist_bio'); // Call this function on page load
};
</script>