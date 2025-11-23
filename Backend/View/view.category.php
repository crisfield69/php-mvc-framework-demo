
<div class="layout-category">

    <h1>Catégorie : <?= $category->nom ?></h1>

    <form method="post">

        <input name="id" value="<?= @$category->id ?>" type="hidden">        
        <input name="parent_actuel" value="<?= @$category->parent_id ?>" type="hidden">
        <input name="ordre_actuel" value="<?= @$category->ordre ?>" type="hidden">

        <fieldset>

            <legend>Modifier une catégorie</legend>
            
            <p>
                <label>Nom</label>
                <input name="nom" value="<?= @$category->nom ?>" type="text">
            </p>

            <p>
                <label>Parent</label>

                <select name="parent_nouveau">
                    <option value="0">Aucun</option>
                    <?php foreach(@$parents as $parent) : ?>
                    <?php if($parent->id !== $category->id) : ?>                
                    <?php $selected = ($parent->id === $category->parent_id)? ' selected' : '' ?>
                    <option value="<?= $parent->id ?>"<?= $selected ?>><?= $parent->nom ?></option>                
                    <?php endif ?>
                    <?php endforeach ?>
                </select>
            </p>

            <p class="disabled">
                <label>Slug</label>
                <input type="text" name="slug" value="<?= @$category->slug ?>" disabled>
            </p>
            
            <p class="right">
                <a class="button light" href="<?= SITE_URL ?>admin/categories">Annuler</a>
                <button class="" type="submit">Modifier</button>    
            </p>

        </fieldset>

    </form>

</div>