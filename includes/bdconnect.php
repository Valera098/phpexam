<?php
$link = mysqli_connect("127.0.0.1", "root", "root", "phpexam")
or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
?>
