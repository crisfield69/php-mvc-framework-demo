<ul>
    <?php foreach( $navigation as $libelle => $link ) : ?>
    <li>
        <a href="<?= $link[0] ?>"<?= $link[1] ?>><?= $libelle ?></a>
    </li>
    <?php endforeach ?>
</ul>