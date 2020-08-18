<!-------------Header---------------->
<?php 
include('./header.php');
if(isset($_POST['email_verified_close_btn'])){
    $_SESSION['email_verified'] = $_POST['email_verified'];
}
if (empty($_SESSION)) {
?>
    <div class="container">
        <img class="w-100" src="https://dr0wv9n0kx6h5.cloudfront.net/7d10cc66fd0a02e2886eab8956b251b29422bdfe/content/03-blog/175-the-new-microsoft-to-do-is-here/01@2x.png" alt="">
    </div>
    <div class="main-section">
        <div class="mb-5 container main-content d-flex justify-content-center flex-column align-items-center">
            <h1>The new To Do app is here</h1>
            <p class="mt-4">
                It’s been an exciting journey since we announced our new app,To Do. We couldn’t have made this app what it is today without your support and the feedback you gave us. For that, we want to say a massive thank you! Since our announcement, we’ve been working hard on the evolution of Wunderlist that you told us you’d like to see. We looked at the best bits of Wunderlist and To Do to figure out how we could incorporate these into one great app.
                <br><br>Today, we’re excited to unveil what we’ve been building for you at the Wunderlist/To Do offices here in Berlin. It’s time to take a first look at the new version of To Do.
            </p>
            <br>
            <a href="register.php" class="btn btn-lg btn-red">Get Started</a>
        </div>
    </div>
<?php } else {
    $user = new TodoApp('users');
    $data = $user->getData('', ['id' => $_SESSION['id']], '', 'ASC', 1);
    $row = $data->fetch_assoc();

?>
    <!------------------START CODE IF SESSION HAS VALUES----------------->
    <div class="container">
        <?php
        if ($_SESSION['email_verified'] != 1 && $row['email_verified'] != 1) {
            echo '<div class="mt-3 alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Email Address</strong> is not verified!
                    <button class="btn text-white verifyEmailBtn" style="background:#ffb420">Verify Now</button>
                    <button type="button" class="close" id="email_verified_close_btn" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        }
        ?>
        <!-- <h2 class="mt-4 text-center">
            Write Your Notes Here and Access Anytime Anywhere!
        </h2> -->
    </div>

    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center" style="background: #ffb420;">
            <div class="p-3">
                <p class="text-white">All Notes</p>
            </div>
            <div class="p-3">
                <a href="#" class="btn btn-red" data-toggle="modal" data-target="#newNoteModal">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Add New Note
                </a>
                <a href="#" class="btn btn-red hide-on-md" data-toggle="modal" data-target="#newNoteModal">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> 
                </a>
            </div>
        </div>
    </div>

    <!----------FOR SHOWING NOTE DETAILS IN A TABLE THROUGH AJAX---------------->
    <div class="container" id="fetchNote"></div>



    <!-- Modal For New NOTE-->
    <div class="modal fade" id="newNoteModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-white" style="background: #7668fa;">
                    <h5 class="modal-title">Add New Note</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="container">
                            <form>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" name="newNoteTitle" id="newNoteTitle" placeholder="Enter Title">
                                </div>
                                <div class="form-group">
                                    <textarea name="newNoteContent" id="newNoteContent"></textarea>
                                </div>
                            </form>
                            <button type="button" class="btn btn-primary w-100" id="addNewNote">Add Note</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal For view NOTE-->
    <div class="modal fade" id="viewNoteModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-white" style="background: #7668fa;">
                    <h5 class="modal-title">View Note</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container" id="veiwSingNote">
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal For edit NOTE-->
    <div class="modal fade" id="editNoteModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-white" style="background: #ffb420;">
                    <h5 class="modal-title">Edit Note</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="container">
                            <form>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" name="editNoteTitle" id="editNoteTitle" placeholder="Enter Title">
                                </div>
                                <div class="form-group">
                                    <textarea style="font-size:20px" class="form-control" name="editNoteContent" id="editNoteContent" rows="5" placeholder="Write Your Note"></textarea>
                                </div>
                                <input type="hidden" id="editNoteId">
                                <button type="button" class="btn w-100 text-white" style="background: #ffb420;" id="editNoteButton">Update Note</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal For delete NOTE-->
    <div class="modal fade" id="deleteNoteModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-white" style="background: #ca0000;">
                    <h5 class="modal-title text-center">Are You Sure To Delete?</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="container text-center">
                            <input type="hidden" id="deleteNoteId">
                            <button class="btn text-white" style="background: #ffb420;" id="cnfDelete"><i class="fa fa-check fa-2x" aria-hidden="true"></i></button>
                            <a href="#" class="btn text-white" style="background: #ca0000;" data-dismiss="modal" aria-label="Close"><i class="fa fa-close fa-2x pl-1 pr-1" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<!-------------Footer---------------->
<?php include('./footer.php'); ?>
<script defer>
    // CKEDITOR.replace('newNoteContent');
    // CKEDITOR.replace('editNoteContent');
    tinymce.init({selector:'textarea'});
    tinymce.init({
  plugins: "table",
  table_default_attributes: {
    'border': '1'
  },
  table_default_styles: {
    'border-collapsed': 'collapse',
    'width': '100%'
  },
  table_responsive_width: true
});

$(document).on('click','#email_verified_close_btn',()=>{
    $.ajax({
        url:'index.php',
        method:'POST',
        data:{email_verified:1,email_verified_close_btn:"email_verified_close_btn"}
    })
});
</script>