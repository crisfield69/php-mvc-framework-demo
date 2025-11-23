
<div class="layout-programmation-home">

    <section>     

        <div class="layout-programme">
            <p>Programme</p>
            <p><a href="<?= SITE_URL ?>uploads/pdf/programme.pdf" target="_blank">Télécharger le programme</a></p>
            <p><a href="<?= SITE_URL ?>uploads/pdf/plan.pdf" target="_blank">Télécharger le plan</a></p>            
        </div>

        <div class="layout-jours">
            <p>Par jour</p>
            <div>
                <?php foreach(@$jours as $jour) : ?>
                <p><a href="<?= @$jour->lien ?>"><?= @$jour->libelle ?></a></p>
                <?php endforeach ?>
            </div>
        </div>

        <div class="layout-thematique">
            <div class="layout-content">
                <p>Par thématique</p>
                <?= @$content ?>
            </div>
        </div>

        
    </section>
    
</div>