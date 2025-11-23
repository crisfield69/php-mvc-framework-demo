<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    
    <link rel="stylesheet" href="<?= SITE_URL ?>public/styles/line-awesome.css<?= RND ?>">
    <link rel="stylesheet" href="<?= SITE_URL ?>public/styles/frontend/fonts.css<?= RND ?>">
    <link rel="stylesheet" href="<?= SITE_URL ?>public/styles/backend/styles.css<?= RND ?>">

    <?php if( isset($styles) ) : ?>
    <link rel="stylesheet" href="<?= SITE_URL ?>public/styles/backend/<?= $styles ?>.css<?= RND ?>">
    <?php endif ?>

    <?php if( isset($ckeditor)) : ?>
    <script src="<?= SITE_URL ?>public/scripts/backend/ckeditor/ckeditor.js"></script>
    <?php endif ?>

    <script>const SITE_URL = '<?= SITE_URL ?>';</script>
    <script src="<?= SITE_URL ?>public/scripts/backend/scripts.js<?= RND ?>" type="module"></script>

    <title><?= $title ?></title>

</head>

<body>
    <div class="layout-root">
        <div class="layout-header">
            <header>
                <div class="layout-navigation">
                    <?= $navigation ?>
                </div>
                <div class="layout-disconnect"><a class="button small" href="<?= SITE_URL ?>admin/disconnect">x</a></div>
            </header>
        </div>
        <div class="layout-main">
            <main>