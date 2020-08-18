<?php
include('./header.php');
$msg = '';
if (isset($_GET['uid']) && isset($_GET['vtoken']) && !empty($_GET['uid'])  && !empty($_GET['vtoken'])) {
    $msg = '';
    $user = new TodoApp('users');
    $data = $user->getData('', ['id' => $_GET['uid'], 'vtoken' => $_GET['vtoken']]);
    if ($data->num_rows > 0) {
        $row = $data->fetch_assoc();
        if ($row['email_verified'] != 1) {
            if ($row['vtoken'] === $_GET['vtoken']) {
                $nuser = new TodoApp('users');
                $nuser->updateData(['id' => $row['id']], ['email_verified' => '1']);
                $msg.= "Email Verified Successfully.";
                
            } else {
                $msg.= "Something went wrong please try after some time";
            }
        }else{
            $msg.="Email Already Verified";
        }
    } else {
        echo "<script>alert('It looks like data is not exist!');window.location='index.php'</script>";
    }
}else{
    header("location:index.php");
}
?>
<div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6 text-center">
                <div class="container alert alert-warning">
                    <h1 class="mt-5"><?php echo $msg;?> </h1><br>
                    <?php 
                    if(empty($_SESSION)){
                    ?>
                    <a href="signin.php" class="btn btn-lg btn-red w-100 mt-3 mb-2"><i class="fa fa-sign-in"></i> Sign In</a>
                    <p>OR</p>
                    <a href="register.php" class="btn btn-lg btn-green w-100 mt-2 mb-3"><i class="fa fa-user"></i> Register</a>
                    <?php }else{?>
                        <a href="index.php" class="btn btn-lg btn-red w-100 mt-3 mb-2"><i class="fa fa-home"></i> Go To Home Page</a> 
                    <?php }?>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>

    <?php include_once('footer.php')?>