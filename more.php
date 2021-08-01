<?php
$_TITLE = 'Home';
include_once 'includes/templates/header.php';
require_once 'includes/classes/Jar.php';
require_once 'includes/classes/Utils.php';
$jars = Jar::collect();
if (isset($_GET['type'])) {
    $type = $_GET['type'];
    $tree = Jar::getTree($type, $jars, false);
    if ($tree !== false) {
        $typeJars = $jars[$tree][$type];
        ?>
        <div class="jar-list full">
            <h1 class="jar-list-title"></h1>
            <div class="jars">
                <?php foreach ($typeJars as $jar) { ?>
                    <div class="jar">
                        <h3 class="jar-version"><?php echo $jar['version'] ?></h3>
                        <h4 class="jar-release"><?php echo date('jS F Y', $jar['built']) ?></h4>
                        <a class="jar-download" href="/jars/<?php echo $tree . '/' . $type . '/' . $jar['file']; ?>">Download</a>
                        <span class="jar-md5"><?php echo $jar['md5'] ?></span>
                        <button class="jar-show">Show MD5</button>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
    }
} ?>
    <script>
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
