<?php declare(strict_types=1);

// Settings
const LANGUAGE_COOKIE_PARAM = 'lang';
const LANGUAGE_QUERY_PARAM = 'lang';
const LANGUAGE_FALLBACK = 'en';
const PAGE_IDENTIFIER_QUERY_PARAM = 'page';
const PAGE_IDENTIFIER_DIRECTORY = 'views';
const PAGE_IDENTIFIER_DEFAULT_PAGE = 'index';
const PAGE_IDENTIFIER_FILE_ENDING = '.html'; // !IMPORTANT do not Use PHP!

// Language Switch
if (isset($_GET[LANGUAGE_QUERY_PARAM])) {
    setcookie(LANGUAGE_COOKIE_PARAM, $_GET[LANGUAGE_QUERY_PARAM]);
    $_COOKIE[LANGUAGE_COOKIE_PARAM] = $_GET[LANGUAGE_QUERY_PARAM];
}

// This sets the language of the user.
// If you wish to set a default you can replace ln 21:22 and 23 with your default
if (!isset($_COOKIE[LANGUAGE_COOKIE_PARAM])) {
    $language = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
    $language = strtolower(substr(chop($language[0]),0,2));
    setcookie(LANGUAGE_COOKIE_PARAM, $language);
} else {
    $language = $_COOKIE[LANGUAGE_COOKIE_PARAM];
}
if (!is_dir(PAGE_IDENTIFIER_DIRECTORY . DIRECTORY_SEPARATOR . $language)) {
    $language = LANGUAGE_FALLBACK;
}

// View Switch
$uri = $_GET[PAGE_IDENTIFIER_QUERY_PARAM] ?? PAGE_IDENTIFIER_DEFAULT_PAGE;
$uri = rtrim($uri, '/');
$uri = filter_var($uri, FILTER_SANITIZE_URL);
$_uri = explode('/', $uri);

$showView = PAGE_IDENTIFIER_DIRECTORY . '/' . $language;
if (count($_uri) > 1) {
    // echo "1<br>";
    foreach ($_uri as $key => $value) {
        // echo "2<br>";
        $showView .= '/';
        $showView .= $value;
    }
} else {
    $showView .= '/'.$_uri[0];
}
$showView = str_replace('/', DIRECTORY_SEPARATOR, $showView);
if (is_dir($showView)) {
    $showView .= DIRECTORY_SEPARATOR . PAGE_IDENTIFIER_DEFAULT_PAGE;
}
$showView .= PAGE_IDENTIFIER_FILE_ENDING;

if (file_exists($showView)) {
    // echo "4<br>";
    $content = file_get_contents($showView);
} else {
    $content = file_get_contents(PAGE_IDENTIFIER_DIRECTORY."/404".PAGE_IDENTIFIER_FILE_ENDING);
}

// Render the Page beneath

?>
<?php // Template HTML Header (use include, file_get_content or direct HTML Content) ?>

<?php echo $content ?>

<?php // Template HTML Footer (use include, file_get_content or direct HTML Content) ?>
