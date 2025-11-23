<div class="layout-programmation">

    <section>

        <h1>Programmation </h1>

        <div class="layout-breadcrumb"><?= @$breadcrumb ?></div>

        <div class="layout-panel cols">

            <div class="layout-categories" data-type="category-slugs-list">

                <h2>Dates</h2>
                <ul>
                    <?php foreach(@$dates as $date) : ?>
                    <li data-type="category-slug" data-category-slug="<?= $date->slug ?>"<?= $date->selected ?>><a href="<?= $date->lien ?>"><?= $date->libelle ?></a></li>
                    <?php endforeach ?>
                    <li><a <?= $permanents->selected ?> href="<?= $permanents->lien ?>"><?= $permanents->libelle ?></a></li>
                </ul>

                <h2>Formes</h2>
                <ul>
                    <?php foreach(@$formes as $forme) : ?>
                    <li data-type="category-slug" data-category-slug="<?= $forme->slug ?>"<?= $forme->selected ?>><a href="<?= $forme->lien ?>"><?= $forme->libelle ?></a></li>
                    <?php endforeach ?>
                </ul>

                <h2>Thèmes</h2>
                <ul>
                    <?php foreach(@$themes as $theme) : ?>
                    <li data-type="category-slug" data-category-slug="<?= $theme->slug ?>"<?= $theme->selected ?>><a href="<?= $theme->lien ?>"><?= $theme->libelle ?></a></li>
                    <?php endforeach ?>
                </ul>

                <h2>Lieux</h2>
                <ul>
                    <?php foreach(@$lieux as $lieu) : ?>
                    <li data-type="category-slug" data-category-slug="<?= $lieu->slug ?>"<?= $lieu->selected ?>><a href="<?= $lieu->lien ?>"><?= $lieu->libelle ?></a></li>
                    <?php endforeach ?>
                </ul>                
                
            </div>

            <div class="layout-programmes-list" data-type="slugs-list"><?= @$programmes ?></div>
            
            <div class="layout-programme-single" data-type="single-content"><?= @$programme ?></div>

        </div>

    </section>

</div>