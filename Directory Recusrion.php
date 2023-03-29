<?php
error_reporting(0);

if (isset($_GET['file'])) {
    $path    = '.' . DIRECTORY_SEPARATOR . $_GET['file'];
} else {
    $path    = './';
}

function ext($file)
{
    $image_info = explode(".", $file);
    return end($image_info);
}

function display_size($bytes, $precision = 2)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, $precision) . '<span class="fs-0-8 bold">' . $units[$pow] . "</span>";
}

function count_dir_files($dir)
{
    global $path;
    $fi = new FilesystemIterator($path . "/" . $dir, FilesystemIterator::SKIP_DOTS);
    return iterator_count($fi);
}

function get_directory_size($path)
{
    $bytestotal = 0;
    $path = realpath($path);
    if ($path !== false && $path != '' && file_exists($path)) {
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object) {
            $bytestotal += $object->getSize();
        }
    }
    return display_size($bytestotal);
}

$ignore_file_list = array(".htaccess", "Thumbs.db", ".DS_Store", "index.php", "config.php", "test-setup");
$ignore_ext_list = array("php", "css", "js", "html");
// $ignore_folder_list = array("test-setup");
$force_download = true;

$files = array_diff(scandir($path), array('.', '..'));
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Vincitore Volare Assets</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../volare-marketing-assets-file/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body class="bg-color">

    <div class="container">

        <div class="row mb-3">
            <div class="col-md-12 col-lg-12 text-center pt-5 mb-3">
                <img src="https://vincitorerealty.com/vincitore-volare/assets/img/mainlogo.png" width="150px">
            </div>
        </div>
        <?php if (isset($_GET['file'])) {
            $path    = '.' . DIRECTORY_SEPARATOR . $_GET['file'];
            $list = explode('/', $path);
            $list[0] = substr($list[0], 2); ?>
            <nav class="navbar navbar-inverse">
                <ul class="nav navbar-nav">
                    <li><a href="https://vincitorerealty.com/volare-marketing-assets/"> All List → </a></li>
                    <?php
                    $urlappend = 'https://vincitorerealty.com/volare-marketing-assets/?file=';
                    foreach ($list as $data) {
                        $urlappend .= $data . '/';
                        echo '<li><a href="' . $urlappend . '">' . $data . ' → </a></li> ';
                    } ?>
                </ul>
            </nav>
        <?php } ?>
        <div class="row pt-7">
            <?php
            if (isset($files) && !empty($files)) {
                foreach ($files as $file) {
                    $file_ext = ext($file);
                    if (in_array($file, $ignore_file_list)) continue;
                    if (in_array($file_ext, $ignore_ext_list)) continue;
                    if (is_dir($path . "/" . $file)) { ?>
                        <div class="col-md-3 col-lg-3 text-center mb-3">
                            <a href="<?= isset($_GET['file']) ? ('?file=' . $_GET['file'] . '/' . $file) : '?file=' . $file ?>">
                                <div class="folder_box">
                                    <img src="https://vincitorerealty.com/volare-marketing-assets-file/icons/folder.png" width="150px">
                                    <p class="title"><?= $file ?> </p>
                                    <!--<p class="title"><?= count_dir_files($file) ?> item </p>-->
                                </div>
                            </a>
                        </div>
                    <?php } else {
                        $image_path = 'https://vincitorerealty.com/volare-marketing-assets-file/icons/';
                        $download_att = ($force_download and $file_ext != "dir") ? " download='" . basename($file) . "'" : ""; ?>
                        <div class="col-md-3 col-lg-3 text-center mb-3">
                            <a href="<?= $path . "/" . $file ?>" <?= $download_att ?> target="_blank">
                                <div class="folder_box <?= $file_ext ?>">
                                    <!-- <img class="image <?= $file_ext ?>" width="150px"> -->
                                    <img src="<?= $image_path . $file_ext . '.png' ?>" width="150px">
                                    <p class="title"><?= $file ?> </p>
                                    <!--<p class="title"><?= display_size(filesize($path . "/" . $file)) ?> </p>-->
                                </div>
                            </a>
                        </div>
            <?php }
                }
            } else {
                echo '<p class="title" style="font-size:29px;text-align:center">No Files and Folder Found</p>';
            }
            ?>
        </div>
    </div>
    <script src="" async defer></script>
</body>

</html>