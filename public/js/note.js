$(document).ready(function () {
    fetchNote();

    //VIEW NOTE AS A FUNCTION
    function fetchNote() {

        var note = '';
        $.ajax({
            method: 'POST',
            url: `${PATH_NAME_JS}/form_handle/userNote.php`,
            data: {
                fetchNote: 'fetchNote'
            },
            success: function (data) {
                if (data[0]['num_rows'] > 0) {

                    note += `
                    <table class="table table-bordered table-responsive">
                    <thead class="thead-inverse">
                        <tr>
                            <th class="text-center" style="min-width: 10px;width:100px;">#</th>
                            <th class="text-center" style="min-width: 160px;width:1000px">Title</th>
                            <th class="text-center" style="min-width: 130px;width: 200px;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="fetch">
                    `;

                    let i = 1;
                    for (j = 1; j < data.length; j++) {
                        note +=
                            `
                                <tr id="${data[j]['id']}">
                                    <td scope="row" style="min-width: 10px;width:100px;text-align: center;">${i}</td>
                                    <td style="min-width: 160px;width:1000px">${data[j]['title']}<p style="font-size:12px">${new Date(data[j]['updated_at']).toDateString()}</p></td>
                                    <td style="min-width: 130px;text-align: center;width: 200px;">
                                    <a href="#" class="f-30 pr-2 viewNote" data-toggle="modal" data-target="#viewNoteModal" data-id="${data[j]['id']}" data-role="read">
                                                <i class="fa fa-info-circle" aria-hidden="true"></i></a>
            
                                            <a href="#" class="f-30 pr-1 editNote" data-toggle="modal" data-target="#editNoteModal" data-id="${data[j]['id']}" data-role="update">
                                                <i class="fa fa-edit text-success" aria-hidden="true"></i></a>
            
                                            <a href="#" class="f-30 deleteNote" data-toggle="modal" data-target="#deleteNoteModal" data-id="${data[j]['id']}" data-role="delete">
                                                <i class="fa fa-trash text-danger" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            `;

                        i++;
                    }

                    note += `
                        </tbody></table>
                    `;
                } else {
                    // $('div#fetchNote').html("<p class='text-center'><img class='w-50' src='https://icon-library.com/images/spinner-icon-gif/spinner-icon-gif-16.jpg'></p>");
                    note += `
                    <img src="https://853868.smushcdn.com/1835781/wp-content/uploads/2013/04/143.-EMPTY-TO-DO-LIST-2.jpg?lossy=1&strip=1&webp=1" alt="" width="100%">
                    `;
                }
                $('div#fetchNote').html(note);
            }
        });
    }





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

    //ADD NEW NOTE
    $('#addNewNote').click(function () {

        let newNoteTitle = $('#newNoteTitle').val().trim();
        // let newNoteContent = CKEDITOR.instances.newNoteContent.getData();
        let newNoteContent = tinymce.get("newNoteContent").getContent();

        if (newNoteTitle === '' || newNoteContent === '') {
            showMsg(0, 'All Fields Are Mandatory');
        } else {
            $('#addNewNote').html(`<img src="${PATH_NAME_JS}/public/img/loader.svg" width="20px">`);
            $('#addNewNote').attr('disabled', 'disabled');
            $.ajax({
                method: 'POST',
                url: `${PATH_NAME_JS}/form_handle/userNote.php`,
                data: {
                    newNoteTitle: newNoteTitle,
                    newNoteContent: newNoteContent
                },
                success: function (data) {
                    $('#addNewNote').html(`Add Note`);
                    $('#addNewNote').removeAttr('disabled');
                    if (data[0]['status'] === 1) {
                        showMsg(1, data[0]['msg']);
                        $('#newNoteTitle').val("");
                        // CKEDITOR.instances.newNoteContent.setData("");
                        tinymce.get('newNoteContent').setContent("");
                        fetchNote();
                    } else {
                        showMsg(0, data[0]['msg']);
                    }
                }
            });
        }

    });



    //EDIT NOTE FOR RETRIVE DATA
    $(document).on('click', 'a[data-role="update"]', function () {
        let id = $(this).data('id');
        $.ajax({
            method: 'POST',
            url: `${PATH_NAME_JS}/form_handle/userNote.php`,
            data: {
                editNoteIdRetrive: id
            },
            success: function (data) {
                $('#editNoteId').val(data['id']);
                $('#editNoteTitle').val(data['title'].replace('\n', ' '));
                // CKEDITOR.instances.editNoteContent.setData(data['note']);
                tinymce.get('editNoteContent').setContent(data['note']);
            }
        });
    });



    //EDIT NOTE FOR UPDATE DATA
    $('#editNoteButton').click(function () {
        let editNoteId = $('#editNoteId').val();
        let editNoteTitle = $('#editNoteTitle').val().trim();
        let editNoteContent = tinymce.get('editNoteContent').getContent();

        if (editNoteTitle === '' || editNoteContent === '') {
            showMsg(0, 'All Fields Are Mandatory');
        } else {
            $('#editNoteButton').html(`<img src="${PATH_NAME_JS}/public/img/loader.svg" width="20px">`);
            $('#editNoteButton').attr('disabled', 'disabled');

            $.ajax({
                method: 'POST',
                url: `${PATH_NAME_JS}/form_handle/userNote.php`,
                data: {
                    editNoteId: editNoteId,
                    editNoteTitle: editNoteTitle,
                    editNoteContent: editNoteContent
                },
                success: function (data) {
                    $('#editNoteButton').html(`Update Note`);
                    $('#editNoteButton').removeAttr('disabled');

                    if (data[0]['status'] === 1) {
                        showMsg(1, data[0]['msg']);
                        fetchNote();
                    } else {
                        showMsg(0, data[0]['msg']);
                    }
                }
            });
        }
    });


    //VIEW NOTE
    $(document).on('click', 'a[data-role="read"]', function () {
        let id = $(this).data('id');
        $('#veiwSingNote').html(`<div class="text-center"><img src="${PATH_NAME_JS}/public/img/loaderblue.svg" width="100px"></div>`);
        $.ajax({
            method: 'POST',
            url: `${PATH_NAME_JS}/form_handle/userNote.php`,
            data: {
                viewNoteid: id,
            },
            success: function (data) {
                let veiwSingNote = '';
                veiwSingNote += `
                        <div class="container">
                            <h2 class="text-center bg-light p-3 mb-4">${data[0]['title']}</h2>
                        </div>
                        <div class="container">${data[0]['note']}</div>
                        <div class="container" style="overflow:auto"> 
                            <p class="mt-5 text-right" style="color:#ffb420;font-size:16px;">Last Modified : ${new Date(data[0]['updated_at']).toLocaleString()}</p>
                            <p class="text-right" style="color:#ffb420;font-size:16px;:">Created : ${new Date(data[0]['created_at']).toLocaleDateString()}</p>
                        </div>

                    `;
                $('#veiwSingNote').html(veiwSingNote);
            }
        });
    });


    //DELETE NOTE
    $(document).on('click', 'a[data-role="delete"]', function () {
        let delNoteid = $(this).data('id');
        $('#deleteNoteId').val(delNoteid);
    });

    $('#cnfDelete').click(() => {
        let deleteNoteid = $('#deleteNoteId').val();
        $('#cnfDelete').attr('disabled', 'disabled');
        $.ajax({
            method: 'POST',
            url: `${PATH_NAME_JS}/form_handle/userNote.php`,
            data: {
                deleteNoteid: deleteNoteid,
            },
            success: function (data) {
                $('#cnfDelete').removeAttr('disabled');
                if (data[0]['status'] === 1) {
                    $('tr#' + deleteNoteid).fadeOut(600);
                    showMsg(1, data[0]['msg']);
                    $('#deleteNoteModal').modal('hide');
                    setTimeout(() => {
                        fetchNote();
                    }, 600);
                } else {
                    showMsg(0, data[0]['msg']);
                }
            }
        });
    });

});