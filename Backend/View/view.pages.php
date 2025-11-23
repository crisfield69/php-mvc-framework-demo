
<div class="layout-pages">

    <h1>Pages</h1>

    <table class="list">
        
        <thead>
            <tr>
                <th>Titre</th>
                <th>Slug</th>
                <th style="width: 180px">Actions</th>
            </tr>
        </thead>

        <tbody>
            
            <?php foreach($pages as $page) : ?>

            <tr>                
                <td><?= $page->titre ?></td>
                <td><?= $page->slug ?></td>
                <td>
                    <a class="button small" href="<?= SITE_URL ?>admin/pages/delete/<?= $page->id ?>">Supprimer</a>
                    <a class="button small" href="<?= SITE_URL ?>admin/pages/update/<?= $page->id ?>">Modifier</a>
                </td>
            </tr>

            <?php endforeach ?>

        </tbody>

    </table>

    <p class="right">        
        <a class="button" href="<?= SITE_URL ?>admin/pages/insert">Ajouter</a>
    </p>

</div>