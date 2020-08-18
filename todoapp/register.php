<!-------------Header---------------->
<?php 
include('./header.php');
if (!empty($_SESSION)) {
    header("location: index.php");
}
?>

<div class="registration">
    <div class="d-flex justify-content-end">
        <div class="registration-form">
            <form  id="registrationForm">
                <div class="form-group">
                    <label for="regName">Name</label>
                    <input type="text" class="form-control" name="regName" id="regName" placeholder="Enter Your Name">
                </div>
                <div class="form-group">
                    <label for="regEmail">Email Address</label>
                    <input type="email" class="form-control" name="regEmail" id="regEmail" placeholder="Enter Your Email Address">
                </div>
                <div class="form-group">
                    <label for="regPassword">Password</label>
                    <input type="password" class="form-control" name="regPassword" id="regPassword" placeholder="Enter Password">
                </div>
                <div class="form-group">
                    <label for="regCnfPassword">Confirm Password</label>
                    <input type="password" class="form-control" name="regCnfPassword" id="regCnfPassword" placeholder="Confirm Password">
                </div>
                <button type="button" id="registerBtn" class="w-100 btn btn-red">Register</button>
            </form>
        </div>
    </div>
</div>

<!-------------Footerfooter---------------->
<?php include('./footer.php'); ?>