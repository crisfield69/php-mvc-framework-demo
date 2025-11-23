
<h1>Mes favoris</h1>

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
            </li>
        <?php endforeach ?>
        </ul>

    </div>

    <?php endif ?>