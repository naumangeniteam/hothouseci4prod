<!-- Favicon icon -->
<link rel="icon" href="<?= esc($ASSET_INCLUDE_URL) ?>image/fav-icon.png" type="image/x-icon">
<!-- vendor css -->
<link rel="stylesheet" href="<?= esc($ASSET_INCLUDE_URL) ?>css/style.css">
<link href="<?= esc($ASSET_INCLUDE_URL) ?>css/manoj.css" id="theme" rel="stylesheet">
<script type="text/ecmascript">
var BASEURL 			=	"<?= $BASE_URL?>";
var FULLSITEURL 		=	'<?= esc($FULL_SITE_URL) ?>';
var ASSETURL 			=	'<?= esc($ASSET_URL) ?>';
var ASSETINCLUDEURL 	=	'<?= esc($ASSET_INCLUDE_URL) ?>';
var CURRENTCLASS 		=	'<?= esc($CURRENT_CLASS) ?>';
var CURRENTMETHOD 		=	'<?= esc($CURRENT_METHOD) ?>';
var csrf_api_key = '<?= csrf_token() ?>';
var csrf_api_value = '<?= csrf_hash() ?>';

</script>