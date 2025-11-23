
<div class="layout-conferences">

    <section>

        <h1>Conférences</h1>

        <div class="layout-area">

            <div class="layout-list">

                <?php foreach($conferences as $conference) : ?>

                    <div class="layout-conference" data-title="<?= $conference->titre ?>" data-subtitle="<?= $conference->soustitre ?>">
                        <div class="layout-photo"><?= @$conference->photo ?></div>
                        <h3><?= $conference->titre ?></h3>
                        <h4><?= $conference->soustitre ?></h4>
                        <!--<div class="layout-audio"><?= @$conference->audio ?></div>-->
                        <a href="<?= SITE_URL ?>uploads/conferences/mp3/<?= @$conference->fichier ?>" target="_blank"></a>
                    </div>

                <?php endforeach ?>

            </div>

            <div class="layout-viewer"></div>

        </div>
        
    </section>
    
</div>