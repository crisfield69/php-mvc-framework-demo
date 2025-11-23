<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="<?= SITE_URL ?>public/scripts/cookiebanner/cookiebanner.script.js"></script>
<link rel="stylesheet" href="<?= SITE_URL ?>public/scripts/cookiebanner/cookiebanner.style.css">
<script>
$(document).ready(function() {
    cookieBanner.init();
});     
</script>

<div class="layout-header">
    <header>
        <div class="layout-header-top home">
            <div>
                <h1><a href="<?= SITE_URL ?>">Primevère <span>38<sup>e</sup> salon-rencontres <br>de l'écologie et des alternatives</span></a></h1>
            </div>
            <div>
                <div class="layout-socials">
                    <p class="facebook"><a href="<?= SOCIALS['facebook'] ?>" target="_blank"><img src="<?= SITE_URL ?>public/images/socials/facebook.jpg"></a></p>
                    <p class="instagram"><a href="<?= SOCIALS['instagram'] ?>" target="_blank"><img src="<?= SITE_URL ?>public/images/socials/instagram.jpg"></a></p>
                    <p class="youtube"><a href="<?= SOCIALS['youtube'] ?>" target="_blank"><img src="<?= SITE_URL ?>public/images/socials/youtube.jpg"></a></p>
                </div>
                <div class="layout-spaces">
                    <ul>
                        <?php foreach(SPACES as $libelle => $slug) : ?>
                        <li><a href="<?= SITE_URL.$slug ?>"><?= $libelle ?></a></li>
                        <?php endforeach ?>
                    </ul>
                </div>
                <div class="layout-search">
                    <form action="<?= SITE_URL ?>recherche" method="post" autocomplete="off">
                        <input type="text" name="search" value="" autocomplete="false">
                    </form>
                </div>
            </div>
        </div>
        <div class="layout-navigation">
            <nav>
                <?= $navigation ?>
            </nav>
        </div>
        <?= @$wishlistWidget->header ?>
    </header>
</div>