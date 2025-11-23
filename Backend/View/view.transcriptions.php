
<div class="layout-programmations">

    <h1>Programmation</h1>    

    <form method="post">

        <h2>Thèmes</h2>

        <table class="list">
            
            <thead>
                <tr>
                    <th>Mot-clef(s)</th>                    
                    <th>Libellé</th>
                    <th>Slug</th>
                    <th>Type</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach (@$themes as $theme) : ?>

                    <input type="hidden" value="<?= @$theme->id ?>" name="ids[]">
                    <input type="hidden" value="<?= @$theme->motsclefs ?>" name="motsclefs[]">
                    <input type="hidden" value="<?= @$theme->type ?>" name="types[]">
                    <input type="hidden" value="<?= @$theme->slug->value ?>" name="slugs[]">
                    <tr class="<?= @$theme->complete ?>">
                        <td><?= @$theme->motsclefs ?></td>
                        <td><input type="text" value="<?= @$theme->libelle ?>" name="libelles[]"></td>
                        <td><?= @$theme->slug->libelle ?></td>
                        <td><?= @$theme->type ?></td>
                    </tr>

                <?php endforeach ?>

            </tbody>

        </table>


        <h2>Formes</h2>

        <table class="list">
            
            <thead>
                <tr>
                    <th>Mot-clef(s)</th>                    
                    <th>Libellé</th>
                    <th>Slug</th>
                    <th>Type</th>
                </tr>
            </thead>

            <tbody>   

                <?php foreach (@$formes as $forme) : ?>

                    <input type="hidden" value="<?= @$forme->id ?>" name="ids[]">
                    <input type="hidden" value="<?= @$forme->motsclefs ?>" name="motsclefs[]">
                    <input type="hidden" value="<?= @$forme->type ?>" name="types[]">
                    <input type="hidden" value="<?= @$forme->slug->value ?>" name="slugs[]">
                    <tr class="<?= @$forme->complete ?>">
                        <td><?= @$forme->motsclefs ?></td>
                        <td><input type="text" value="<?= @$forme->libelle ?>" name="libelles[]"></td>
                        <td><?= @$forme->slug->libelle ?></td>
                        <td><?= @$forme->type ?></td>
                    </tr>

                <?php endforeach ?>

            </tbody>

        </table>

        <h2>Lieux parents</h2>

        <table class="list">
            
            <thead>
                <tr>
                    <th>Mot-clef(s)</th>
                    <th>Libellé</th>
                    <th>Slug</th>
                    <th>Type</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach (@$lieuxParents as $lieuParent) : ?>

                    <input type="hidden" value="<?= @$lieuParent->id ?>" name="ids[]">
                    <input type="hidden" value="<?= @$lieuParent->motsclefs ?>" name="motsclefs[]">
                    <input type="hidden" value="<?= @$lieuParent->type ?>" name="types[]">
                    <input type="hidden" value="<?= @$lieuParent->slug->value ?>" name="slugs[]">
                    <tr class="<?= @$lieuParent->complete ?>">
                        <td><?= @$lieuParent->motsclefs ?></td>                        
                        <td><input type="text" value="<?= @$lieuParent->libelle ?>" name="libelles[]"></td>
                        <td><?= @$lieuParent->slug->libelle ?></td>
                        <td><?= @$lieuParent->type ?></td>
                    </tr>

                <?php endforeach ?>

            </tbody>

        </table>

        <h2>Lieux</h2>

        <table class="list">
            
            <thead>
                <tr>
                    <th>Mot-clef(s)</th>
                    <th>Libellé</th>
                    <th>Slug</th>
                    <th>Type</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach (@$lieux as $lieu) : ?>

                    <input type="hidden" value="<?= @$lieu->id ?>" name="ids[]">
                    <input type="hidden" value="<?= @$lieu->motsclefs ?>" name="motsclefs[]">
                    <input type="hidden" value="<?= @$lieu->type ?>" name="types[]">
                    <input type="hidden" value="<?= @$lieu->slug->value ?>" name="slugs[]">
                    <tr class="<?= @$lieu->complete ?>">
                        <td><?= @$lieu->motsclefs ?></td>                        
                        <td><input type="text" value="<?= @$lieu->libelle ?>" name="libelles[]"></td>
                        <td><?= @$lieu->slug->libelle ?></td>
                        <td><?= @$lieu->type ?></td>
                    </tr>

                <?php endforeach ?>

            </tbody>

        </table>

        <p class="right">
            <button type="submit" class="button">Enregistrer</button>
        </p>

    </form> 
   
</div>

<style>
    .list input[type="text"] {
        width: 100%;
    }

    .list th:nth-child(1) {
        width: 30%;
    }

    .list th:nth-child(2) {
        width: 30%;
    }

    .list th:nth-child(3) {
        width: 30%;
    }

    .list th:nth-child(4) {
        width: 10%;
    }
</style>