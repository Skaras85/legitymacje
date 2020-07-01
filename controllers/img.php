<?php
$imagepath = './images/karty/1.jpg';
header("Content-Type: image/jpeg");
header("Content-Length: " . filesize($imagepath));
echo file_get_contents($imagepath);

exit;
?>
