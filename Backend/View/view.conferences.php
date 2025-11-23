
<div class="layout-conferences">

    <h1>Conférences</h1>

    <form method="post" enctype="multipart/form-data">

        <table class="list layout-list">
                
            <thead>
                <tr>
                    <th style="width: 250px">Fichier</th>
                    <th style="width: 400px;">Libellés</th>
                    <th style="width: 150px">Photo</th>
                    <th style="width: 80px">Ordre</th>
                    <th style="width: 80px">Masquer</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach(@$conferences as $conference) : ?>
                    <input type="hidden" name="ids[]" value="<?= $conference->id ?>">
                    <input type="hidden" name="fichiers[]" value="<?= $conference->fichier ?>">
                <tr>
                    <td><p><?= $conference->fichier ?></p></td>
                    <td>
                        <p><textarea name="titres[]" placeholder="Titre"><?= $conference->titre ?></textarea></p>
                        <p><textarea name="soustitres[]" placeholder="Sous-titre"><?= $conference->soustitre ?></textarea></p>
                    </td>
                    <td>
                        <?php if($conference->photo) : ?>
                            <img src="<?= $conference->photo ?>">
                        <?php endif ?>

                        <button type="button" class="button" data-inputfile-id="<?= $conference->inputfileId ?>"></button>
                        <input type="file" name="photos[]" value="" data-inputfile-id="<?= $conference->inputfileId ?>">
                    </td>
                    <td>
                        <?= $conference->widgetOrdre ?>
                    </td>
                    <td><input type="checkbox" name="horslignes[]" value="<?= $conference->fichier ?>"<?= $conference->horsligne ?>></td>
                </tr>
                <?php endforeach ?>
            </tbody>

        </table>

        <p class="right">
            <button type="submit" name="action" class="button">Enregistrer</button>            
        </p>

    </form>

</div>

<script>
    window.addEventListener('load', function(){
        let inputFileButtons = Array.from(document.querySelectorAll('button[data-inputfile-id]'));
        let inputFileInputs = Array.from(document.querySelectorAll('input[data-inputfile-id]'));        
        inputFileButtons.map(function(button, index){
            button.addEventListener('click', function(){
                inputFileInputs[index].click();
            }, false);
        });
        inputFileInputs.map(function(input, index){
            input.addEventListener('change', function(){
                inputFileButtons[index].classList.add('charged');
            }, false);
        });
    }, false);
</script>