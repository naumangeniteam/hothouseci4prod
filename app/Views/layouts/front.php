<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= esc($title ?? '') ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?= esc($description ?? '') ?>">
    <meta name="keywords" content="<?= esc($keyword ?? '') ?>">
    <meta name="author" content="Phoenixcoded">
    
    <?= $head ?? '' ?>
</head>

<?php
// Function to get slug (moved to a helper or included in the view)
function get_slug() {
    return basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
}
$slug = get_slug();
?>
<!-- in ci3 after navigation 
<body class="<?php //if($slug==''){ echo 'home'; } else{ echo htmlspecialchars($slug);} ?>"> -->
<body class="<?= $slug == '' ? 'home' : esc($slug) ?>">

<!-- Pre-loader -->
<div class="loader-bg">
    <div class="loader-track">
        <div class="loader-fill"></div>
    </div>
</div>

<!-- Navigation -->
<?= $menu ?? '' ?>
<?= $navigation ?? '' ?>

<!-- Main Content -->
<?= $content ?? '' ?>

<!-- Footer -->
<?= $footer ?? '' ?>

<!-- Footer JS -->
<?= $footer_js ?? '' ?>

</body>
</html>
