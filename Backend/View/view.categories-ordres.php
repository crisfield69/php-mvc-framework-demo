
<select data-type="ordre" data-id="<?= $category->id ?>" data-ordre="<?= $category->ordre ?>">
    <?php foreach($ordres as $ordre) : ?>
    <?php $selected = ($ordre === $category->ordre)? ' selected' : '' ?>    
    <option value="<?= $ordre ?>"<?= $selected ?>><?= $ordre ?></option>
    <?php endforeach ?>
</select>