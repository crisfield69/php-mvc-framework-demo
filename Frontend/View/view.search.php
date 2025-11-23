<div class="layout-search">

    <section>

        <h1>Résultats de recherche</h1>
        <h2>Recherche : "<?= $recherche ?>" - <?= $nb_resultats ?> résultats</h2>        
        
        <?php if(!empty($resultats['exposants'])) : ?>
        
            <div class="layout-resultats">
                
                <h4>Dans le rubrique "Exposants"</h4>
                
                <ol>
                    <?php foreach($resultats['exposants'] as $resultat) : ?>
                    <li>
                        <h3><a href="<?= $resultat->url ?>" target="_blank"><?= $capitalize($resultat->stand_name) ?></a></h3>
                        <div><?= $resultat->paper_introduction ?></div>
                    </li>
                    <?php endforeach ?>
                </ol>

            </div>

        <?php endif ?>


        <?php if(!empty($resultats['programmation'])) : ?>

            <div class="layout-resultats">
                
                <h4>Dans la rubrique "Programmation"</h4>
                
                <ol>
                    <?php foreach($resultats['programmation'] as $resultat) : ?>
                    <li>
                        <h3><a href="<?= $resultat->url ?>" target="_blank"><?= $capitalize($resultat->titre) ?></a></h3>
                        <div><?= $resultat->texte ?></div>
                    </li>
                    <?php endforeach ?>
                </ol>

            </div>

        <?php endif ?>

    </section>

</div>


