<?php

header('content-type:application/json');
include_once('../controller/TodoApp.ctrl.php');

session_start();

//FOR SIGNIN
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $msg = [];

    if (empty($email)) {
        array_push($msg, ['status' => 0, 'msg' => "Please Enter Email Address"]);
    } elseif (empty($pass)) {
        array_push($msg, ['status' => 0, 'msg' => "Please Enter Password"]);
    } else {
        $user = new TodoApp('users');
        $data = $user->getData('', ['email' => $email], '', 'ASC', 1);

        if ($data->num_rows > 0) {
            $row = $data->fetch_assoc();
            if (password_verify($pass, $row['password'])) {
                $_SESSION = [
                    'id' => $row['id'],
                    // 'name' => $row['name'],
                    // 'email' => $row['email'],
                    'email_verified'=>$row['email_verified']
                ];
                array_push($msg, ['status' => 1, 'msg' => "Login Successfully! Redirecting..."]);
            } else {
                array_push($msg, ['status' => 0, 'msg' => "Please Enter Correct Password"]);
            }
        } else {
            array_push($msg, ['status' => 0, 'msg' => "It seems like email address is not registered with us"]);
        }
    }

    echo json_encode($msg);
}


//FOR REGISTRATION
if (isset($_POST['regName']) && $_POST['regEmail'] && $_POST['regPassword'] && isset($_POST['regCnfPassword'])) {
    $name = $_POST['regName'];
    $email = $_POST['regEmail'];
    $pass = $_POST['regPassword'];
    $cnfpass = $_POST['regCnfPassword'];

    $msg = [];

    $user = new TodoApp('users');
    $data = $user->getData(['id'], ['email' => $email]);

    if ($data->num_rows === 0) {
        $pass = password_hash($pass, PASSWORD_BCRYPT);

        $vtoken = md5(time());
        $userData = ['name' => $name, 'email' => $email, 'password' => $pass, 'vtoken' => $vtoken, 'created_at'=>Date("Y-m-d H:i:s"), 'updated_at'=>Date("Y-m-d H:i:s")];
        
        $newUser = new TodoApp('users');

        if ($newUser->insertData($userData)) {
            array_push($msg, ["status" => 1, "msg" => "Registration successfull. Login to your account and write your first todo."]);
            $u = new TodoApp('users');
            $d = $u->getData('', ['email' => $email])->fetch_assoc();
            $link = "http://".$_SERVER['SERVER_NAME']."/www/todo/todoapp/emailverify.php?uid=" . $d['id'] . "&vtoken=" . $vtoken;

            $from = "subirdakshi18@gmail.com";
            $to = $email;
            $sub = "Email Verification Link From TODO APP";
            $message = "Hi, $name <br><br> Thank you for register with us. Please click the link below to verify your email address.<br>$link";
            $headers = "From: subirdakshi18@gmail.com" . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            mail($to, $sub, $message, $headers);
        } else {
            array_push($msg, ["status" => 0, "msg" => "Something Went Wrong! Please Try After Sometime!"]);
        }
    } else {
        array_push($msg, ["status" => 0, "msg" => "Email Already Exists!"]);
    }
    
    echo json_encode($msg);
}



//FORGOT PASSWORD EMAIL
if (isset($_POST['forgotPassEmail'])) {
    $msg = [];
    $forgotPassEmail = $_POST['forgotPassEmail'];

    $user = new TodoApp('users');
    $data = $user->getData('', ['email' => $forgotPassEmail]);

    if ($data->num_rows > 0) {
        $row = $data->fetch_assoc();
        $token = md5(time());
        if ($user->updateData(['id' => $row['id']], ['ptoken' => $token, 'password_change' => '0'])) {
            $link = "http://".$_SERVER['SERVER_NAME']."/www/todo/todoapp/resetPassword.php?uid=" . $row['id'] . "&ptoken=" . $token;

            $to = $forgotPassEmail;
            $from = "subirdakshi18@gmail.com";
            $sub = "Password Reset Link From TODO APP";
            $message = "Hi, " . $row['name'] . " <br><br> Please click the link below to reset your password.<br>$link";
            $headers = "From: subirdakshi18@gmail.com" . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $mail = mail($to, $sub, $message, $headers);
            if ($mail) {
                array_push($msg, ["status" => 1, "msg" => "A reset password mail has sent to your email address."]);
            } else {
                array_push($msg, ['status' => 0, 'msg' => "Something Went Wrong! Please Try After Some Time"]);
            }
        } else {
            array_push($msg, ['status' => 0, 'msg' => "Something Went Wrong! Please Try After Some Time"]);
        }
    } else {
        array_push($msg, ['status' => 0, 'msg' => "It seems like email address is not registered with us"]);
    }

    echo json_encode($msg);
}


//RESET PASSWORD
if (isset($_POST['rid']) && isset($_POST['rpassword']) && isset($_POST['rcpassword'])) {
    $msg = [];
    $id = $_POST['rid'];
    $pass = $_POST['rpassword'];
    $pass = password_hash($pass, PASSWORD_BCRYPT);
    $user = new TodoApp('users');
    $data = $user->getData('', ['id' => $id])->fetch_assoc();

    if ($data['password_change'] === '0') {
        if ($user->updateData(['id' => $id], ['password' => $pass, 'password_change' => '1'])) {
            array_push($msg, ['status' => 1, 'msg' => 'Password Changed Successfully']);
        } else {
            array_push($msg, ['status' => 0, 'msg' => 'Something Went Wrong! Please Try After Some Time']);
        }
    }else{
        array_push($msg, ['status' => 0, 'msg' => 'Password Already Changed!']);
    }


    echo json_encode($msg);
}
