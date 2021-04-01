<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="shortcut icon" href="/images/icon.ico" type="image/x-icon">
    <title>Панель администратора</title>
</head>
<body>
<div class="container">
<?php
// session_start();
require "includes/bdconnect.php";
require "includes/input_type.php";
if (isset($_GET['logout']))
{
    unset($_SESSION['password']);
    unset($_POST['password']);
    exit();
}
if(!empty($_POST)){
     $_SESSION['password']=$_POST['password'];
}
if($_SESSION['password']=='12345'||$_GET['status']){
    if($_POST['password']=='12345' || $_SESSION['password']=='12345'||$_GET['status']){
        echo '<h3> Панель Администратора </h3></br>';
        if($_GET['status']=='list'){
            $result = mysqli_query($link,'SELECT session_link, theme FROM sessions');
            echo '<h3> Актуальные сессии: </h3>';
            echo '<div class="list"> <ol>';
            while ($row = mysqli_fetch_array($result)){
                echo'
                    <li><div><h5>Ссылка на сессию: <a href="//'.$_SERVER['SERVER_NAME'].'/?link='.$row['session_link'].'">' . $_SERVER['SERVER_NAME'] . '/?link=' . $row['session_link'] .
                '</a> <a href="//' . $_SERVER['SERVER_NAME'] .'/admin.php?status=edit&id=' . $row['session_link'] . '" class="editLink">[Редактировать]</a>'.
                '<a href="//' . $_SERVER['SERVER_NAME'] .'/admin.php?status=delete&id=' . $row['session_link'] . '" class="editLink">[Удалить]</a></h5></div></li>';
            }
            echo '</ol> </div>';
        } else {
            echo '<a href="//'.$_SERVER[SERVER_NAME].'/admin.php?status=list"><h4>Список сессий</a><h4><br>';
        }

        echo '<a href="?status=add"><h4>Добавить новую сессию</h4></a></br>';
        echo '<a href="admin.php?logout"><h4>Выйти</h4></a><br>';
        if($_GET['status']=='edit' and $_GET['id']){
            $result = mysqli_query($link, 'SELECT theme, questions FROM `sessions` WHERE session_link = "' . $_GET['id'] . '"');
            $result = mysqli_fetch_array($result);
            $theme = $result['theme'];
            $questions = json_decode($result['questions']);
            echo'
            <h1>Изменение сессии</h1></br>
            <form method="post">
            <label for="theme">Тема сессии</label>
            <input type="text" id="theme" name="theme" value="'.$theme.'"></br>
            <label for="count_questions">Количество вопросов в сессии</label>
            <br>
            ';
            if(!$_POST['count_questions']){
                echo '<input type="number" id="count_questions" name="count_questions" value="'.count($questions).'">
                    </br>
                    <input type="submit" value="Выбрать">';
            } else {
                echo '<input type="number" id="count_questions" name="count_questions" value="'.$_POST['count_questions'].'">
                    </br>';
            }
            if($_POST['theme']){
                  echo '<h2>Тема сессии '.$_POST['theme'].'</h2>';
            }
            $selectedArray = Array();
            if($_POST['count_questions'] && !$_POST['theme0']){
                for ($i = 0; $i < count($questions); $i++) {
                    echo '
                <label for="theme' . $i . '">Вопрос №' . ($i+1) . '</label>
                <select name="theme' . $i . '" id="theme' . $i . '" value="'.$_POST['theme'.$i].'">
                    <option value="number"'; if(($questions[$i] -> type)=='number'){echo 'selected'; $selectedArray[$i]='number';}echo '>Число</option>
                    <option value="positive_number"'; if(($questions[$i] -> type)=='positive_number'){echo 'selected'; $selectedArray[$i]='positive_number';}echo '>Положительно число</option>
                    <option value="small_text"'; if(($questions[$i] -> type)=='small_text'){echo 'selected'; $selectedArray[$i]='small_text';}echo '>строка</option>
                    <option value="big_text"'; if(($questions[$i] -> type)=='big_text'){echo 'selected'; $selectedArray[$i]='big_text';}echo '>текст</option>
                    <option value="radio"'; if(($questions[$i] -> type)=='radio'){echo 'selected'; $selectedArray[$i]='radio';}echo '>С единственным выбором</option>
                    <option value="checkbox"'; if(($questions[$i] -> type)=='checkbox'){echo 'selected'; $selectedArray[$i]='checkbox';}echo '>С множественным выбором</option>
                </select>
                <br><br>
                '; }
                for ($i = count($questions); $i < $_POST['count_questions']; $i++) {
                    echo '
                <label for="theme' . $i . '">Вопрос №' . ($i+1) . '</label>
                <select name="theme' . $i . '" id="theme' . $i . '" value="'.$_POST['theme'.$i].'">
                    <option value="number"'; if($_POST['theme'.$i]=='number'){echo 'selected'; $selectedArray[$i]='number';}echo '>Число</option>
                    <option value="positive_number"'; if($_POST['theme'.$i]=='positive_number'){echo 'selected'; $selectedArray[$i]='positive_number';}echo '>Положительно число</option>
                    <option value="small_text"'; if($_POST['theme'.$i]=='small_text'){echo 'selected'; $selectedArray[$i]='small_text';}echo '>строка</option>
                    <option value="big_text"'; if($_POST['theme'.$i]=='big_text'){echo 'selected'; $selectedArray[$i]='big_text';}echo '>текст</option>
                    <option value="radio"'; if($_POST['theme'.$i]=='radio'){echo 'selected'; $selectedArray[$i]='radio';}echo '>С единственным выбором</option>
                    <option value="checkbox"'; if($_POST['theme'.$i]=='checkbox'){echo 'selected'; $selectedArray[$i]='checkbox';}echo '>С множественным выбором</option>
                </select>
                <br><br>
                '; }
                echo '<input type="submit" value="Выбрать типы вопросов">';

            }
            if($_POST['count_questions'] && $_POST['theme0']){
                for ($i = 0; $i < $_POST['count_questions']; $i++) {
                    echo '
                <label for="theme' . $i . '">Вопрос №' . ($i+1) . '</label>
                <select name="theme' . $i . '" id="theme' . $i . '" value="'.$_POST['theme'.$i].'">
                    <option value="number"'; if($_POST['theme'.$i]=='number'){echo 'selected'; array_push($selectedArray,'number');}echo '>Число</option>
                    <option value="positive_number"'; if($_POST['theme'.$i]=='positive_number'){echo 'selected'; array_push($selectedArray,'number');}echo '>Положительно число</option>
                    <option value="small_text"'; if($_POST['theme'.$i]=='small_text'){echo 'selected'; array_push($selectedArray,'number');}echo '>строка</option>
                    <option value="big_text"'; if($_POST['theme'.$i]=='big_text'){echo 'selected'; array_push($selectedArray,'number');}echo '>текст</option>
                    <option value="radio"'; if($_POST['theme'.$i]=='radio'){echo 'selected'; array_push($selectedArray,'number');}echo '>С единственным выбором</option>
                    <option value="checkbox"'; if($_POST['theme'.$i]=='checkbox'){echo 'selected'; array_push($selectedArray,'number');}echo '>С множественным выбором</option>
                </select>
                <br><br>
                '; }
                for ($i = 0; $i < count($questions); $i++){
                    echo '<label for="question'.$i.'">Вопрос№' . ($i+1) . ': </label>
                             <input type="text" id="question'.$i.'"  name="question'.$i.'" value="'.($questions[$i] -> question).'" required>';
                    if($_POST['theme'.$i]=='radio'||$_POST['theme'.$i]=='checkbox'){
                        echo '<label for="options'.$i.'">Варианты ответов(Перечислите через ","): </label>
                             <input type="text" id="options'.$i.'" name="options'.$i.'" value="'.
                             ($questions[$i]->options).'" required>';
                    }
                    echo '<label for="answer'.$i.'">Ответ: </label>
                             <input type="text" id="answer'.$i.'" name="answer'.$i.'" value="'.($questions[$i] -> answer).'" required><br><br>';
                 }
                for ($i = count($questions); $i < $_POST['count_questions']; $i++){
                   echo '<label for="question'.$i.'">Вопрос№' . ($i+1) . ': </label>
                            <input type="text" id="question'.$i.'"  name="question'.$i.'"required>';
                   if($_POST['theme'.$i]=='radio'||$_POST['theme'.$i]=='checkbox'){
                       echo '<label for="options'.$i.'">Варианты ответов(Перечислите через ","): </label>
                            <input type="text" id="options'.$i.'" name="options'.$i.'" required>';
                   }
                   echo '<label for="answer'.$i.'">Ответ: </label>
                            <input type="text" id="answer'.$i.'" name="answer'.$i.'" required><br><br>';
                }
                echo '
                <input name="session_link" id="session_link" type="text" value="'.$_GET['id'].'"><label for="session_link">Ссылка на сессию</label>
                <input type="submit" value="Изменить сессию">';
            }
            if($_POST['question0']){
                if(!$_POST['session_link']){
                $session_link = bin2hex(random_bytes(5));
                }else{
                    $session_link=$_POST['session_link'];
                }
                $questions=Array();
                for ($i = 0; $i < $_POST['count_questions']; $i++){
                    $questions[$i]['type']=$_POST['theme'.$i];
                    $questions[$i]['question']=$_POST['question'.$i];
                        $questions[$i]['options']=$_POST['options'.$i];
                    $questions[$i]['answer']=$_POST['answer'.$i];
                }
                $questions = json_encode($questions, JSON_UNESCAPED_UNICODE);
                $theme = $_POST['theme'];
                $questions_query="UPDATE `sessions` SET session_status = 'active', theme='$theme', questions='$questions'
                                WHERE session_link='$session_link'";
                $result = mysqli_query($link, $questions_query) or die("Ошибка " . mysqli_error($link));
                unset($_POST);
            }
            echo '</form>';
        }
        if($_GET['status']=='add'){
            echo'
            <div class="card-">
            <h3>Создание новой сессии</h3></br>
            <form method="post">
            <label for="theme">Тема сессии</label>
            <br>
            <input type="text" id="theme" name="theme" value="'.$_POST['theme'].'"></br>
            <label for="count_questions">Количество вопросов в сессии</label>
            <br>
            <input type="number" id="count_questions" name="count_questions" value="'.$_POST['count_questions'].'"></br>
            </div>';
            if(!$_POST['count_questions']){
                echo '<input type="submit" value="Выбрать">';
            }
            if($_POST['theme']){
                  echo '<h2>Тема сессии '.$_POST['theme'].'</h2>';
            }
            for ($i = 0; $i < $_POST['count_questions']; $i++) {
                echo '
            <label for="theme' . $i . '">Вопрос №' . ($i+1) . '</label>
            <select name="theme' . $i . '" id="theme' . $i . '" value="'.$_POST['theme'.$i].'">
                <option value="number"'; if($_POST['theme'.$i]=='number')echo 'selected';echo '>Число</option>
                <option value="positive_number"'; if($_POST['theme'.$i]=='positive_number')echo 'selected';echo '>Положительно число</option>
                <option value="small_text"'; if($_POST['theme'.$i]=='small_text')echo 'selected';echo '>строка</option>
                <option value="big_text"'; if($_POST['theme'.$i]=='big_text')echo 'selected';echo '>текст</option>
                <option value="radio"'; if($_POST['theme'.$i]=='radio')echo 'selected';echo '>С единственным выбором</option>
                <option value="checkbox"'; if($_POST['theme'.$i]=='checkbox')echo 'selected';echo '>С множественным выбором</option>
            </select>
            <br><br>
            '; }
            if($_POST['count_questions'] && !$_POST['theme0']){
                echo '<input type="submit" value="Выбрать типы вопросов">';
            }
            if($_POST['count_questions'] && $_POST['theme0']){
                for ($i = 0; $i < $_POST['count_questions']; $i++){
                   echo '<label for="question'.$i.'">Вопрос№' . ($i+1) . ': </label>
                            <input type="text" id="question'.$i.'"  name="question'.$i.'"required>';
                   if($_POST['theme'.$i]=='radio'||$_POST['theme'.$i]=='checkbox'){
                       echo '<label for="options'.$i.'">Варианты ответов: </label>
                            <input type="text" id="options'.$i.'" name="options'.$i.'" required>';
                   }
                   echo '<label for="answer'.$i.'">Ответ: </label>
                            <input type="text" id="answer'.$i.'" name="answer'.$i.'" required><br><br>';
                }
                echo '
                <label for="session_link">Ссылка на сессию</label> <input name="session_link" id="session_link" type="text">
                <input type="submit" value="Создать сессию">';
            }
            if($_POST['question0']){
                if(!$_POST['session_link']){
                $session_link = bin2hex(random_bytes(5));
                }else{
                    $session_link=$_POST['session_link'];
                }
                $questions=Array();
                for ($i = 0; $i < $_POST['count_questions']; $i++){
                    $questions[$i]['type']=$_POST['theme'.$i];
                    $questions[$i]['question']=$_POST['question'.$i];
                        $questions[$i]['options']=$_POST['options'.$i];
                    $questions[$i]['answer']=$_POST['answer'.$i];
                }
                $questions = json_encode($questions, JSON_UNESCAPED_UNICODE);
                $theme = $_POST['theme'];
                $questions_query="INSERT INTO `sessions` (session_link, session_status, theme, questions)
                        VALUES ('$session_link', 'active', '$theme', '$questions')";
                $result = mysqli_query($link, $questions_query) or die("Ошибка " . mysqli_error($link));
                unset($_POST);
            }
            echo '</form>';
        }
    }else{
        echo 'Возникла ошибка';
    }
}else{
    print_r($_SESSION['password']);
    echo '<form action="admin.php" method="post">
    <p>Введите пароль для доступа к этой стрнице: </p><br>
    <input type="password" name="password">
    <input type="submit">
    </form>';
}
?>
</div>
<script src="main.js"></script>
</body>
</html>
