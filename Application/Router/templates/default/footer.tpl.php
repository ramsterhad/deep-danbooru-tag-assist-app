</main>

<footer class="footer mt-auto bg-light">
    <div class="container-fluid container-footer">
        <div class="row  align-items-end">
            <div class="col-sm-4">
                <div class="row footer-help-row">
                    <div class="col-sm-12"><h4>Manual</h4></div>
                </div>
                <div class="row footer-help-row">
                    <div class="col-sm-3">Numpad Keys 1 - 9</div>
                    <div class="col-sm-9">Activate the checkboxes of the tags.</div>
                </div>
                <div class="row footer-help-row">
                    <div class="col-sm-3">Enter</div>
                    <div class="col-sm-9">Send the marked tags to Danbooru and load a new post.</div>
                </div>
                <div class="row footer-help-row">
                    <div class="col-sm-3">Spacebar</div>
                    <div class="col-sm-9">Load a new post. Do not send anything to Danbooru.</div>
                </div>
            </div>

            <div class="col-sm-4 text-center ">
                <span> &copy; <span id="year"></span> reiuyi + ramsterhad</span>
            </div>

            <div class="col-sm-4">
                <form method="post" action="index.php">
                    <input type="hidden" name="r" value="logout" />
                    <button type="submit" class="button-cosplays-as-link float-right" value="Logout">> Logout</button>
                </form>
            </div>
        </div>
    </div>
</footer>

<script type="text/javascript">
    // Copyright year
    document.getElementById('year').innerHTML = new Date().getFullYear();
</script>

</body>
</html>