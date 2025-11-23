<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <title>Exposants</title>
</head>

<body>
    <div class="exposants">
        <?php foreach($exposants as $exposant) : ?>
    
        <div class="exposant">
            <h2><?= $exposant['name'] ?> : <?= $exposant['id'] ?></h2>
            <?php foreach ($exposant['images'] as $image) : ?>

                <figure>
                    <a href="<?= $image ?>" target="_blank"><img src="<?= $image ?>"></a>
                </figure>

            <?php endforeach ?>
        </div>

        <?php endforeach ?>
    </div>
</body>

</html>