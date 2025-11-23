<nav>
    <ul>
        <?php foreach( $navigation as $libelle => $link ) : ?>
        <li>
            <a href="<?= SITE_URL . $link ?>"><?= $libelle ?></a>
        </li>
        <?php endforeach ?>
    </ul>
</nav>