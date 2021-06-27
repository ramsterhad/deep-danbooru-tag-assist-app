<div class="row">
    <div class="col s2">Danbooru tags</div>
    <div class="col s10">
        <?php foreach ($response->get('danbooru')->getPost()->getTagCollection()->getTags() as $tag) : ?>
            <span class="tag">
                <a href="https://danbooru.donmai.us/wiki_pages/<?php echo $tag->getName(); ?>" target="_blank">
                    <?php echo $response->getController()->tagsCssClassHelperColoredDanbooruTags($tag); ?>
                </a>
            </span>
        <?php endforeach; ?>
    </div>
</div>