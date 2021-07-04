
<form action="index.php" method="post" class="url_form" name="form2" id="form_reset_danbooru_api_url">
    <input type="hidden" name="r" value="apiurlreset">
</form>

<div class="row top-buffer-headline">
    <div class="col-lg-10">
        <form action="index.php" method="post" class="" id="form_set_danbooru_api_url">
            <div class="input-group">
                <span class="input-group-text">API URL</span>
                <input
                    type="text"
                    class="form-control"
                    id="input_id_danbooru_api_url"
                    name="input_name_danbooru_api_url"
                    placeholder="Danboorus API URL"
                    value="<?php echo $response->get('danbooru')::loadEndpointAddress();?>"
                >
                <button class="btn btn-outline-secondary" type="button" name="api_submit_save" title="save your custom api url" onclick="document.getElementById('form_set_danbooru_api_url').submit();">save</button>
                <button class="btn btn-outline-secondary" type="button" name="api_submit_reset" title="reset to default api url" onclick="document.getElementById('form_reset_danbooru_api_url').submit();">reset</button>
            </div>
            <input type="hidden" name="set_danbooru_api_url" value="">
            <input type="hidden" name="r" value="apiurlset">
        </form>
    </div>
</div>