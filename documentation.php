<?php
$_TITLE = 'Documentation';
include_once 'includes/templates/header.php';
require_once 'includes/classes/Documentation.php';
$routes = Documentation::ROUTES;
$keys = array_keys($routes);
?>
    <div class="github-links">
        <div class="github-link">
            <h3 class="github-title">Java Wrapper</h3>
            <a class="github-button" href="https://github.com/jacobtread/ServerJars-API-Java" target="_blank"><i class="fab fa-github"></i>Source & Download</a>
        </div>
        <div class="github-link">
            <h3 class="github-title">Javascript Wrapper</h3>
            <a class="github-button" href="https://github.com/jacobtread/ServerJars-API-JS" target="_blank"><i class="fab fa-github"></i>Source & Download</a>
        </div>
    </div>
    <div class="docs-selectors">
        <?php
        foreach ($keys as $key) {
            ?>
            <a class="docs-selector" href="#<?php echo $key ?>"><?php echo $key; ?></a>
            <?php
        }
        ?>
    </div>
<?php
foreach ($keys as $route) {
    $routeData = $routes[$route];
    $url = $routeData['url'];
    $url = str_replace('{', '<span class="url-params">{', $url);
    $url = str_replace('}', '}</span>', $url);
    $fields = $routeData['fields'];
    $response = $routeData['response'];
    ?>
    <div class="docs-section" id="<?php echo $route ?>">
        <h1 class="docs-title"><?php echo $routeData['name'] ?></h1>
        <div class="docs-method method-<?php echo strtolower($routeData['method']) ?>"><?php echo $routeData['method'] ?></div>
        <h2 class="docs-url"><?php echo $url ?></h2>
        <p class="docs-description"><?php echo $routeData['description'] ?></p>
        <?php if (sizeof($fields) > 0) { ?>
            <table class="docs-fields">
                <thead>
                <tr>
                    <th>Parameter</th>
                    <th>Type</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($fields as $field => $fieldData) { ?>
                    <tr>
                        <td><?php echo $field . ($fieldData['optional'] ? '<span class="docs-optional">Optional</span>' : ''); ?></td>
                        <td><?php echo $fieldData['type'] ?></td>
                        <td><?php echo $fieldData['description'] ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php }
        if ($response['type'] === 'json') { ?>
            <pre class="docs-example"><code><?php echo json_encode($response['example'], JSON_PRETTY_PRINT) ?></code></pre>
        <?php } ?>
    </div>
    <?php
}
include_once 'includes/templates/footer.php';
