<?php $previous_date = null ?>

<?php foreach(@$programmes as $programme) : ?>

    <?php if($programme->date !== $previous_date) : ?>

        <?php if($previous_date !== null) : ?>
        </ul>
        <?php endif ?>

        <h3><?= $programme->date ?></h3>
        <ul>

    <?php endif ?>
        
        <li data-type="slug" data-slug="<?= $programme->slug ?>">
            <h4><a href="<?= $programme->url ?>"><span><?= $programme->heure ?></span><span> : </span><span><?= $programme->titre ?></span></a></h4>
            <p>Intervenant-e(s) : <?= $programme->intervenant ?></p>
            <p><?= $programme->forme ?> <?php if($programme->duree) : ?>(<?= $programme->duree ?>)<?php endif ?></p>
        </li>

        <?php $previous_date = $programme->date ?>

<?php endforeach ?>

