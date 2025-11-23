
<div class="layout-galleries">
    
    <h1>Galeries</h1>

    <table class="list">
        
        <thead>
            <tr>
                <th>Nom</th>
                <th style="width: 180px">Actions</th>
            </tr>
        </thead>

        <tbody>
            
            <?php foreach($galleries as $gallery) : ?>

            <tr>                
                <td><?= $gallery->nom ?></td>
                <td>
                    <a class="button small" href="<?= SITE_URL ?>admin/galeries/delete/<?= $gallery->id ?>">Supprimer</a>
                    <a class="button small" href="<?= SITE_URL ?>admin/galeries/update/<?= $gallery->id ?>">Modifier</a>
                </td>
            </tr>

            <?php endforeach ?>

        </tbody>

    </table>

    <p class="right">
        <a class="button" href="<?= SITE_URL . 'admin/galeries/insert' ?>">Ajouter</a>
    </p>
    
</div>