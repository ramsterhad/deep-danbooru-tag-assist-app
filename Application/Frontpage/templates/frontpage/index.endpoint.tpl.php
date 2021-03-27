<div class="row">
    <div class="col s2 hidden">danbooru</div>
    <div class="col s10">
        <div class="row">
            <div class="col s10">
                <form action="index.php" method="post" class="" id="form_set_danbooru_api_url">
                    <input id="input_id_danbooru_api_url" name="input_name_danbooru_api_url" type="text" value="<?php echo $response->get('danbooru')::loadEndpointAddress();?>">
                    <label for="input_id_danbooru_api_url">danbooru api url</label>
                    <input type="hidden" name="set_danbooru_api_url" value="">
                    <input type="hidden" name="r" value="apiurlset">
                </form>
            </div>
            <div class="col s2">
                <button onclick="document.getElementById('form_set_danbooru_api_url').submit();" class="btn waves-effect waves-light url_form" type="submit" name="api_submit_save" title="save your custom api url"><i class="material-icons">save</i></button>
                <form action="index.php" method="post" class="url_form" name="form2">
                    <input type="hidden" name="r" value="apiurlreset">
                    <button class="btn waves-effect waves-light" type="submit" name="api_submit_reset" title="reset to default api url"><i class="material-icons">undo</i></button>
                </form>
            </div>
        </div>
    </div>
</div>