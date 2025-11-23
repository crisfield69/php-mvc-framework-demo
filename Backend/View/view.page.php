
<div class="layout-page">

    <h1>Page : <?= @$page->titre ?></h1>

    <form method="post" enctype="multipart/form-data">

        <input name="id" value="<?= @$page->id ?>" type="hidden">

        <fieldset>

            <legend>Informations générales</legend>

            <p>
                <label>Titre</label>
                <input name="titre" value="<?= @$page->titre ?>" type="text">
            </p>  

            <p class="disabled unselectable">
                <label>Slug</label>
                <input class="unselectable" name="slug" value="<?= @$page->slug ?>" type="text" readonly>
            </p>

            <p>
                <label>Largeur</label>
                <?= @$displayLargeur ?>
            </p>

        </fieldset>

        <?php if($step === 'update') : ?>

        <div class="layout-build-area">
            <fieldset>
                <legend>Corps de page</legend>
                <?= @$page->blocs ?>
            </fieldset>
        </div>

        <fieldset>
            
            <p class="right">
                <span class="button small" data-type="add-button" data-action="add-gallery">+ Gallerie</span>
                <span class="button small" data-type="add-button" data-action="add-image">+ Image</span>
                <span class="button small" data-type="add-button" data-action="add-file">+ Fichier</span>
                <span class="button small" data-type="add-button" data-action="add-text">+ Texte</span>
                <span class="button small" data-type="add-button" data-action="add-code">+ Code</span>
            </p>

        </fieldset>
        
        <?php endif ?>

        <fieldset>
            
            <p class="right">                
                <a class="button light" href="<?= SITE_URL ?>admin/pages">Retour</a>                
                <button type="submit" data-type="submit-button">Enregistrer</button>
            </p>

        </fieldset>
        

    </form>

</div>

<style>    
      .unselectable {
        -webkit-user-select: none;
        -webkit-touch-callout: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;        
      }    
</style>

<script>
    
    window.addEventListener('load', function(){
        
        let disabled = document.querySelector('.disabled');
        let input = disabled.querySelector('input');

        disabled.addEventListener('dblclick', function(){
            if(confirm('Voulez-vous vraiment déverrouiller le slug ?')) {
                input.readOnly = false;
                disabled.classList.remove('disabled');
            }
        }, false);

    }, false);

</script>