

<div class="container text-center header logo-header">
    <div class="slogan">Deep Danbooru Tag Assist </div><img src="img/header_logo.png" class="logo">
</div>

<div class="container authentication_box">
    <form action="index.php" method="post">
        <div class="row mb-3">
            <label for="username" class="col-sm-2 col-form-label">Username</label>
            <div class="col-sm-10">
                <input type="text" name="username" class="form-control" id="username">
            </div>
        </div>
        <div class="row mb-3">
            <label for="api_key" class="col-sm-2 col-form-label">API key</label>
            <div class="col-sm-10">
                <input type="text" name="api_key" class="form-control" id="api_key">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-7 offset-sm-2">
                <span>No API key yet?&nbsp;<a href="https://danbooru.donmai.us/wiki_pages/help:api" target="_blank">Get one!</a></span>
            </div>
            <div class="col-sm-3">
                <button type="submit" name="submit" class="btn btn-primary float-right">Login</button>
            </div>
        </div>


        <div class="row mb-3">
            <div class="col-sm-10 offset-sm-2">
                <?php if ($response->has('authentication_wrong_credentials')) : ?>
                    <span>
                        <p>Danbooru said no to your credentials. (╯︵╰,)<br>Whats your name and api key again?<br>must. know. that.</p>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <input type="hidden" name="r" value="authenticate">
    </form>
</div>