<!-------------Header---------------->
<?php include('./header.php');

if (empty($_SESSION)) {
    header("location: signin.php");
} else {
    $user = new TodoApp('users');
    $data = $user->getData('', ['id' => $_SESSION['id']]);
    $row = $data->fetch_assoc();
    if (empty($row['img_path'])) {
        $img_path = "../profilePic/defaultUser.png";
    } else {
        $img_path = $row['img_path'];
    }
}
?>

<div class="container mt-4">
    <nav class="pt-2">
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Profile</a>
            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Edit Profile</a>
            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Change Password</a>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <div class="row">
                <div class="col-md-6 order-2-cc">
                    <ul>
                        <li><strong>Name: </strong> <?php echo $row['name']; ?></li>
                        <li><strong>Email: </strong> <?php echo $row['email']; ?></li>
                        <li><strong>Phone: </strong> <?php echo $row['phone']; ?></li>
                        <li><strong>Gender: </strong> <?php echo $row['gender']; ?></li>
                        <li><strong>D.O.B: </strong> <?php echo $row['dob']; ?></li>
                        <li><strong>Registered On: </strong> <?php echo date("d/m/Y", strtotime($row['created_at'])); ?></li>
                        <li><strong>Email Verified: </strong>
                            <?php
                            if ($row['email_verified'] != 1) {
                                echo "Not Verified! <button class='verifyEmailBtn btn btn-sm text-white' style='background:#ffb420'>Verify Now</button>";
                            } else {
                                echo "Verified!";
                            }
                            ?>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 order-1-cc">
                    <div class="profilePic">
                        <img style="width: 60vmin;height: 60vmin;padding: 10px;border-radius:50%" src="<?php echo $img_path ?>" alt="" srcset="">
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <div class="row">
                <div class="col-md-6">
                    <div class="m-767-margin-pc profilePic d-flex flex-column justify-cntent-center align-items-center" style="margin:50px 0; min-height:80vh">
                        <img style="width: 60vmin;height: 60vmin;padding: 10px;border-radius:50%" src="<?php echo $img_path ?>" alt="" srcset="">
                    </div>
                </div>
                <div class="m-767-margin-reg-from col-md-6 d-flex flex-column justify-cntent-center align-items-center" style="padding:50px 0;min-height: 80vh;">
                    <form method="POST" enctype="multipart/form-data" name="updateProfileForm" id="updateProfileForm">

                        <?php
                        if (!empty($row['img_path'])) {
                        ?>
                            <div class="form-group">
                                <button id="removeProfileImg" name="removeProfileImg" type="button" class="btn btn-red w-100">Remove Photo</button>
                            </div>
                        <?php } ?>

                        <div class="form-group">
                            <input type="file" class="form-control-file" name="editProfilePic" id="editProfilePic">
                        </div>
                        <div class="form-group">
                            <input type="text" name="editName" id="editName" class="form-control form-control-lg" placeholder="Enter Your Name" value="<?php echo $row['name']; ?>">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control form-control-lg" name="editEmail" id="editEmail" placeholder="Enter Your Email Address" value="<?php echo $row['email']; ?>">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-lg" name="editPhone" id="editPhone" placeholder="Enter Your Phone Number" value="<?php echo $row['phone']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="editDOB">Date Of Birth</label>
                            <input type="date" class="form-control form-control-lg" name="editDOB" id="editDOB" value="<?php if (!empty($row['dob'])) {
                                                                                                                            echo date("Y-m-d", strtotime($row['dob']));
                                                                                                                        } ?>">
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input value="Male" type="radio" id="customRadioInline1" name="customRadioInline1" class="custom-control-input" <?php if ($row['gender'] === 'Male') {
                                                                                                                                                    echo 'checked';
                                                                                                                                                } ?>>
                                <label class="custom-control-label" for="customRadioInline1">Male</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input value="Female" type="radio" id="customRadioInline2" name="customRadioInline1" class="custom-control-input" <?php if ($row['gender'] === 'Female') {
                                                                                                                                                        echo 'checked';
                                                                                                                                                    } ?>>
                                <label class="custom-control-label" for="customRadioInline2">Female</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input value="Other" type="radio" id="customRadioInline3" name="customRadioInline1" class="custom-control-input" <?php if ($row['gender'] === 'Other') {
                                                                                                                                                        echo 'checked';
                                                                                                                                                    } ?>>
                                <label class="custom-control-label" for="customRadioInline3">Other</label>
                            </div>
                        </div>
                        <button type="button" name="updateProfile" id="updateProfile" class="w-100 btn text-white" style="background: #ffb420;font-size: 20px;">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
            <div class="row">
                <div class="nav-contact col-md-6 d-flex flex-column justify-cntent-center" style="min-height: 80vh;">
                    <form name="changePassForm" action="#" method="POST" style="margin: 50px 0; padding: 50px;" id="reset-pwd-form">
                        <div class="form-group">
                            <input type="password" class="form-control form-control-lg" name="oldPassword" id="oldPassword" placeholder="Enter Your Password">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control form-control-lg" name="editPassword" id="editPassword" placeholder="Enter New Password">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control form-control-lg" name="editCnfPassword" id="editCnfPassword" placeholder="Re-Enter Password">
                        </div>
                        <a type="button" name="changePass" id="changePass" class="w-100 btn text-white" style="background: #7668fa;font-size:20px">Change Password</a>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="hide-on-sm" style="height: 80vh;">
                        <img style="height: 100%;" class="w-100" src="https://wppro.nl/wp-content/uploads/2013/07/passwords.jpg" alt="" srcset="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-------------Footer---------------->
<?php include('./footer.php'); ?>