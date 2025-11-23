
<div class="photos">

    <?php foreach($photos as $photo) : ?>
        <a href="<?= $photo->large.RND ?>" target="_blank"><img src="<?= $photo->small.RND ?>"></a>
    <?php endforeach ?>
    
</div>
