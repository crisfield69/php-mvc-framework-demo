<div class="layout-delete">

    <h1>Suppression</h1>    

    <?php if(@$element->message) : ?>

        <fieldset>

                <legend><?= @$element->legend ?></legend>
                
                <h2><?= @$element->message ?></h2>
                
                <p class="right" style="margin-top: 30px">

                    <a class="button light" href="<?= SITE_URL ?>admin/<?= @$element->slug ?>">Retour</a>
                    
                </p>

        </fieldset>

    <?php else : ?>

        <fieldset>

            <legend><?= @$element->legend ?></legend>
            
            <h2>Confirmez-vous la suppresion de l'élément : <strong>"<?= @$element->titre ?>"</strong> ?</h2>

            <form method="post">

                <input type="hidden" name="id" value="<?= @$element->id ?>">
                
                <p class="right" style="margin-top: 30px">

                    <a class="button light" href="<?= SITE_URL ?>admin/<?= @$element->slug ?>">Annuler</a>

                    <button class="" type="submit">Supprimer</button>

                </p>

            </form>

        </fieldset>

    <?php endif ?>

</div>