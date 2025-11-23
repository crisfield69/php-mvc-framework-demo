
<div class="layout-gallery">

    <h1>Galerie : <?= @$gallery->nom ?></h1>

    <form method="post" enctype="multipart/form-data">

        <input name="gallery_id" value="<?= @$gallery->id ?>" type="hidden">

        <fieldset>

            <legend>Informations générales</legend>

            <p>
                <label>Nom</label>
                <input name="nom" value="<?= @$gallery->nom ?>" type="text" required>
            </p>

        </fieldset>
        
        <?php if($step === 'update') : ?>
        
            <div class="update">

                <fieldset>

                    <p>
                        <label>Ajouter photos</label>                        
                        <input type="file" name="photos[]" multiple>
                    </p>

                    <p>
                        <label>Type</label>
                        <?= $typesSelect ?>
                    </p>
                    
                    <p>
                        <label>Hauteur</label>
                        <input type="text" name="hauteur" value="<?= @$gallery->hauteur ?>">
                    </p>

                    <div class="parameters">

                        <p>
                            <label>Colonnes</label>
                            <?= $colonnesSelect  ?>
                        </p>

                        <p>
                            <label>Marges</label>
                            <?= $margesSelect ?>
                        </p>

                    </div>

                </fieldset>

                <fieldset>
                
                    <div class="gallery">

                    <?php foreach($photos as $photo) : ?>

                        <div class="photo" data-id="<?= @$photo->id ?>">
                            <div>
                                <p data-id="<?= @$photo->id ?>"><</p>
                                <p data-id="<?= @$photo->id ?>">></p>
                                <p data-id="<?= @$photo->id ?>">-</p>
                                <a href="<?= @$urlLarge . @$photo->filename ?>" target="_blank"><img src="<?= @$urlSmall . @$photo->filename . RND ?>"></a>
                            </div>
                            <div>
                                <input name="titres[]"  type="text"  value="<?= @$photo->titre ?>" data-type="title" placeholder="Titre">
                                <textarea name="textes[]" data-type="text" placeholder="Texte"><?= @$photo->texte ?></textarea>
                                <input name="liens[]"  type="text"  value="<?= @$photo->lien ?>" data-type="link" placeholder="Lien">
                                <input name="ordres[]" type="hidden" value="<?= @$photo->ordre ?>" data-type="order">
                                <input name="ids[]" type="hidden" value="<?= @$photo->id ?>" data-type="id">
                            </div>
                        </div>

                    <?php endforeach ?>

                    </div>

                </fieldset>

            </div>

        <?php endif ?>

        <fieldset>
            
            <p class="right">
                <a class="button light" href="<?= SITE_URL ?>admin/galeries">Retour</a>
                <button type="button" data-type="submit">Enregistrer</button>
            </p>

        </fieldset>

    </form>

</div>

<script>
    window.addEventListener('load', function(){

        <?php $typesWithParameters = [4, 5] ?>

        let parameters  =   document.querySelector('.parameters');
        if(!parameters) return;

        let type        =   document.querySelector('select[name="type"]');
        let colonnes    =   document.querySelector('select[name="colonnes"]');
        let marges      =   document.querySelector('select[name="marges"]');
        let typesWithParameters = <?= json_encode($typesWithParameters) ?>;

        <?php if(!in_array(intval(@$gallery->type), $typesWithParameters)) : ?>
        parameters.style.display = 'none';
        <?php endif ?>
        
        type.addEventListener('change', function(event){
            let type = event.target;
            if(typesWithParameters.includes(parseInt(type.value))) {
                parameters.style.display = 'block';
            }
            else{
                parameters.style.display = 'none';
                colonnes.options[0].selected = true;
                marges.options[0].selected = true;
            }
        }, false);
        
    }, false);
</script>