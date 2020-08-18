$(document).ready(function () {

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

    //SHOW BORDER
    function showBorder(status, element) {
        if (status === 0) {
            element.css('border', '1px solid red');
        } else {
            element.css('border', '1px solid #ced4da');
        }
    }

    //FOR SIGNIN USER
    const signInForm = $('#signInForm');
    $('#signin').click(() => {

        const email = $('#email');
        const password = $('#password');

        let res = true;
        if (email.val().trim() === '' && password.val().trim() === '') {
            email.css('border', '1px solid red');
            password.css('border', '1px solid red');
            showMsg(0, 'All fields are mandatory');
            res = false;
        } else {
            if (email.val().trim() === '') {
                email.css('border', '1px solid red');
                showMsg(0, 'Email can not be empty');
                res = false;
            } else if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email.val())) {
                res = false;
                showMsg(0, 'Looks Like This Is Not An Email!');
                email.style.border = "1px solid red";
            } else {
                email.css('border', '1px solid #ced4da');
            }

            if (password.val().trim() === '') {
                password.css('border', '1px solid red');
                showMsg(0, 'Password can not be empty');
                res = false;
            } else {
                password.css('border', '1px solid #ced4da');
            }

        }

        if (res === true) {
            $('#signin').attr('disabled', 'disabled');
            fetch(`${PATH_NAME_JS}/form_handle/userSignReg.php`, {
                method: 'POST',
                body: new FormData(document.getElementById('signInForm'))
            }).then(response => response.json()).then(data => {
                $('#signin').removeAttr('disabled');
                if (data[0]['status'] === 1) {
                    $('#signInForm').trigger('reset'); 
                    window.location.reload();
                } else {
                    showMsg(0, data[0]['msg']);
                }
            });
        }

    });



    //FOR REGISTRATION USER
    $("#registerBtn").click(function () {
        let res = true;
        const name = $('#regName');
        const email = $('#regEmail');
        const password = $('#regPassword');
        const cnfpassword = $('#regCnfPassword');

        if (name.val().trim() === '' && email.val().trim() === '' && password.val().trim() === '' && cnfpassword.val().trim() === '') {
            res = false;
            showMsg(0, 'All fields are mandatory');
            showBorder(0, name);
            showBorder(0, email);
            showBorder(0, password);
            showBorder(0, cnfpassword);
        } else {
            if (name.val().trim() === '') {
                res = false;
                showMsg(0, 'Name can not be empty');
                showBorder(0, name);
            } else {
                showBorder(1, name);
            }

            //FOR EMAIL FIELD IS ALSO REQUIRED AND ALSO VALID
            if (email.val().trim() === '') {
                res = false;
                showMsg(0, 'Email can not be empty');
                showBorder(0, email);
            } else if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email.val())) {
                res = false;
                showMsg(0, 'Looks like this is not an email');
                showBorder(0, email);
            } else {
                showBorder(1, email);
            }

            //password Field
            if (password.val().trim() === '') {
                res = false;
                showMsg(0, 'Password can not be empty');
                showBorder(0, password);
            } else {
                showBorder(1, password);
            }

            //confirm password Field
            if (cnfpassword.val().trim() === '') {
                res = false;
                showMsg(0, 'Confirm password can not be empty');
                showBorder(0, cnfpassword);
            } else if (password.val().trim() !== cnfpassword.val().trim()) {
                res = false;
                showMsg(0, 'Confirm password does not match');
                showBorder(0, cnfpassword);
            } else {
                showBorder(1, cnfpassword);
            }


        }

        if (res === true) {
            $('#registerBtn').html(`<img src="${PATH_NAME_JS}/public/img/loader.svg" width="20px">`);
            $('#registerBtn').attr('disabled', 'disabled');
            fetch(`${PATH_NAME_JS}/form_handle/userSignReg.php`, {
                method: 'POST',
                body: new FormData(document.getElementById('registrationForm'))
            }).then(response => response.json()).then(data => {
                if (data[0]['status'] === 1) {
                    Swal.fire({
                        title: 'Success!',
                        text: data[0]['msg'],
                        icon: 'success',
                        confirmButtonText: 'ok'
                    });
                    $('#registrationForm').trigger('reset');
                    $('#registerBtn').removeAttr('disabled');
                    $('#registerBtn').html('Request Password Reset');
                } else {
                    showMsg(0, data[0]['msg']);
                    $('#registerBtn').removeAttr('disabled');
                    $('#registerBtn').html('Request Password Reset');
                }
            });
        }

    });



    //FOR PASSWORD RESET

    $('#resetPassBtn').click(() => {
        const forgotPassEmail = $('#forgotPassEmail');
        let res = true;
        if (forgotPassEmail.val().trim() === '') {
            forgotPassEmail.css('border', '1px solid red');
            showMsg(0, 'Email can not be empty');
            res = false;
        } else if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(forgotPassEmail.val())) {
            res = false;
            showMsg(0, 'Looks Like This Is Not An Email!');
            forgotPassEmail.style.border = "1px solid red";
        } else {
            forgotPassEmail.css('border', '1px solid #ced4da');
        }


        if (res === true) {
            $('#resetPassBtn').html(`<img src="${PATH_NAME_JS}/public/img/loader.svg" width="20px">`);
            $('#resetPassBtn').attr('disabled', 'disabled');
            fetch(`${PATH_NAME_JS}/form_handle/userSignReg.php`, {
                method: 'POST',
                body: new FormData(document.getElementById('forgotPassForm'))
            }).then(response => response.json()).then(data => {
                if (data[0]['status'] === 1) {
                    Swal.fire({
                        title: 'Success!',
                        text: data[0]['msg'],
                        icon: 'success',
                        confirmButtonText: 'ok'
                    });
                    $('#resetPassBtn').removeAttr('disabled');
                    $('#resetPassBtn').html('Request Password Reset');
                } else {
                    showMsg(0, data[0]['msg']);
                    $('#resetPassBtn').removeAttr('disabled');
                    $('#resetPassBtn').html('Request Password Reset');
                }
            });
        }
    });

});