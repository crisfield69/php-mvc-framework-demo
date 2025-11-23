<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link SameSite="none" rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

    <link rel="stylesheet" href="<?= SITE_URL ?>public/styles/frontend/fonts.css<?= RND ?>">
    <link rel="stylesheet" href="<?= SITE_URL ?>public/styles/line-awesome.css<?= RND ?>">
    <link rel="stylesheet" href="<?= SITE_URL ?>public/styles/frontend/styles.css<?= RND ?>">
    
    <?php if( isset($styles) ) : ?>
    <link rel="stylesheet" href="<?= SITE_URL ?>public/styles/frontend/<?= $styles ?>.css<?= RND ?>">
    <?php endif ?>

    <script>const SITE_URL="<?= SITE_URL ?>";</script>
    <script src="<?= SITE_URL ?>public/scripts/frontend/scripts.js<?= RND ?>" type="module"></script> 

    <title><?= $title ?></title>
</head> 

<body>
    <div class="layout-navigation-mobile">
        
        <div>    
            <h1><a href="<?= SITE_URL ?>">Primevère <span>38<sup>e</sup> salon-rencontres <br>de l'écologie et des alternatives</span></a></h1>
            <button></button>
        </div>

        <div>
            
            <nav>                

                <ul>
                    <li><a href="<?= SITE_URL ?>uploads/pdf/programme.pdf">Télécharger le programme</a></li>
                    <li><a href="<?= SITE_URL ?>uploads/pdf/plan.pdf">Télécharger le plan</a></li>
                </ul>

                <?= $navigation ?>

                <ul>
                    <?php foreach(SPACES as $libelle => $slug) : ?>
                    <li><a href="<?= SITE_URL.$slug ?>"><?= $libelle ?></a></li>
                    <?php endforeach ?>
                </ul>

                <ul>
                    <?php foreach(SOCIALS as $libelle => $url) : ?>
                    <li><a href="<?= $url ?>" target="_blank"><?= ucfirst($libelle) ?></a></li>
                    <?php endforeach ?>
                </ul>

                <div class="layout-search">
                    <form action="<?= SITE_URL ?>recherche" method="post" autocomplete="off">
                        <input type="text" name="search" value="" autocomplete="false">
                        <button type="submit">Rechercher</button>
                    </form>
                </div>
                
            </nav>

        </div>
    </div>

    <div class="layout-root <?= @$styles ?><?= @$type ?>">
        <?php 
        
            if($styles==='home') {
                require __DIR__ . '/view.top.home.php';
            }
            else {
                require __DIR__ . '/view.top.internal.php';
            }
        ?>
        <div class="layout-main">
            <main>

            <div class="layout-col-left">
                <a href="<?= SITE_URL ?>" class="layout-logo">Primevère</a>                    
            </div>

            <div class="layout-col-right">
