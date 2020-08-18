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



    //FOR EDIR OR UPDATE PROFILE
    $('#updateProfile').click(() => {
        $('#updateProfile').html(`<img src="${PATH_NAME_JS}/public/img/loader.svg" width="20px">`);
        const editName = document.querySelector('#editName');
        const editEmail = document.querySelector('#editEmail');
        var res = true;


        //IF BOTH NAME AND EMAIL IS EMPTY SHOW THE ERROR
        if (editName.value.trim() === '' && editEmail.value.trim() === '') {
            showMsg(0, 'Name And Email Fields Are Mandatory!');
            editName.style.border = "1px solid red";
            editEmail.style.border = "1px solid red";
            res = false;
        } else {
            //FOR NAME FIELD IS REQUIRED
            if (editName.value.trim() === '') {
                showMsg(0, 'Name Field Can Not Be Empty!');
                editName.style.border = "1px solid red";
                res = false;
            } else {
                editName.style.border = "1px solid #ced4da";
            }

            //FOR EMAIL FIELD IS ALSO REQUIRED AND ALSO VALID
            if (editEmail.value.trim() === '') {
                showMsg(0, 'Email Field Can Not Be Empty!');
                editEmail.style.border = "1px solid red";
                res = false;
            } else if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(editEmail.value)) {
                res = false;
                showMsg(0, 'Looks Like This Is Not An Email!');
                editEmail.style.border = "1px solid red";
            } else {
                editEmail.style.border = "1px solid #ced4da";
            }
        }

        if (res === true) {
            $('#updateProfile').attr('disabled','disabled');
            fetch(`${PATH_NAME_JS}/form_handle/userForm.php`, {
                method: 'POST',
                body: new FormData(document.getElementById('updateProfileForm'))
            }).then(response => response.json()).then(data => {
                $('#updateProfile').html('Update Profile');
                $('#updateProfile').removeAttr('disabled');
                if (data[0]['status'] === 1) {
                    showMsg(1, data[0]['msg']);
                    setTimeout(() => {
                        window.location.reload()
                    }, 1500);
                } else {
                    showMsg(0, data[0]['msg']);
                }
            });


        }
    });


    //FOR REMOVE PROFILE PICTURE
    $('#removeProfileImg').click(() => {
        $('#removeProfileImg').html(`<img src="${PATH_NAME_JS}/public/img/loader.svg" width="20px">`);
        $('#removeProfileImg').attr('disabled','disabled');
        $.ajax({
            method: "POST",
            url: `${PATH_NAME_JS}/form_handle/userForm.php`,
            data: {
                removeProfileImg: 'removeProfileImg'
            },
            success: function (data) {  
                $('#removeProfileImg').removeAttr('disabled');
                $('#removeProfileImg').html('Remove Photo');
                if (data[0]['status'] === 1) {
                    showMsg(1, data[0]['msg']);
                    setTimeout(() => {
                        window.location.reload()
                    }, 1500);
                } else {
                    showMsg(0, data[0]['msg']);
                }
            }
        });

    });



    /****USER PROFILE PASSWORD AJAX REQUEST****/

    $('#changePass').click(function () {
        let changePassForm = document.forms.changePassForm;

        let oldPassword = changePassForm.oldPassword;
        let editPassword = changePassForm.editPassword;
        let editCnfPassword = changePassForm.editCnfPassword;

        let res = true;

        if (oldPassword.value.trim() === '' || editPassword.value.trim() === '' || editCnfPassword.value.trim() === '') {
            showMsg(0, 'All Fields Are Mandatory');
            res = false;
        }

        if (oldPassword.value.trim() === '') {
            oldPassword.style.border = "1px solid red";
            res = false;
        } else {
            oldPassword.style.border = "1px solid #ced4da";
        }

        if (editPassword.value.trim() === '') {
            editPassword.style.border = "1px solid red";
            res = false;
        } else {
            editPassword.style.border = "1px solid #ced4da";
        }

        if (editCnfPassword.value.trim() === '') {
            editCnfPassword.style.border = "1px solid red";
            res = false;
        } else if (editPassword.value.trim() != editCnfPassword.value.trim()) {
            showMsg(0, 'Confirm Password Does Not Matched');
            editCnfPassword.style.border = "1px solid red";
            res = false;
        } else {
            editCnfPassword.style.border = "1px solid #ced4da";
        }

        if (res === true) {
            $("#changePass").html(`<img src="${PATH_NAME_JS}/public/img/loader.svg" width="20px">`);
            $('#changePass').attr('disabled','disabled');
            $.ajax({
                method: "POST",
                url: `${PATH_NAME_JS}/form_handle/userForm.php`,
                data: {
                    oldPassword: oldPassword.value,
                    editPassword: editPassword.value,
                    editCnfPassword: editCnfPassword.value
                },
                success: function (data) {
                    $("#changePass").html('Change Password');
                    $('#changePass').removeAttr('disabled');
                    if (data[0]['status'] === 1) {
                        showMsg(1, data[0]['msg']);
                        changePassForm.reset();
                    } else {
                        showMsg(0, data[0]['msg']);
                    }
                }

            });
        }
    });



    //FOR VERIFY EMAIL
    $('.verifyEmailBtn').click(() => {
        $('.verifyEmailBtn').attr('disabled', 'disabled');
        $('.verifyEmailBtn').append(`<img class="ml-2" style="margin-top:-3px" src="${PATH_NAME_JS}/public/img/loader.svg" width="20px">`);
        $.ajax({
            method: "POST",
            url: `${PATH_NAME_JS}/form_handle/userForm.php`,
            data: {
                verifyEmail: 'verifyEmail'
            },
            success: function (data) {
                $('.verifyEmailBtn').removeAttr('disabled');
                $('.verifyEmailBtn').html("Verify Now");
                if (data[0]['status'] === 0) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: data[0]['msg'],
                        showConfirmButton: false,
                        timer: 5000
                    });
                }

                if (data[0]['status'] === 1) {

                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: data[0]['msg'],
                        showConfirmButton: false,
                        timer: 5000
                    });
                }
            }
        });
    });


});