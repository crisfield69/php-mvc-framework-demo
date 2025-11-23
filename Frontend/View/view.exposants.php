
<div class="layout-exposants">

    <section>       

        <div class="layout-programme">
            <h1>Exposants</h1>
            <p><a href="<?= SITE_URL ?>uploads/pdf/programme.pdf" target="_blank">Télécharger le programme</a></p>
            <p><a href="<?= SITE_URL ?>uploads/pdf/plan.pdf" target="_blank">Télécharger le plan</a></p>            
        </div>        

        <div class="layout-breadcrumb"><?= @$breadcrumb ?></div>

        <div class="layout-panel cols">
            
            <div class="layout-categories" data-type="category-slugs-list"><?= @$categories ?></div>  
            
            <div class="layout-exposants-list" data-type="slugs-list"><?= @$exposants ?></div>

            <div class="layout-exposant-single" data-type="single-content"><?= @$exposant ?></div>

            <?= @$void ?>
            
        </div> 

    </section>
    
</div>