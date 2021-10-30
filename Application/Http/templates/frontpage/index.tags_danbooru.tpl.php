<div class="row top-buffer">
    <div class="col-lg-2 column-header">Danbooru tags</div>
</div>
<div class="row">
    <div class="col-lg">
        <?php foreach ($response->get('post')->getTagCollection()->getTags() as $tag) : ?>
            <span class="tag">
                <a href="https://danbooru.donmai.us/wiki_pages/<?php echo $tag->getName(); ?>" target="_blank" rel="noreferrer">
                    <?php if ($tag->highlightColoredTag()): ?> <mark> <?php endif; ?>
                    <?php echo $response->getController()->tagsCssClassHelperColoredDanbooruTags($tag); ?>
                    <?php if ($tag->highlightColoredTag()): ?> </mark> <?php endif; ?>
                </a>&nbsp;
            </span>
        <?php endforeach; ?>
    </div>
</div>