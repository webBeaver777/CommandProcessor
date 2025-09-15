<?php

/**
 * Рекурсивно копирует содержимое каталога $src в $dst
 */
function copy_recursive($src, $dst)
{
    $dir = opendir($src);
    @mkdir($dst, 0777, true);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src.'/'.$file)) {
                copy_recursive($src.'/'.$file, $dst.'/'.$file);
            } else {
                copy($src.'/'.$file, $dst.'/'.$file);
            }
        }
    }
    closedir($dir);
}

$src = __DIR__.'/../publishable/tests/Feature';
$dst = __DIR__.'/../tests/Feature';
if (is_dir($src)) {
    copy_recursive($src, $dst);
    echo "Tests copied to $dst\n";
} else {
    echo "Source tests directory not found: $src\n";
}
