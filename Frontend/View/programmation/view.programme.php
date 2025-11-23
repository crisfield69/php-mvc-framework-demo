<section>
    
    <h1 data-id="<?= @$programme->id_horaire ?>">
       <?= @$programme->titre ?>
       <?= @$wishlistWidget->main ?>
    </h1>
     
    <div>
        <p>Forme: <?= @$programme->forme ?> - Durée: <?= @$programme->duree ?></p>    
        <p><strong>Intervenant-e(s) : <?= @$programme->intervenant_long ?></strong></p>
    </div>

    <div class="photo"><?= @$programme->photo ?></div>

    <div><?= @$programme->texte ?></div>

    <?php if(count(@$programme->horraires)) : ?>
    <div>
        <p><strong>Date(s), horaire(s) et lieu(x)</strong></p>
        
        <?php for($i=0; $i<count(@$programme->horraires); $i++) : ?>

            <p><?= @$programme->horraires[$i]->date . ' | ' . @$programme->horraires[$i]->time . ' | ' . @$programme->lieux[$i] ?></p>

        <?php endfor ?>
    </div>

    <?php else : ?>

        <?php if(count(@$programme->lieux)) : ?>
            <div>
                <p><strong>Lieu(x)</strong></p>
                <?php for($i=0; $i<count(@$programme->lieux); $i++) : ?>
                    <p><?= @$programme->lieux[$i] ?></p>
                <?php endfor ?>
            </div>
        <?php endif ?>
        
    <?php endif ?>

    <div>
        <p><strong>Contact(s) : </strong></p>
        <div><?= trim(@$programme->contact, '"') ?></div>
    </div>

</section>