<?php
$link = mysqli_connect("127.0.0.1", "root", "root", "phpexam")
//$link = mysqli_connect("std-mysql", "std_967", "12345678", "std_967")
or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
?>
