<?php 
// ini_set('display_errors', 'On');
$p = 'Sd@03091997';
echo password_hash($p,PASSWORD_BCRYPT);
// // echo $_SERVER['SERVER_NAME'];
// if(isset($_POST['submit'])){
//     echo '<pre>';
//     print_r($_FILES['userfile']);
//     echo '</pre>';}

// include_once('controller/TodoApp.ctrl.php');

// echo constant("PATH_NAME");
// $u = new TodoApp('users');
// $u->getData();
?>
<!--<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">-->
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <!-- <input type="hidden" name="MAX_FILE_SIZE" value="30000" /> -->
    <!-- Name of input element determines name in $_FILES array -->
<!--    <input name="userfile" type="file" />-->
<!--    <input type="submit" value="Send File" name="submit"/>-->
<!--</form>-->

