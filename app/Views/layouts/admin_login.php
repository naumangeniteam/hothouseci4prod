<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= esc($title) ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="<?= esc($description) ?>" />
    <meta name="keywords" content="<?= esc($keyword) ?>">
    <meta name="author" content="Phoenixcoded" />
    <?= $head ?? '' ?>  <!-- Safe check for $head variable -->
</head>
<body>
    <?= $content ?? '' ?>
    <?= $footer_js ?? '' ?>
</body>
</html>
