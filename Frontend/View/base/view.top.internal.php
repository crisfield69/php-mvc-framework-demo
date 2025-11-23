<div class="layout-header">
    <header>
        <div class="layout-header-top internal">

            <div class="layout-top">

                <a class="layout-home" href="<?= SITE_URL ?>"><img src="<?= PUBLIC_URL ?>images/logo-retour-accueil.png"></a>

                <div class="layout-spaces">
                    <ul>
                        <?php foreach(SPACES as $libelle => $slug) : ?>
                        <li><a href="<?= SITE_URL.$slug ?>"><?= $libelle ?></a></li>
                        <?php endforeach ?>
                    </ul>
                </div>

            </div>
            
            <div class="layout-middle">
                <div class="layout-navigation">
                    <nav>
                        <?= $navigation ?>
                    </nav>
                </div>
            </div>

            <div class="layout-bottom">

                <?= @$wishlistWidget->header ?>

                <div class="layout-search">
                    <form action="<?= SITE_URL ?>recherche" method="post" autocomplete="off">
                        <input type="text" name="search" value="" autocomplete="false">
                    </form>
                </div>

                <div class="layout-socials">
                    <p class="facebook"><a href="<?= SOCIALS['facebook'] ?>" target="_blank"><img src="<?= SITE_URL ?>public/images/socials/facebook.jpg"></a></p>
                    <p class="instagram"><a href="<?= SOCIALS['instagram'] ?>" target="_blank"><img src="<?= SITE_URL ?>public/images/socials/instagram.jpg"></a></p>
                    <p class="youtube"><a href="<?= SOCIALS['youtube'] ?>" target="_blank"><img src="<?= SITE_URL ?>public/images/socials/youtube.jpg"></a></p>
                </div>

            </div>
            
        </div>
        
    </header>
</div>