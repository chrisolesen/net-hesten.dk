<?php

/* PNG */
$image = filter_input(INPUT_GET, 'image');
$im = false;
if (file_exists("./imgs/$image")) {
    $im = imagecreatefrompng("./imgs/$image");
    imagesavealpha($im, true);
} else if (file_exists("../../www.net-hesten.dk/imgHorse/$image")) {
    $im = imagecreatefrompng("../../www.net-hesten.dk/imgHorse/$image");
    imagesavealpha($im, true);
}
if ($im) {

    header('Content-Type: image/png');
    imagepng($im);
    imagedestroy($im);
}