$(document).ready(function() {
    let path = window.location.pathname.split("/").pop();
    if(path === ""){
       path = "/";
    }
    // alert(path);
    $('nav a[href="'+path+'"]').parent().addClass("active");
    $('.dropdown-menu a[href="'+path+'"]').addClass("active");
});