<?php 
session_start();
include_once('../controller/TodoApp.ctrl.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do App</title>
    <link rel="shortcut icon" href="https://raw.githubusercontent.com/fabiospampinato/vscode-todo-plus/master/resources/logo/logo.png" type="image/x-icon">

    <!----------Bootsrap CSs------------>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <!----------Fontawesome CSs------------>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!----------Custom CSs------------>
    <link rel="stylesheet" href="../public/css/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <div class="width-80 text-center">
                <a class="navbar-brand" href="index.php"><img src="https://raw.githubusercontent.com/fabiospampinato/vscode-todo-plus/master/resources/logo/logo.png" width="40px"> To Do App</a>
            </div>
            <div div class="width-20 text-right">
                <button class="nav-butt navbar-toggler text-white" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link pl-3 pr-3" href="index.php"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
                    </li>
                    <?php
                    if(empty($_SESSION)){
                    ?>
                    <li class="nav-item">
                        <a class="nav-link pl-3 pr-3" href="signin.php"><i class="fa fa-sign-in" aria-hidden="true"></i> Sign In</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3 pr-3" href="register.php"><i class="fa fa-user" aria-hidden="true"></i> Register</a>
                    </li>
                    <?php    
                    }else{
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Hi, <?php 
                                $user = new TodoApp('users');
                                $data = $user->getData(['name'],['id'=>$_SESSION['id']])->fetch_assoc();
                                echo $data['name'];
                            ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="profile.php"><i class="fa fa-gear"></i> Setting</a>
                            <a class="dropdown-item" href="signout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Sign out</a>
                        </div>
                    </li>
                    <?php }?>
                </ul>
            </div>
        </div>
    </nav>