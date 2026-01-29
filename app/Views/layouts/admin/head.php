<!-- Favicon icon -->
<link rel="icon" href="<?= esc($ASSET_INCLUDE_URL) ?>image/favicon.jpg" type="image/x-icon">
<!-- vendor css -->
<link rel="stylesheet" href="<?= esc($ASSET_INCLUDE_URL) ?>css/style.css">
<link href="<?= esc($ASSET_INCLUDE_URL) ?>css/manoj.css" id="theme" rel="stylesheet">
<!-- data tables css -->
<link rel="stylesheet" href="<?= esc($ASSET_INCLUDE_URL) ?>css/plugins/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= esc($ASSET_INCLUDE_URL) ?>css/chosen.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/perfect-scrollbar/1.5.5/css/perfect-scrollbar.min.css">
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
<script src="<?= esc($ASSET_INCLUDE_URL) ?>js/vendor-all.min.js"></script>
<script src="<?= esc($ASSET_INCLUDE_URL) ?>js/chosen.jquery.min.js"></script>
<script src="<?= esc($ASSET_INCLUDE_URL) ?>js/plugins/bootstrap.min.js"></script>
<!-- Perfect Scrollbar JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/perfect-scrollbar/1.5.5/perfect-scrollbar.min.js"></script>
<!-- Add DOMPurify here -->
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.3/dist/purify.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var sidebar = document.querySelector(".scroll-div");
        if (sidebar) {
            new PerfectScrollbar(sidebar);
        }

        var customTables = document.querySelectorAll(".custom-data-table-wrapper2");
        customTables.forEach(function (table) {
            new PerfectScrollbar(table);
        });
    });
</script>
<style style="css">
 .custom-data-table-wrapper1,
  .custom-data-table-wrapper2 {
    width: 100%;
    overflow: auto;
  }

  .custom-data-table-wrapper1 {
    background: #fff;
    height: 20px;
  }

  .custom-data-table-wrapper2::-webkit-scrollbar {
    display: none;
  }

  .custom-data-table-wrapper2 {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
  
</style>