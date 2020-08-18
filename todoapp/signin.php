<!-------------Header---------------->
<?php
ini_set('display_erros','1');
include('./header.php');
if (!empty($_SESSION)) {
    header("location: ./index.php");
}


?>

<div class="sign-in" id="sign-in">
    <div class="sign-in-form">
        <form id="signInForm">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Enter Your Email Address">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter Your Password">
            </div>
            <button type="button" name="signin" id="signin" class="w-100 btn btn-red"><i class="fa fa-sign-in" aria-hidden="true"></i> Sign In</button>
        </form>
        <a class="mt-2" href="#reset" style="float: right;text-decoration:none" id="reset_btn">Forgot Password?</a>
    </div>
    <div class="sign-in-img"></div>
</div>


<!-------------------RESET FORM------------------------->

<div class="sign-in" id="reset">
    <div class="sign-in-img reset-img"></div>
    <div class="sign-in-form">
        <form id="forgotPassForm">
            <div class="form-group">
                <h2>Forgot Password?</h2>
                <p>
                    Enter your email address below and we will send you an email with a link to choose a new password.
                </p>
            </div>
            <div class="form-group">
                <label for="forgotPassEmail">Email Address</label>
                <input type="email" class="form-control" name="forgotPassEmail" id="forgotPassEmail" placeholder="Enter Your Email Address">
            </div>
            <button type="button" id="resetPassBtn" class="w-100 btn btn-red">Request Password Reset</button>
        </form>
        <a class="mt-2" href="#sign-in" style="float: right;text-decoration:none" id="back">Back To Sign In</a>
    </div>
</div>

<!-------------Footerfooter---------------->
<?php include('./footer.php'); ?>
<script>
    $('#reset_btn').click(() => {
        $('#sign-in').hide();
        $('#reset').css('display', 'flex').show();
    });
    $('#back').click(() => {
        $('#sign-in').show();
        $('#reset').hide();
    });
</script>