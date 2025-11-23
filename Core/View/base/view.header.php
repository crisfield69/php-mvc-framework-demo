<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="<?= SITE_URL ?>public/styles/frontend/fonts.css<?= RND ?>">     
    <link rel="stylesheet" href="<?= SITE_URL ?>public/styles/frontend/styles.css<?= RND ?>">

    <?php if( isset($styles) ) : ?>
    <link rel="stylesheet" href="<?= SITE_URL ?>public/styles/frontend/<?= $styles ?>.css<?= RND ?>">
    <?php endif ?>

    <script src="<?= SITE_URL ?>public/scripts/frontend/scripts.js<?= RND ?>" type="module"></script>

    <title><?= $title ?></title>

</head>

<body>
    <div class="layout-root">
        <div class="layout-header">
            <header>
                <div class="layout-navigation">
                    <?= $navigation ?>
                </div>
            </header>
        </div>
        <div class="layout-main">
            <main>