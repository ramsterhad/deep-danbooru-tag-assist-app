<div class="row">
    <div class="col-lg-2">Suggested tags</div>
    <div class="col-lg-10"><?php echo $response->getController()->tagsCssClassHelperUnknownTags($response->get('suggestedTags'), $response->get('unknownTags')); ?></div>
</div>
<div class="row">
    <div class="col-lg-2">new tags</div>
    <div class="col-lg-10">
        <form action="index.php" method="post" id="form_submit_tags">
            <?php

            $maxTags = $response->getController()->getCountedUnknownTagsLimitedByValue(
                $response->get('unknownTags'),
                $response->get('suggestedTagsLimit')
            );
            $keyIdent = 96; // numpad 0
            /*
             * $closeRow decides if a row gets a new column or if the row has to be closed and a new one started.
             * It starts with 0 (new row) and directly gets increased to 2.
             * For each column it gets decreased by 1. After it reached 0, the row will be closed and a new one
             * will be opened and the circle starts again.
             */
            $closeRow = 0;

            for ($i = 0; $i < $maxTags; $i++) :
                ?>

                <?php if ($i % 3 === 0) : ?>
                <?php $closeRow = 2; // Opens a new row. If not, a new column will be appended to the previous column. ?>
                <div class="row new_tags_grid">
                <?php endif; ?>
                    <div class="col-lg-4">
                        <span
                            class="tag mlpTag"
                            data-key="<?php echo $keyIdent + $i + 1;?>"
                            id="ml_new_tag_<?php echo $response->get('unknownTags')->getTags()[$i]->getName();?>"
                        >
                            <label>
                                <input
                                    type="checkbox"
                                    class="tag_checkbox"
                                    name="tag_checkbox[]"
                                    value="<?php echo $response->get('unknownTags')->getTags()[$i]->getName() ;?>"
                                >
                                <span class="new_tags_grind_col" title="<?php echo $response->get('unknownTags')->getTags()[$i]->getScore(); ?>">
                                   <a href="https://danbooru.donmai.us/wiki_pages/<?php echo $response->get('unknownTags')->getTags()[$i]->getName();?>" target="_blank" rel="noreferrer"><?php echo '[ '.($i + 1).' ] ' . $response->get('unknownTags')->getTags()[$i]->getName(); ?></a>
                                </span>
                            </label>
                        </span>
                    </div>
                <?php if ($closeRow === 0) : // Ends the row. ?>
                </div>
                <?php endif; ?>

                <?php --$closeRow; // Decreases the counter for each column by 1. ?>
            <?php endfor; ?>
            <?php if ($closeRow > -1) : // In case the last row hadn't three columns. ?>
            </div>
            <?php endif; ?>

            <?php foreach ($response->get('danbooru')->getPost()->getTagCollection()->getTags() as $tag) : ?>
                <input type="hidden" name="tag_checkbox_existing_tags[]" value="<?php echo $tag->getName(); ?>">
            <?php endforeach; ?>

            <input type="hidden" name="tag_checkbox_post_id" value="<?php echo $response->get('danbooru')->getPost()->getId() ;?>">
            <input type="hidden" name="r" value="pushnewtags">
            <button type="submit" id="id_tag_checkbox_submit" name="name_tag_checkbox_submit" class="tag_checkbox_submit"></button>
        </form>
    </div>
</div>