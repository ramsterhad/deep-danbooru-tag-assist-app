<div class="row">&nbsp;</div>
<div class="row">
    <div class="col s12">
        <h3 class="center-align">Deep Danbooru Tag Assist 3000</h3>
    </div>
</div>
<!--
<div class="row">

    <div class="col s2"></div>
    <div class="col s10">
-->
        <div class="row">
            <form action="index.php" method="post">
                <input type="text" name="username" placeholder="username">
                <input type="text" name="api_key" placeholder="api key">
                <span class="login_box">
                    <span class="login_box_login_button"><button type="submit" name="submit" class="btn waves-effect waves-light" >login</button></span>
                    <span class="login_box_apiurl_helper_text login-apikey-text text-vertikal-flex">No API key yet?&nbsp;<a href="https://danbooru.donmai.us/wiki_pages/help:api" target="_blank">Get one!</a></span>
                    <input type="hidden" name="r" value="authenticate">
                </span>
            </form>
        </div>
        <div class="row">
            <div class="col s12">

            </div>
        </div>
        <?php if ($response->has('authentication_wrong_credentials')) : ?>
        <div class="row">
            <p>Danbooru said no to your credentials. (╯︵╰,)<br>Whats your name and api key again?<br>must. know. that.</p>
        </div>
        <?php endif; ?>
<!--
    </div>
</div>
-->
