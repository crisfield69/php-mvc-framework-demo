<nav>
    <ul>
        <?php foreach( $navigation as $libelle => $link ) : ?>
        <li data-libelle="<?= $libelle ?>" data-link="<?= $link ?>">
            <a href="<?= SITE_URL . $link ?>" data-libelle="<?= $libelle ?>" data-link="<?= $link ?>"><?= $libelle ?></a>
        </li>
        <?php endforeach ?>
    </ul>
</nav>