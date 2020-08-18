<?php

header('content-type:application/json');
include_once('../controller/TodoApp.ctrl.php');
session_start();
if (empty($_SESSION)) {
    header('location:'.constant("PATH_NAME"));
}

//VIEW NOTE AS A FUNCTION
if(isset($_POST['fetchNote']) && $_POST['fetchNote']==='fetchNote'){
    $note = new TodoApp('todos');
    $data = $note->getData('',['user_id'=>$_SESSION['id']]);
    $msg = [];
    array_push($msg,['num_rows'=>$data->num_rows]);
    while($row = $data->fetch_assoc()){
        array_push($msg,$row);
    }
    echo json_encode($msg);
}



//VIEW A SINGLE NOTE
if(isset($_POST['viewNoteid'])){
    $note = new TodoApp('todos');
    $data = $note->getData('',['id'=>$_POST['viewNoteid'],'user_id'=>$_SESSION['id']],'id','ASC',1);
    $msg = [];
    while($row = $data->fetch_assoc()){
        array_push($msg,$row);
    }
    echo json_encode($msg);
}



//ADD NEW NOTE TO THE DATABASE
if (isset($_POST['newNoteTitle']) && isset($_POST['newNoteContent'])) {
    $newNoteTitle = $_POST['newNoteTitle'];
    $newNoteContent = $_POST['newNoteContent'];
    $msg = [];

    $todoInsert = new TodoApp('todos');
    $insert = $todoInsert->insertData([
        'user_id' => $_SESSION['id'],
        'title'   => $newNoteTitle,
        'note'    => $newNoteContent
    ]);

    if ($insert) {
        array_push($msg, ["status" => 1, "msg" => "New Note Added Successfully"]);
    } else {
        array_push($msg, ["status" => 0, "msg" => "Something Went Wrong! Please Try After Some Time "]);
    }

    echo json_encode($msg);
}


//EDIT NOTE FOR RETRIVE DATA
if (isset($_POST['editNoteIdRetrive'])) {
    $todo = new TodoApp('todos');
    $data = $todo->getData('', ['id' => $_POST['editNoteIdRetrive']], '', 'ASC', 1);
    echo json_encode($data->fetch_assoc());
}


//EDIT NOTE FOR UPDATE DATA
if (isset($_POST['editNoteId']) && $_POST['editNoteTitle'] && isset($_POST['editNoteContent'])) {
    $msg = [];
    $todoEditNote = new TodoApp('todos');
    $status = $todoEditNote->updateData(
        ['id' => $_POST['editNoteId']],
        ['title' => $_POST['editNoteTitle'], 'note' => $_POST['editNoteContent'], 'updated_at' => date('Y-m-d H:i:s')]
    );

    if ($status) {
        array_push($msg, ["status" => 1, "msg" => "Note Updated Successfully"]);
    } else {
        array_push($msg, ["status" => 0, "msg" => "Something Went Wrong! Please Try After Some Time "]);
    }
    echo json_encode($msg);
}



//DELETE NOTE
if (isset($_POST['deleteNoteid']) && !empty($_POST['deleteNoteid'])) {
    $deleteNote = new TodoApp('todos');
    $msg = [];
    if ($deleteNote->deleteData('id',$_POST['deleteNoteid'])) {
        array_push($msg, ["status" => 1, "msg" => "Note Deleted Successfully"]);
    } else {
        array_push($msg, ["status" => 0, "msg" => "Something Went Wrong! Please Try After Some Time "]);
    }
    echo json_encode($msg);
}
