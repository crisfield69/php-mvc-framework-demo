<div class="layout-programmation">

    <h1>Programmation</h1>

    <form method="post">

        <fieldset>

            <legend>Associer un libellé (à un thème, une forme ou un lieux)</legend>

            <input type="hidden" name="id" value="<?= @$programmation->id ?>">
            <input type="hidden" name="ordre" value="<?= @$programmation->ordre ?>">

            <p>
                <label>Libelle</label>
                <input type="text" name="libelle" value="<?= @$programmation->libelle ?>" required>
            </p>

            <p>
                <label>Thèmes</label>
                <select name="theme">
                    <option value="">...</option>
                    <?php foreach (@$themes as $theme) : ?>
                        <?php $selected = ($theme === $programmation->motsclefs) ? ' selected' : '' ?>
                        <option value="<?= @$theme ?>" <?= $selected ?>><?= @$theme ?></option>
                    <?php endforeach ?>
                </select>
            </p>

            <p>
                <label>Formes</label>
                <select name="forme">
                    <option value="">...</option>
                    <?php foreach (@$formes as $forme) : ?>
                        <?php $selected = ($forme === $programmation->motsclefs) ? ' selected' : '' ?>
                        <option value="<?= @$forme ?>" <?= $selected ?>><?= @$forme ?></option>
                    <?php endforeach ?>
                </select>
            </p>

            <p>
                <label>Lieux</label>
                <select name="lieu">
                    <option value="">...</option>
                    <?php foreach (@$lieux as $lieu) : ?>
                        <?php $selected = ($lieu === $programmation->motsclefs) ? ' selected' : '' ?>
                        <option value="<?= @$lieu ?>" <?= $selected ?>><?= @$lieu ?></option>
                    <?php endforeach ?>
                </select>
            </p>

        </fieldset>


        <fieldset>

            <p class="right">
                <a class="button light" href="<?= SITE_URL ?>admin/transcriptions">Retour</a>
                <button type="submit">Enregistrer</button>
            </p>

        </fieldset>

    </form>

</div>

<script>
    let selects;
    window.addEventListener('load', function() {
        selects = Array.from(document.querySelectorAll('select'));
        if (selects.length === 0) return;
        selects.map(function(select) {
            select.addEventListener('change', selectChangeHandler, false);
        });
    }, false);

    function selectChangeHandler(event)
    {
        let currentSelect = event.target;
        selects.map(function(select) {
            if(select !== currentSelect) {
                select.options[0].selected = true;
            }
        });
    }

</script>