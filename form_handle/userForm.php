<?php

header('content-type:application/json');
include_once('../controller/TodoApp.ctrl.php');
session_start();
if (empty($_SESSION)) {
    header('location:' .constant("PATH_NAME"));
}

//FOR EDIT OR UPDATE PROFILE
if (isset($_POST['editName']) && isset($_POST['editEmail'])) {
    $msg = [];
    $name = $_POST['editName'];
    $email = $_POST['editEmail'];
    $phone = "";
    $dob = "";
    $gender = "";
    $imgName = "";
    $path = "";

    $u = new TodoApp('users');
    $data = $u->getData('', ['id' => $_SESSION['id']])->fetch_assoc();
    if (!empty($data['img_path'])) {
        $path = $data['img_path'];
    }

    if (!empty($_POST['editPhone'])) {
        $phone = $_POST['editPhone'];
    }
    if (!empty($_POST['editDOB'])) {
        $dob = $_POST['editDOB'];
    }
    if (isset($_POST['customRadioInline1'])) {
        $gender = $_POST['customRadioInline1'];
    }

    if (isset($_FILES['editProfilePic'])) {
        if ($_FILES['editProfilePic']['name'] != '') {
            $file_name = $_FILES['editProfilePic']['name'];
            $file_size = $_FILES['editProfilePic']['size'];
            $file_tmp = $_FILES['editProfilePic']['tmp_name'];
            $file_type = $_FILES['editProfilePic']['type'];

            $extensions = array('image/jpg', 'image/jpeg', 'image/png');

            if (!in_array($file_type, $extensions)) {
                array_push($msg, ['status' => 0, 'msg' => 'Please choose a JPEG or PNG file.']);
            } else {
                if (!empty($path)) {
                    unlink($path);
                }
                if(!is_dir("../profilePic")){
                    mkdir("../profilePic");
                }
                $img_name = str_replace("image/",".",$file_type);
                $path = "../profilePic/" . $_SESSION['id'] . $img_name;
                move_uploaded_file($file_tmp, $path);
            }
        }
    }

    if (count($msg)===0) {
        $user = new TodoApp('users');
        $data = $user->getData();

        while ($row = $data->fetch_assoc()) {
            if ($row['email'] === $email && $row['id'] != $_SESSION['id']) {
                array_push($msg, ["status" => 0, "msg" => "A user with this email id is already registered."]);
            }
        }

        $newUser = new TodoApp('users');
        $newData = $user->getData('', ['id' => $_SESSION['id']]);
        $newRow = $newData->fetch_assoc();

        if ($newRow['email'] === $email) {
            $qry = $newUser->updateData(['id' => $_SESSION['id']], [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'gender' => $gender,
                'dob' => $dob,
                'img_path' => $path,
                'updated_at' => date("Y-m-d H:i:s")
            ]);



            if ($qry) {

                array_push($msg, ["status" => 1, "msg" => "Profile Updated Successfully"]);
            } else {
                array_push($msg, ["status" => 0, "msg" => "Something Went Wrong! Please Try After Some Time"]);
            }
        } else {
            $qry = $newUser->updateData(['id' => $_SESSION['id']], [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'gender' => $gender,
                'dob' => $dob,
                'img_path' => $path,
                'email_verified' => '0',
                'updated_at' => date("Y-m-d H:i:s")
            ]);

            if ($qry) {

                array_push($msg, ["status" => 1, "msg" => "Profile Updated Successfully"]);
            } else {
                array_push($msg, ["status" => 0, "msg" => "Something Went Wrong! Please Try After Some Time"]);
            }
        }
    }


    echo json_encode($msg);
}


//FOR REMOVE PROFILE PICTURE
if (isset($_POST['removeProfileImg']) && $_POST['removeProfileImg'] === 'removeProfileImg') {
    $msg = [];
    $user = new TodoApp('users');
    $data = $user->getData(['img_path'], ['id' => $_SESSION['id']])->fetch_assoc();

    if ($user->updateData(['id' => $_SESSION['id']], ['img_path' => ''])) {
        unlink("../profilePic/".$data['img_path']);
        array_push($msg, ['status' => 1, 'msg' => 'Profile Picture Deleted Successfully']);
    } else {
        array_push($msg, ['status' => 1, 'msg' => 'Something went wrong! Please try after some time!']);
    }
    echo json_encode($msg);
}



//FOR RESET PASSWORD
if (isset($_POST['editPassword']) && isset($_POST['oldPassword']) && isset($_POST['editCnfPassword'])) {
    $oldPass = $_POST['oldPassword'];
    $chngPass = $_POST['editPassword'];
    $cnfChngPass = $_POST['editCnfPassword'];

    $msg = [];

    $user = new TodoApp('users');
    $data = $user->getData(['password'], ['id' => $_SESSION['id']], '', 'ASC', 1);
    $row = $data->fetch_assoc();

    if (password_verify($oldPass, $row['password'])) {
        $chngPass = password_hash($chngPass, PASSWORD_BCRYPT);
        $qry = $user->updateData(['id' => $_SESSION['id']], ['password' => $chngPass]);
        if ($qry) {
            array_push($msg, ["status" => 1, "msg" => "Password Changed Successfully"]);
        } else {
            array_push($msg, ["status" => 0, "msg" => "Something Went Wrong! Please Try After Some Time"]);
        }
    } else {
        array_push($msg, ["status" => 0, "msg" => "Please Enter Your Correct Password"]);
    }

    echo json_encode($msg);
}



//FOR VERIFY MAIL
if (isset($_POST['verifyEmail']) && $_POST['verifyEmail'] === 'verifyEmail') {

    $user = new TodoApp('users');
    $data = $user->getData('', ['id' => $_SESSION['id']]);
    $row = $data->fetch_assoc();

    $link = "http://".$_SERVER['SERVER_NAME']."/www/todo/todoapp/emailverify.php?uid=" . $row['id'] . "&vtoken=" . $row['vtoken'];
    $to = $row['email'];
    $from = "subirdakshi18@gmail.com";
    $sub = "Email Verification Link From TODO APP";
    $message = "Hi, " . $row['name'] . " <br><br> Please click the link below to verify your email address.<br>$link";
    $headers = "From: subirdakshi18@gmail.com" . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $mail = mail($to, $sub, $message, $headers);
    $msg = [];
    if ($mail) {
        array_push($msg, ["status" => 1, "msg" => "A verification mail has sent to your email address."]);
    } else {
        array_push($msg, ["status" => 0, "msg" => "Something Went Wrong! Please Try After Some Time"]);
    }

    echo json_encode($msg);
}
