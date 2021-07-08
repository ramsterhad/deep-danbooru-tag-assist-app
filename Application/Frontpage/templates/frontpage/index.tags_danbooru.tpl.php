<div class="row top-buffer">
    <div class="col-lg-2 column-header">Danbooru tags</div>
</div>
<div class="row">
    <div class="col-lg">
        <?php foreach ($response->get('danbooru')->getPost()->getTagCollection()->getTags() as $tag) : ?>
            <span class="tag">
                <a href="https://danbooru.donmai.us/wiki_pages/<?php echo $tag->getName(); ?>" target="_blank" rel="noreferrer">
                    <?php echo $response->getController()->tagsCssClassHelperColoredDanbooruTags($tag); ?>
                </a>
            </span>
        <?php endforeach; ?>
    </div>
</div>