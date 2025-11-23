<select data-type="order" data-id="<?= $conference->id ?>" data-current-order="<?= $conference->ordre ?>">
    <?php foreach($ordres as $ordre) : ?>
    <?php $selected = ($ordre == $conference->ordre)? ' selected' : '' ?>    
    <option value="<?= $ordre ?>"<?= $selected ?>><?= $ordre ?></option>
    <?php endforeach ?>
</select>