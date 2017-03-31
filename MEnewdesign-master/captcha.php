<?php
session_start();
unset($_SESSION['captchaWord']);

if (empty($_SESSION['captchaWord'])) {
    $str = "";
    $length = 0;
    for ($i = 0; $i < 4; $i++) {
        // this numbers refer to numbers of the ascii table (small-caps)
        $str .= chr(rand(97, 122));
    }
    $_SESSION['captchaWord'] = $str;
   }


$imgX = 70;
$imgY = 30;
$image = imagecreatetruecolor(70, 30);

$backgr_col = imagecolorallocate($image, 238,239,239);
$border_col = imagecolorallocate($image, 208,208,208);
$text_col = imagecolorallocate($image, 46,60,31);

imagefilledrectangle($image, 0, 0, 70, 30, $backgr_col);
imagerectangle($image, 0, 0, 69, 29, $border_col);

$font = "fonts/mavenpro-bold-webfont.ttf"; // it's a Bitstream font check www.gnome.org for more
$font_size = 10;
$angle = 0;
$box = imagettfbbox($font_size, $angle, $font, $_SESSION['captchaWord']);
$x = (int)($imgX - $box[4]) / 2;
$y = (int)($imgY - $box[5]) / 2;
imagettftext($image, $font_size, $angle, $x, $y, $text_col, $font, $_SESSION['captchaWord']);

header("Content-type: image/png");
imagepng($image);
imagedestroy ($image);
?>