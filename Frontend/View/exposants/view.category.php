
<form action="<?= $post_url ?>" method="post"<?= $class ?>>
    
    <input type="hidden" name="url" value="<?= $post_url ?>/">
    <input type="hidden" name="tags" value="<?= $item->tags ?>">
    <input type="hidden" name="action" value="filter">
    
    <button type="submit"><?= $item->libelle ?></button>

</form>