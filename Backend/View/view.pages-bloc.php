<?php switch($type) : 
    case 'image' : ?>
    <div class="build-bloc" data-num="<?= $num ?>" data-type="image">
        <div class="bloc-name">
            <label>Bloc image</label>
        </div>
        <div>
            <div>
                <div><?= @$displayContent ?></div>
                <div>
                    <input type="hidden" name="image_<?= $num ?>" data-type="image" value="<?= @$brutContent ?>">
                    <input type="file" name="image_<?= $num ?>" data-type="image">
                </div>
            </div>
            <div> 
                <?= @$displayMarge ?>
                <?= @$displayOrdre ?>
                <button data-type="delete-button" data-num="<?= $num ?>" type="button" class="small">-</button>
            </div>
        </div>
    </div>
<?php break ?>
<?php case 'file' : ?>
    <div class="build-bloc" data-num="<?= $num ?>" data-type="file">
        <div class="bloc-name">
            <label>Bloc fichier</label>
        </div>
        <div>
            <div>
                <div><?= @$displayContent ?></div>
                <div>
                    <input type="hidden" name="file_<?= $num ?>" data-type="file" value="<?= @$brutContent ?>">
                    <input type="text" name="file_text_<?= $num ?>" data-type="file">
                    <input type="file" name="file_<?= $num ?>" data-type="file">
                </div>
            </div>
            <div>
                <?= @$displayMarge ?>
                <?= @$displayOrdre ?>
                <button data-type="delete-button" data-num="<?= $num ?>" type="button" class="small">-</button>
            </div>
        </div>
    </div>
<?php break ?>
<?php case 'text' : ?>
    <div class="build-bloc" data-num="<?= $num ?>" data-type="text">
        <div class="bloc-name">
            <label>Bloc texte</label>
        </div>
        <div>
            <div>
                <textarea name="text_<?= $num ?>" id="text_<?= $num ?>" data-type="text"><?= @$displayContent ?></textarea>
            </div>
            <div>
                <?= @$displayMarge ?>
                <?= @$displayColonnes ?>
                <?= @$displayOrdre ?>
                <button data-type="delete-button" data-num="<?= $num ?>" type="button" class="small">-</button> 
            </div>
        </div>
    </div>    
    <script>
        CKEDITOR.replace( 'text_<?= $num ?>', {
            filebrowserUploadUrl: '<?= SITE_URL ?>admin/pages/upload',
            filebrowserUploadMethod: 'form',
            clipboard_handleImages : false
        });
    </script>
<?php break ?>
<?php case 'gallery' : ?>
    <div class="build-bloc" data-num="<?= $num ?>" data-type="gallery">
        <div class="bloc-name">
            <label>Bloc gallerie</label>
        </div>
        <div>
            <div>
               <?= @$displayContent ?>
            </div>
            <div>
                <?= @$displayMarge ?>
                <?= @$displayOrdre ?>
                <button data-type="delete-button" data-num="<?= $num ?>" type="button" class="small">-</button>
            </div>
        </div>
    </div> 
<?php break ?>
<?php case 'code' : ?>
    <div class="build-bloc" data-num="<?= $num ?>" data-type="code">
        <div class="bloc-name">
            <label>Bloc code</label>
        </div>
        <div>
            <div>
                <textarea name="code_<?= $num ?>" id="code_<?= $num ?>" data-type="code"><?= @$displayContent ?></textarea>
            </div>
            <div>
                <?= @$displayOrdre ?>
                <button data-type="delete-button" data-num="<?= $num ?>" type="button" class="small">-</button> 
            </div>
        </div>
    </div>
<?php break ?>
<?php endswitch ?>

