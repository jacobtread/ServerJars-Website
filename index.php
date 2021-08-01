<?php
$_TITLE = 'Home';
include_once 'includes/templates/header.php';
require_once 'includes/classes/Stats.php';
require_once 'includes/classes/Jar.php';
require_once 'includes/classes/Utils.php';
Stats::load();
$jars = Jar::collect();
?>
    <div class="welcome-wrapper">
        <img src="/assets/img/angel22-min.png" class="welcome-icon" alt="Brianna">
        <div class="welcome-box">
            <h1 class="welcome-title">Hello User!</h1>
            <p class="welcome-message">
                ServerJars is the easiest and most efficient way to get the most up to date Minecraft jars. Using our
                state-of-the-art system we fetch updates every 8 minutes and instantaneously supply those updates for
                you to
                download directly from our site. Using our Auto Updater, you can even automatically receive the latest
                updates for your server effortlessly in seconds after every server restart.
                <br><br>
                If that’s not your style then join our discord and we’ll notify you when any major or exploit patching
                build
                is released.
            </p>
            <div class="stats">
                <div class="welcome-button"><i class="fa fa-download"></i>
                    <span>Downloads</span> <?php echo number_format((int)Stats::downloads()); ?> </div>
                <div class="welcome-button"><i class="fa fa-calendar"></i>
                    <span>Last Updated</span> <?php echo Stats::updated(); ?></div>
            </div>
        </div>
    </div>
    <div class="switches">
        <?php
        $categories = array_keys($jars);
        foreach ($categories as $category) {
            $category = strtolower($category);
            ?>
            <button class="switch" name="<?php echo $category ?>"><?php echo $category ?></button>
        <?php } ?>
    </div>
    <div class="jars-content">
        <?php
        foreach ($categories as $category) {
            $childCategories = array_keys($jars[$category]);
            ?>
            <div class="jar-lists" category="<?php echo $category ?>">
                <?php foreach ($childCategories as $childCategory) { ?>
                    <div class="jar-list">
                        <h1 class="jar-list-title"><?php echo Utils::capitalizeFirst($childCategory) ?></h1>
                        <div class="jars">
                            <?php
                            $isFirst = true;
                            $isMore = false;
                            $count = 0;
                            foreach ($jars[$category][$childCategory] as $jar) {
                                if ($count == 8) {
                                    $isMore = true;
                                    break;
                                }
                                ?>
                                <div class="jar">
                                    <?php if ($isFirst) { ?>
                                        <h2 class="jar-latest">Latest</h2>
                                    <?php } ?>
                                    <h3 class="jar-version"><?php echo $jar['version'] ?></h3>
                                    <h4 class="jar-release"><?php echo date('jS F Y', $jar['built']) ?></h4>
                                    <a class="jar-download" href="/api/fetchJar/<?php echo $childCategory . '/' . $jar['version']; ?>">Download</a>
                                    <span class="jar-md5"><?php echo $jar['md5'] ?></span>
                                    <button class="jar-show">Show MD5</button>
                                </div>
                                <?php
                                $isFirst = false;
                                $count++;
                            }
                            ?>
                        </div>
                        <?php if ($isMore) { ?>
                            <a class="jar-more" href="/more/<?php echo $childCategory ?>">More...</a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
    <script>
        var $switches = $('.switch');
        $switches.on('click', function () {
            $switches.removeClass('selected');
            var $switch = $(this);
            $switch.addClass('selected');
            var name = $switch.text();
            $('.jar-lists').removeClass('selected');
            $('.jar-lists[category="' + name + '"]').addClass('selected');
        });
        $switches.each(function () {
            var $switch = $(this);
            var name = $switch.text();
            if (name === "servers") {
                $switch.addClass('selected');
            }
        });
        $('.jar-lists[category="servers"]').addClass('selected');
        $('.jar-show').on('click', function () {
            var $show = $(this);
            var isShow = $show.text() === 'Show MD5';
            var $md5 = $(this).parent().children('.jar-md5');
            if (isShow) {
                $md5.addClass('visible');
                $show.text("Hide MD5");
            } else {
                $md5.removeClass('visible');
                $show.text("Show MD5");
            }
        })
    </script>
<?php
include_once 'includes/templates/footer.php';
