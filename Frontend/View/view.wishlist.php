<div class="layout-wishlist-list">

    <h1>Mes favoris</h1>

    <?php if(!empty($programmes) || !empty($exposants)) : ?>
    
        <p data-button="print-wishlist" class="wisshlist-print-button"><i class="las la-print"></i></p>

    <?php endif ?>

    
    <?php if(!empty($programmes)) : ?>
    
    <div class="layout-programmes">

        <h2>Programmes</h2>

        <ul>
        <?php foreach(@$programmes as $programme) : ?>
            <li>
                <div>
                    <h3><?= $programme->titre ?></h3>
                    <div><?= $programme->texte ?></div>
                    
                    <div>
                        <p><strong>Date(s), horraire(s) et lieu(x)</strong></p>

                        <?php for($i=0; $i<count(@$programme->horraires); $i++) : ?>
                            <p><?= @$programme->horraires[$i]->date . ' | ' . @$programme->horraires[$i]->time . ' | ' . @$programme->lieux[$i] ?></p>
                        <?php endfor ?>

                    </div>

                </div>
                <p data-button="remove-wishlist" data-slug="<?= $programme->slug ?>">x</p>
            </li>
        <?php endforeach ?>
        </ul>

    </div>

    <?php endif ?>


    <?php if(!empty($exposants)) : ?>

    <div class="layout-exposants">

        <h2>Exposants</h2>

        <ul>
        <?php foreach(@$exposants as $exposant) : ?>
            <li>
                <div>
                    <h3><?= $exposant->name ?></h3>
                    <div><?= $exposant->description ?></div>
                </div>
                <p data-button="remove-wishlist" data-slug="<?= $exposant->slug ?>">x</p>
            </li>
        <?php endforeach ?>
        </ul>

    </div>

    <?php endif ?>

  
          
    <div class="layout-content">
    <?= $content ?>
    </div>
    

</div>