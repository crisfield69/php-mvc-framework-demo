<section>
    
    <h1 data-id="<?= $exposant->id ?>">
        <?= $capitalize($exposant->stand_name) ?>
        <?= @$wishlistWidget->main ?>
    </h1>

    <h2 class="sous-titre"><?= $exposant->paper_introduction ?></h2>

    <div>
        <p><?= $writeclean('1ère année de participation : ', $exposant->first_participation_year) ?></p>
        <p><?= $writeclean('<strong>Stand : </strong>', $exposant->stand_number) ?></p>
    </div>

    <div class="pictogrammes">
        <?php foreach($exposant->labels as $label) : ?>
            <p>
                <a href="<?= $label->url ?>" target="_blank">
                    <span><img src="<?= $label->image.RND ?>"></span>
                    <span><?= $label->title ?></span>
                </a>
            </p>
        <?php endforeach ?>
    </div>
    
    <div class="description"><?= $exposant->website_introduction ?></div>
    
    <?= $photos ?>    

    <div class="contact">
        <h3>Contact</h3>
        <div>
            
            <h4><?= $capitalize($exposant->stand_name) ?></h4>
            <!--<p><?= $capitalize($exposant->contact) ?></p>-->
            
            <!--<p><?= $capitalize($exposant->communication_postal_street) ?></p>-->
            <p><?= $exposant->communication_postal_zipcode . ' ' . $capitalize($exposant->communication_postal_city) ?></p>
        </div>
        <div>
            <p><?= $writeclean('Tel : ', $exposant->communication_phone) ?></p>
            <p><?= $exposant->communication_email ?></p>
            <p><?= $exposant->website ?></p>
        </div>
        <div class="socials">
            <?= $exposant->facebook ?>
            <?= $exposant->instagram ?>
        </div>
    </div>


</section>

