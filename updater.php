<?php
$_TITLE = 'Updater';
include_once 'includes/templates/header.php';
?>
    <div class="welcome-wrapper">
        <img src="/assets/img/angel-1-min.png" class="welcome-icon" alt="Brianna">
        <div class="welcome-box">
            <h1 class="welcome-title">Hello User!</h1>
            <p class="welcome-message">
                You are looking at our brand new Automatic Updater which you can run like any normal jar on our website.
                However when there is a new version available either on the version you predefine or latest it will
                automaticly update your server to that version on each restart. The jar is ran in place of your usual
                jar, just make sure to configure the wanted version and jar type correctly though the
                serverjars.properties which is automatically created when you first run the jar.
            </p>
            <div class="welcome-buttons">
                <a class="welcome-button" href="https://github.com/jacobtread/ServerJars-Updater/releases/download/2.1/Updater-2.1.jar"><i class="fa fa-download"></i>
                    <span>Download</a>
                <a class="welcome-button" href="https://github.com/jacobtread/ServerJars-Updater" target="_blank"><i class="fab fa-github"></i>
                    <span>Source Code</a>
            </div>
        </div>
        <img src="/assets/img/updater.gif" alt="Gif of Updater" class="updater-gif">
    </div>
<?php
include_once 'includes/templates/footer.php';
