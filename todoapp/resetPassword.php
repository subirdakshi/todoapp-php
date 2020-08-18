<?php 

include('header.php');
if (isset($_GET['uid']) && isset($_GET['ptoken']) && !empty($_GET['uid']) &&  !empty($_GET['ptoken'])) {
    $id = $_GET['uid'];
    $ptoken = $_GET['ptoken'];
    $user = new TodoApp('users');
    $data = $user->getData('', ['id' => $id, 'ptoken' => $ptoken]);
    if ($data->num_rows > 0) {
        $row = $data->fetch_assoc();
    } else {
        echo "<script>alert('It looks like data is not exist!');window.location='index.php'</script>";
    }
} else {
    header('location:index.php');
}
?>
<?php
if ($row['password_change'] === '0') {
?>
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6 text-center">
                <h1>Reset Password</h1>
                <form class="mt-5" id="rPassform">
                    <div class="form-group">
                        <p class="form-control form-control-lg bg-light text-left"><?php echo $row['email'] ?></p>
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-lg" type="password" name="rpassword" id="rpassword" placeholder="Enter Password">
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-lg" type="password" name="rcpassword" id="rcpassword" placeholder="Enter Confirm Password">
                    </div>
                    <input type="hidden" name="rid" value="<?php echo $row['id'] ?>">
                    <button type="button" id="RpassBtn" class="btn btn-lg text-white w-100" style="background: #ffb420;">Reset Password</button>
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
<?php } else { ?>
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6 text-center">
                <div class="container alert alert-warning">
                    <h1 class="mt-5">Password Already Changed! </h1><br>
                    <?php if(empty($_SESSION)){?>
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
<?php } ?>
<?php include('footer.php') ?>
<script>
    $(document).ready(function() {
        //SUCCESS OR ERROR MSG FUNCTION
        function showMsg(status, msg) {
            if (status === 1) {
                Swal.fire({
                    title: 'Success!',
                    icon: 'success',
                    text: msg,
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: msg,
                    icon: 'error',
                    confirmButtonText: 'ok'
                });
            }
        }


        $("#RpassBtn").click(() => {
            const rpassword = $('#rpassword');
            const rcpassword = $('#rcpassword');
            let res = true;

            if (rpassword.val().trim() === '' && rcpassword.val().trim() === '') {
                res = false;
                showMsg(0, 'All fields are mandatory');
                rpassword.css('border', '1px solid red');
                rcpassword.css('border', '1px solid red');
            } else {
                if (rpassword.val().trim() === '') {
                    res = false;
                    showMsg(0, 'All fields are mandatory');
                    rpassword.css('border', '1px solid red');
                } else {
                    rpassword.css('border', '1px solid #ced4da');
                }

                if (rcpassword.val().trim() === '') {
                    res = false;
                    showMsg(0, 'All fields are mandatory');
                    rcpassword.css('border', '1px solid red');
                } else if (rpassword.val().trim() !== rcpassword.val().trim()) {
                    res = false;
                    showMsg(0, 'Confirm password does not match');
                    rcpassword.css('border', '1px solid red');
                } else {
                    rcpassword.css('border', '1px solid #ced4da');
                }

                if (res === true) {
                    fetch(PATH_NAME_JS+"/form_handle/userSignReg.php", {
                        method: 'POST',
                        body: new FormData(document.getElementById('rPassform'))
                    }).then(response => response.json()).then(data => {
                        if (data[0]['status'] === 1) {
                            showMsg(1, data[0]['msg']);
                            setTimeout(() => {
                                window.location = 'signin.php'
                            }, 2000);
                        } else {
                            showMsg(0, data[0]['msg']);
                        }
                    });
                }
            }
        });
    });
</script>