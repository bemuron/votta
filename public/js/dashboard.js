//csrf token
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

var close = document.getElementsByClassName("closebtn");
var i;
var mIsAlertOn;

for (i = 0; i < close.length; i++) {
  close[i].onclick = function(){
    var div = this.parentElement;
    div.style.opacity = "0";
    setTimeout(function(){ div.style.display = "none"; }, 600);
  };
}

//show the active side menu item
switch(window.location.pathname){
    case "/dashboard":
        document.getElementsByClassName("nav-item")[0].className += " active";
    break;
    case "/manage-elections":
        document.getElementsByClassName("nav-item")[1].className += " active";
    break;
    case "/manage-positions":
        document.getElementsByClassName("nav-item")[2].className += " active";
    break;
    case "/manage-candidates":
        document.getElementsByClassName("nav-item")[3].className += " active";
    break;
    case "/manage-voter-base":
        document.getElementsByClassName("nav-item")[4].className += " active";
    break;
    case "/manage-users":
        document.getElementsByClassName("nav-item")[5].className += " active";
    break;
    case "/manage-divisions":
        document.getElementsByClassName("nav-item")[6].className += " active";
    break;
    case "/manage-sub-divisions":
        document.getElementsByClassName("nav-item")[7].className += " active";
    break;
    case "/dash-election-results":
        document.getElementsByClassName("nav-item")[8].className += " active";
    break;
}

//show modal to create a new user
$('#createUserBtn').on('click', function (e) {
    document.getElementById("usersForm").reset();
    document.getElementById("userId").value = 0;
    $("#user_division_dropdown").val('0').trigger("change");
    $('#users_modal').modal("show");
    $("#user_password_section").fadeIn("slow");
    $('#saveUserBtn').html("<i class='bi bi-plus-circle'></i> Create User");
});

//show modal to import users
$('#userBulkInsertBtn').on('click', function (e) {
    $('#user_import_modal').modal("show");
});

//download bulk insert template file
function downloadUserUploadTemplate(){
    //console.log("called to donwload");
    $.ajax({
        url: "/user-upload-template",
        type: 'get',
        beforeSend: function () { // show loading spinner
            $('#loader').removeClass('hidden');
        },
        success: function(data) {

            if(data.error){
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert');
                $('#alert-msg').html("<i class='fas fa-x'></i> "+data.error);
                $('#custom-alert').show();
            }

            if(data.length > 0 || data !== null){
                window.location = "/user-upload-template";
            }

        },
        complete: function () { // hiding the spinner.
            $('#loader').addClass('hidden');
        }
    });

     //hide the alert
     setTimeout(function() {
        if(mIsAlertOn === true){
            $('#custom-alert').fadeOut('fast');
            $('#custom-alert').removeClass('custom-alert info');
            $('#custom-alert').removeClass('custom-alert warning');
            $('#custom-alert').removeClass('custom-alert success');
            $('#custom-alert').removeClass('custom-alert');
        }
        mIsAlertOn = false;
    }, 5000); // <-- time in milliseconds
}

//import the users
$('#importUsersBtn').on('click', function (e) {
    e.preventDefault();
    $("#userImportFormValErr").addClass('d-none');
    
    // Get form
    var form = $("#userImportForm")[0];

    // Create an FormData object
    var data = new FormData(form);
    
    // disabled the submit button
    $("#importUsersBtn").prop("disabled", true);
    
    $.ajax({
        url: "/users/import",
        type: 'post',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () { // show loading spinner
            $('#loader').removeClass('hidden');
        },
        //dataType: 'json',
        data: data,
        success: function(data) {
            //console.log(data);
            $("#importUsersBtn").prop("disabled", false);
            document.getElementById("users_file").value="";
            
            if(data.success){
                getUsers();
                $("#user_import_modal").modal("hide");
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert success');
                $('#alert-msg').html("<i class='fas fa-check-circle'></i> "+data.success);
                $('#custom-alert').show();
            }

            if(data.error){
                let responseHtml = '';
                responseHtml += '<p> * ' + data.error[0].errors[0] + '</p>';
                //console.log(data.error[0].errors[0]);
                

                $("#userImportFormValErr").removeClass('d-none');
                $("#userImportFormValErr").html( responseHtml );
                document.querySelector('#userImportFormValErr').scrollIntoView({
                    behavior: 'smooth'
                });

                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert');
                $('#alert-msg').html("<i class='fas fa-times-circle'></i> Issue with file: "+data.error[0].errors[0]);
                $('#custom-alert').show();
            }
            
        },
        error: function (e) {
            let responseHtml = '';
            var errors = e.responseJSON.errors;
            
            responseHtml += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> </div>';
            $.each( errors , function( key, value ) {
                responseHtml += '<p> * ' + value + '</p>';
            });
            
            
            $("#userImportFormValErr").html( responseHtml );
            document.querySelector('#userImportFormValErr').scrollIntoView({
                behavior: 'smooth'
            });
            
          $("#importUsersBtn").prop("disabled", false);
        },
        complete: function () { // hiding the spinner.
            $('#loader').addClass('hidden');
        }
    });
    
    //hide the alert
setTimeout(function() {
    if(mIsAlertOn === true){
        $('#custom-alert').fadeOut('fast');
        $('#custom-alert').removeClass('custom-alert info');
        $('#custom-alert').removeClass('custom-alert warning');
        $('#custom-alert').removeClass('custom-alert success');
        $('#custom-alert').removeClass('custom-alert');
    }
}, 5000); 
    
});

//get the system users
function getUsers(){
    //display the users in the system
    if ($.fn.DataTable.isDataTable('#users_table')) {
        $('#users_table').DataTable().destroy();
    }
    $('#users_table tbody').empty();
    $('#users_table').DataTable({
        //responsive: true,
        processing: true,
        //serverSide: true,
        order: [],
        ajax: {
            url: "/users/table-list",
            dataSrc: function (json) {
                //console.log(json.length);
              var return_data = new Array();
              for(var i=0;i< json.length; i++){
                return_data.push({
                  action: displayUsersActionButtons(json[i].id, json[i].status),
                  name: json[i].name,
                  email: json[i].email,
                  status: json[i].user_status,
                  created_at: json[i].created_at,
                  division: json[i].division_name,
                  sub_division: json[i].sub_division_name,
                  user_role: json[i].user_role,
                });
                
              }
              return return_data;
            }
        },
        columns: [
        {data: 'name'},
        {data: 'email'},
        {data: 'division'},
        {data: 'sub_division'},
        {data: 'created_at'},
        {data: 'user_role'},
        {data: 'status'},
        {data: 'action', orderable: false, searchable: false}
        ]
    });
}

function displayUsersActionButtons(userId, status){

    var actions =  "<div class='btn-toolbar'> <div> "+
    "<button class='btn btn-xs "+(status == 1 ? 'btn-success' : 'btn-light')+"  btn-icon' data-toggle='tooltip' data-placement='bottom' onclick='onToggleUserStatusClick("+status+ ','+userId+")' title='"+(status == 1 ? 'Deactivate user' : 'Activate user' )+"'  id='toggle-user-status' data-id='"+userId+"'> <i class='bi bi-person-dash'></i></button> "+ 
    "<a href='#' class='btn btn-xs btn-outline-dark btn-icon' data-toggle='tooltip' data-placement='bottom' title='Edit' onclick='getUserDetails("+userId+")' id='edit-user' data-id='"+userId+"'> <i class='bi bi-pencil-square'></i> </a> "+
    "<a href='#' class='btn btn-xs btn-outline-danger btn-icon' data-toggle='tooltip' data-placement='bottom' title='Delete' onclick='confirmUserDelete("+userId+")' id='delete-user' data-id='"+userId+"'> <i class='bi bi-trash'></i> </a>"+
    "</div></div>";

    return actions;
}

//handle clicks on the user status button
function onToggleUserStatusClick(currentStatus, userId){
    $.ajax({
        url: "/change-user-status",
        type: 'post',
        beforeSend: function () { // show loading spinner
            $('#loader').removeClass('hidden');
        },
        //dataType: 'json',
        data: {
            _token: CSRF_TOKEN,
            current_Status: currentStatus,
            user_id: userId
        },
        success: function(data) {
            //console.log(data);
            if(data.success){
                getUsers();

                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert success');
                $('#alert-msg').html("<i class='fas fa-check-circle'></i> "+data.success);
                $('#custom-alert').show();
                
            }
            
            if(data.error){
                mIsAlertOn = true;
                $('#custom-alert').addClass('custom-alert');
                $('#alert-msg').html("<i class='fas fa-times-circle'></i> "+data.error);
                $('#custom-alert').show();
            }
            
        },
        complete: function () { // hiding the spinner.
            $('#loader').addClass('hidden');
        }
    });
    
    //hide the alert
    setTimeout(function() {
        if(mIsAlertOn === true){
            $('#custom-alert').fadeOut('fast');
            $('#custom-alert').removeClass('custom-alert info');
            $('#custom-alert').removeClass('custom-alert warning');
            $('#custom-alert').removeClass('custom-alert success');
            $('#custom-alert').removeClass('custom-alert');
        }
        mIsAlertOn = false;
    }, 5000); // <-- time in milliseconds
}

//get the user details for the user to edit
function getUserDetails(userId){
    var userName = document.getElementById("user_name");
    var emailName = document.getElementById("user_email");
    $("#usersFormValErr").addClass('d-none');

    $.get("/edit-user-details/"+userId, function(data) {
        if(data !== null){
            document.getElementById("usersForm").reset();
            $("#user_password_section").fadeOut("slow");

            document.getElementById("userId").value = data.id;

            userName.value = data.name;
            emailName.value = data.email;
            $("#user_role").val(data.user_role).trigger("change");
            $("#user_status").val(data.status).trigger("change");
            $('#user_sub_division_dropdown').append($("<option />").val(data.sub_division).text(data.sub_division_name));
            $('#user_division_dropdown').append($("<option />").val(data.division_id).text(data.division_name));

            $('#saveUserBtn').html("<i class='bi bi-save-fill'></i> Save");
            $('#users_modal').modal("show");
            
        }
    });
}

//handle create / edit user form submition
$('#saveUserBtn').on('click', function (e) {
    e.preventDefault();
    var status = $("#user_status").val();
    var role = $("#user_role").val();
    var sub_division = $("#user_sub_division_dropdown").val();

    // Get form
    var form = $("#usersForm")[0];

    // Create an FormData object
    var data = new FormData(form);
    
    var userId = $("#userId").val();

    data.append("user_status", status);
    data.append("user_role", role);
    data.append("sub_division", sub_division);
    
    var route;
    if(userId > 0){
        route = "/save-edit-user-details/"+userId;
        data.append("userId", userId);
    }else{
        route = "/create-user";
    }
    
    // disable the submit button
    $("#saveUserBtn").prop("disabled", true);
    
    $.ajax({
        url: route,
        type: 'post',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () { // show loading spinner
            $('#loader').removeClass('hidden');
        },
        //dataType: 'json',
        data: data,
        success: function(data) {
            //console.log(data);
            $("#saveUserBtn").prop("disabled", false);
            if(data.success){
                mIsAlertOn = true;
                $('#custom-alert').addClass('custom-alert success');
                $('#alert-msg').html("<i class='fas fa-check-circle'></i> "+data.success);
                $('#custom-alert').show();

                getUsers();
                document.getElementById("usersForm").reset();
                document.getElementById("userId").value = 0;
                $("#users_modal").modal("hide");
            }

            if(data.error){
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert');
                $('#alert-msg').html("<i class='fas fa-x'></i> "+data.error);
                $('#custom-alert').show();
            }
        },
        error: function (e) {
            let responseHtml = '';
            var errors = e.responseJSON.errors;
            //var errors = data.responseJSON;
            
            $.each( errors , function( key, value ) {
                responseHtml = '<p> * ' + value + '</p>';
            });

            
            //console.log(errors);
            $.each(errors, function (key, value) {
                $('#usersFormValErr').append('<p> * ' + value + '</p>');
                $("#usersFormValErr").removeClass('d-none');
                document.getElementById("usersFormValErr").scrollIntoView();
            });

            mIsAlertOn = true;

            $('#custom-alert').addClass('custom-alert');
            $('#alert-msg').html("<i class='fas fa-x'></i> "+responseHtml);
            $('#custom-alert').show();

          $("#saveUserBtn").prop("disabled", false);
        },
        complete: function () { // hiding the spinner.
            $('#loader').addClass('hidden');
        }
    });
    
    //hide the alert
    setTimeout(function() {
        if(mIsAlertOn === true){
            $('#custom-alert').fadeOut('fast');
            $('#custom-alert').removeClass('custom-alert info');
            $('#custom-alert').removeClass('custom-alert warning');
            $('#custom-alert').removeClass('custom-alert success');
            $('#custom-alert').removeClass('custom-alert');
        }
        mIsAlertOn = false;
    }, 5000); // <-- time in milliseconds
    
});

//confirm deletion of a user
function confirmUserDelete(userId){
    $('#delete_user_modal').modal("show");

    document.getElementById("deleteUserConfirmText").innerHTML = "Confirm delete of this user";

    $('#deleteUserBtn').click(function(){
        deleteUser(userId);
    
  });
}

//deletion of a user
function deleteUser(userId){

    $.ajax({
        url: "/delete-user",
        type: 'post',
        //dataType: 'json',
        data: {
            _token: CSRF_TOKEN,
            user_id: userId
        },
        success: function(data) {
            //console.log(data);
            if(data.success){
                mIsAlertOn = true;
                $('#custom-alert').addClass('custom-alert success');
                $('#alert-msg').html("<i class='fas fa-check-circle'></i> "+data.success);
                $('#custom-alert').show();

                $('#delete_user_modal').modal("hide");
                getUsers();
            }

            if(data.error){
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert');
                $('#alert-msg').html("<i class='fas fa-x'></i> "+data.error);
                $('#custom-alert').show();
            }
        }
    });
}

//populate the divisions dropdown
var div_drpdwn = $("#divisions_dropdown");
    $.get("/divisions-dropdown", function(data) {
        //console.log(data);
        $.each(data, function(index,item) {
            div_drpdwn.append($("<option />").val(item.id).text(item.division));
    });
});

//get the sub divisions
function getSubDivisions(){
    if ($.fn.DataTable.isDataTable('#sub_division_table')) {
        $('#sub_division_table').DataTable().destroy();
    }
    $('#sub_division_table tbody').empty();
    $('#sub_division_table').DataTable({
        //responsive: true,
        processing: true,
        //serverSide: true,
        order: [],
        ajax: {
            url: "/sub-division/table-list",
            dataSrc: function (json) {
                //console.log(json.length);
              var return_data = new Array();
              for(var i=0;i< json.length; i++){
                return_data.push({
                  action: displaySubDivsActionButtons(json[i].id),
                  sub_division_name: json[i].sub_division_name,
                  division_name: json[i].division_name,
                  created_at: json[i].created_at,
                });
                
              }
              return return_data;
            }
        },
        columns: [
        {data: 'sub_division_name'},
        {data: 'division_name'},
        {data: 'created_at'},
        {data: 'action', orderable: false, searchable: false}
        ]
    });
}

  //display sub divisions table action buttons
function displaySubDivsActionButtons(subDivId){

    var actions =  "<div class='btn-toolbar'> <div> "+
    "<a href='#' class='btn btn-xs btn-outline-dark btn-icon' onclick='getSubDivDetails("+subDivId+")' id='edit-post' data-id='"+subDivId+"'> <i class='bi bi-pencil-square'></i> </a> "+
    "<a href='#' class='btn btn-xs btn-outline-danger btn-icon' onclick='confirmSubDivDelete("+subDivId+")' id='delete-post' data-id='"+subDivId+"'> <i class='bi bi-trash'></i> </a>"+
    "</div></div>";

    return actions;
}

//get the sub divisions details for the user to edit
function getSubDivDetails(subDivId){
    var subDivName = document.getElementById("subDivName");
    $("#subDivFormValErr").addClass('d-none');

    $.get("/edit-sub-div-details/"+subDivId, function(data) {
        if(data !== null){
            document.getElementById("subDivisionForm").reset();
            document.getElementById("subDivId").value = subDivId;

            subDivName.value = data.sub_division_name;
            $('#divisions_dropdown').val(data.division_id).change();

            $('#saveSubDivBtn').html("<i class='bi bi-save-fill'></i> Save");
            $('#sub_division_modal').modal("show");
            
        }
    });
}

$('#createSubDivisionBtn').on('click', function (e) {
    document.getElementById("subDivisionForm").reset();
    document.getElementById("subDivId").value = 0;
    $('#sub_division_modal').modal("show");
    $('#saveSubDivBtn').html("<i class='bi bi-plus-circle'></i> Create Sub Division");
});

//show modal to import sub division
$('#subDivBulkInsertBtn').on('click', function (e) {
    $('#sub_div_import_modal').modal("show");
});

//import the sub division
$('#importSubDivBtn').on('click', function (e) {
    e.preventDefault();
    
    // Get form
    var form = $("#subDivImportForm")[0];

    // Create an FormData object
    var data = new FormData(form);
    
    // disabled the submit button
    $("#importSubDivBtn").prop("disabled", true);
    
    $.ajax({
        url: "/sub-division/import",
        type: 'post',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () { // show loading spinner
            $('#loader').removeClass('hidden');
        },
        //dataType: 'json',
        data: data,
        success: function(data) {
            //console.log(data);
            $("#importSubDivBtn").prop("disabled", false);
            document.getElementById("sub_divs_file").value="";
            
            if(data.success){
                getSubDivisions();
                $("#sub_div_import_modal").modal("hide");
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert success');
                $('#alert-msg').html("<i class='fas fa-check-circle'></i> "+data.success);
                $('#custom-alert').show();

            }

            if(data.error){
                let responseHtml = '';
                responseHtml += '<p> * ' + data.error[0].errors[0] + '</p>';
                $("#subDivImportFormValErr").removeClass('d-none');
                $("#subDivImportFormValErr").html( responseHtml );
                document.querySelector('#subDivImportFormValErr').scrollIntoView({
                    behavior: 'smooth'
                });

                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert');
                $('#alert-msg').html("<i class='fas fa-times-circle'></i> Issue with file: "+data.error[0].errors[0]);
                $('#custom-alert').show();
            }
            
        },
        error: function (e) {
            let responseHtml = '';
            var errors = e.responseJSON.errors;
            console.log(data);
            
            responseHtml = '<div class="alert alert-danger">';
            
            $.each( errors , function( key, value ) {
                responseHtml += '<p> * ' + value + '</p>';
            });
            responseHtml += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> </div>';
            
            $("#subDivImportFormValErr").html( responseHtml );
            document.querySelector('#subDivImportFormValErr').scrollIntoView({
                behavior: 'smooth'
            });
            
          $("#importSubDivBtn").prop("disabled", false);
        },
        complete: function () { // hiding the spinner.
            $('#loader').addClass('hidden');
        }
    });
    
    //hide the alert
setTimeout(function() {
    if(mIsAlertOn === true){
        $('#custom-alert').fadeOut('fast');
        $('#custom-alert').removeClass('custom-alert info');
        $('#custom-alert').removeClass('custom-alert warning');
        $('#custom-alert').removeClass('custom-alert success');
        $('#custom-alert').removeClass('custom-alert');
    }
}, 5000); // <-- time in milliseconds
    
});

//handle create / edit positions form submition
$('#saveSubDivBtn').on('click', function (e) {
    e.preventDefault();

    let myForm = document.getElementById('subDivisionForm');
    let data = new FormData(myForm);

    var subDivId = $("#subDivId").val();
    
    var route;
    if(subDivId > 0){
        route = "/save-edit-sub-div-details/"+subDivId;
        data.append("subDivId", subDivId);
    }else{
        route = "/create-sub-division";
    }
    
    // disable the submit button
    $("#saveSubDivBtn").prop("disabled", true);
    
    $.ajax({
        url: route,
        type: 'post',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () { // show loading spinner
            $('#loader').removeClass('hidden');
        },
        data: data,
        success: function(data) {
            //console.log(data);
            $("#saveSubDivBtn").prop("disabled", false);
            if(data.success){
                mIsAlertOn = true;
                $('#custom-alert').addClass('custom-alert success');
                $('#alert-msg').html("<i class='fas fa-check-circle'></i> "+data.success);
                $('#custom-alert').show();

                getSubDivisions();
                document.getElementById("subDivisionForm").reset();
                document.getElementById("subDivId").value = 0;
                $("#sub_division_modal").modal("hide");
            }

            if(data.error){
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert');
                $('#alert-msg').html("<i class='fas fa-x'></i> "+data.error);
                $('#custom-alert').show();
            }

            if(data.info){
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert warning');
                $('#alert-msg').html("<i class='fas fa-info-circle'></i> "+data.info);
                $('#custom-alert').show();
            }
        },
        error: function (e) {
            let responseHtml = '';
            var errors = e.responseJSON.errors;
            //var errors = data.responseJSON;
            
            $.each( errors , function( key, value ) {
                responseHtml = '<p> * ' + value + '</p>';
            });

            //console.log(errors);
            $.each(errors, function (key, value) {
                $('#subDivFormValErr').append('<p> * ' + value + '</p>');
                $("#subDivFormValErr").removeClass('d-none');
                document.getElementById("subDivFormValErr").scrollIntoView();
            });

            mIsAlertOn = true;

            $('#custom-alert').addClass('custom-alert');
            $('#alert-msg').html("<i class='fas fa-x'></i> "+responseHtml);
            $('#custom-alert').show();

          $("#saveSubDivBtn").prop("disabled", false);
        },
        complete: function () { // hiding the spinner.
            $('#loader').addClass('hidden');
        }
    });
    
    //hide the alert
    setTimeout(function() {
        if(mIsAlertOn === true){
            $('#custom-alert').fadeOut('fast');
            $('#custom-alert').removeClass('custom-alert info');
            $('#custom-alert').removeClass('custom-alert warning');
            $('#custom-alert').removeClass('custom-alert success');
            $('#custom-alert').removeClass('custom-alert');
        }
        mIsAlertOn = false;
    }, 5000); // <-- time in milliseconds
    
});

//confirm deletion of a subdiv
function confirmSubDivDelete(subDivId){
    $('#delete_sub_div_modal').modal("show");

    document.getElementById("deleteSubDivConfirmText").innerHTML = "Confirm delete of this sub division";

    $('#deleteSubDivBtn').click(function(){
        deleteSubDivision(subDivId);
  });
}

//deletion of a sub division
function deleteSubDivision(subDivId){

    $.ajax({
        url: "/delete-sub-division",
        type: 'post',
        //dataType: 'json',
        data: {
            _token: CSRF_TOKEN,
            subDivId: subDivId
        },
        success: function(data) {
            if(data.success){
                mIsAlertOn = true;
                $('#custom-alert').addClass('custom-alert success');
                $('#alert-msg').html("<i class='fas fa-check-circle'></i> "+data.success);
                $('#custom-alert').show();

                $('#delete_sub_div_modal').modal("hide");
                getSubDivisions();
            }

            if(data.error){
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert');
                $('#alert-msg').html("<i class='fas fa-x'></i> "+data.error);
                $('#custom-alert').show();
            }

            if(data.info){
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert');
                $('#alert-msg').html("<i class='fas fa-x'></i> "+data.info);
                $('#custom-alert').show();
            }
        }
    });
}

//show modal when add button is clicked
$('#createDivisionBtn').on('click', function (e) {
    document.getElementById("divisionForm").reset();
    document.getElementById("divId").value = 0;
    $('#division_modal').modal("show");
    $('#saveDivBtn').html("<i class='bi bi-plus-circle'></i> Create Division");
});

//show modal to import division
$('#divBulkInsertBtn').on('click', function (e) {
    $('#divisions_import_modal').modal("show");
});

//import the division
$('#importDivBtn').on('click', function (e) {
    e.preventDefault();
    
    // Get form
    var form = $("#divImportForm")[0];

    // Create an FormData object
    var data = new FormData(form);
    
    // disabled the submit button
    $("#importDivBtn").prop("disabled", true);
    
    $.ajax({
        url: "/division/import",
        type: 'post',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () { // show loading spinner
            $('#loader').removeClass('hidden');
        },
        //dataType: 'json',
        data: data,
        success: function(data) {
            //console.log(data);
            $("#importDivBtn").prop("disabled", false);
            document.getElementById("divs_file").value="";
            
            if(data.success){
                getUserDivsions();
                $("#divisions_import_modal").modal("hide");
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert success');
                $('#alert-msg').html("<i class='fas fa-check-circle'></i> "+data.success);
                $('#custom-alert').show();

            }

            if(data.error){
                let responseHtml = '';
                responseHtml += '<p> * ' + data.error[0].errors[0] + '</p>';
                $("#divImportFormValErr").removeClass('d-none');
                $("#divImportFormValErr").html( responseHtml );
                document.querySelector('#divImportFormValErr').scrollIntoView({
                    behavior: 'smooth'
                });

                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert');
                $('#alert-msg').html("<i class='fas fa-times-circle'></i> Issue with file: "+data.error[0].errors[0]);
                $('#custom-alert').show();
            }
            
        },
        error: function (e) {
            let responseHtml = '';
            var errors = e.responseJSON.errors;
            console.log(data);
            
            responseHtml = '<div class="alert alert-danger">';
            
            $.each( errors , function( key, value ) {
                responseHtml += '<p> * ' + value + '</p>';
            });
            responseHtml += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> </div>';
            
            $("#divImportFormValErr").html( responseHtml );
            document.querySelector('#divImportFormValErr').scrollIntoView({
                behavior: 'smooth'
            });
            
          $("#importDivBtn").prop("disabled", false);
        },
        complete: function () { // hiding the spinner.
            $('#loader').addClass('hidden');
        }
    });
    
    //hide the alert
setTimeout(function() {
    if(mIsAlertOn === true){
        $('#custom-alert').fadeOut('fast');
        $('#custom-alert').removeClass('custom-alert info');
        $('#custom-alert').removeClass('custom-alert warning');
        $('#custom-alert').removeClass('custom-alert success');
        $('#custom-alert').removeClass('custom-alert');
    }
}, 5000); // <-- time in milliseconds
    
});

//handle create / edit positions form submition
$('#saveDivBtn').on('click', function (e) {
    e.preventDefault();

    let myForm = document.getElementById('divisionForm');
    let data = new FormData(myForm);

    var divId = $("#divId").val();
    
    var route;
    if(divId > 0){
        route = "/save-edit-div-details/"+divId;
        data.append("divId", divId);
    }else{
        route = "/create-division";
    }
    
    // disable the submit button
    $("#saveDivBtn").prop("disabled", true);
    
    $.ajax({
        url: route,
        type: 'post',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () { // show loading spinner
            $('#loader').removeClass('hidden');
        },
        data: data,
        success: function(data) {
            //console.log(data);
            $("#saveDivBtn").prop("disabled", false);
            if(data.success){
                mIsAlertOn = true;
                $('#custom-alert').addClass('custom-alert success');
                $('#alert-msg').html("<i class='fas fa-check-circle'></i> "+data.success);
                $('#custom-alert').show();

                getUserDivsions();
                document.getElementById("divisionForm").reset();
                document.getElementById("divId").value = 0;
                $("#division_modal").modal("hide");
            }

            if(data.error){
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert');
                $('#alert-msg').html("<i class='fas fa-x'></i> "+data.error);
                $('#custom-alert').show();
            }

            if(data.info){
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert warning');
                $('#alert-msg').html("<i class='fas fa-info-circle'></i> "+data.info);
                $('#custom-alert').show();
            }
        },
        error: function (e) {
            let responseHtml = '';
            var errors = e.responseJSON.errors;
            //var errors = data.responseJSON;
            
            $.each( errors , function( key, value ) {
                responseHtml = '<p> * ' + value + '</p>';
            });

            //console.log(errors);
            $.each(errors, function (key, value) {
                $('#divFormValErr').append('<p> * ' + value + '</p>');
                $("#divFormValErr").removeClass('d-none');
                document.getElementById("divFormValErr").scrollIntoView();
            });

            mIsAlertOn = true;

            $('#custom-alert').addClass('custom-alert');
            $('#alert-msg').html("<i class='fas fa-x'></i> "+responseHtml);
            $('#custom-alert').show();

          $("#saveDivBtn").prop("disabled", false);
        },
        complete: function () { // hiding the spinner.
            $('#loader').addClass('hidden');
        }
    });
    
    //hide the alert
    setTimeout(function() {
        if(mIsAlertOn === true){
            $('#custom-alert').fadeOut('fast');
            $('#custom-alert').removeClass('custom-alert info');
            $('#custom-alert').removeClass('custom-alert warning');
            $('#custom-alert').removeClass('custom-alert success');
            $('#custom-alert').removeClass('custom-alert');
        }
        mIsAlertOn = false;
    }, 5000); // <-- time in milliseconds
    
});

//get the divisions
function getUserDivsions(){
    if ($.fn.DataTable.isDataTable('#division_table')) {
        $('#division_table').DataTable().destroy();
    }
    $('#division_table tbody').empty();
    $('#division_table').DataTable({
        //responsive: true,
        processing: true,
        //serverSide: true,
        order: [],
        ajax: {
            url: "/division/table-list",
            dataSrc: function (json) {
                //console.log(json.length);
              var return_data = new Array();
              for(var i=0;i< json.length; i++){
                return_data.push({
                  action: displayDivsActionButtons(json[i].id),
                  division_name: json[i].division_name,
                  created_at: json[i].created_at,
                });
                
              }
              return return_data;
            }
        },
        columns: [
        {data: 'division_name'},
        {data: 'created_at'},
        {data: 'action', orderable: false, searchable: false}
        ]
    });
}

  //display divisions table action buttons
function displayDivsActionButtons(divId){

    var actions =  "<div class='btn-toolbar'> <div> "+
    "<a href='#' class='btn btn-xs btn-outline-dark btn-icon' onclick='getDivDetails("+divId+")' id='edit-div' data-id='"+divId+"'> <i class='bi bi-pencil-square'></i> </a> "+
    "<a href='#' class='btn btn-xs btn-outline-danger btn-icon' onclick='confirmDivDelete("+divId+")' id='delete-div' data-id='"+divId+"'> <i class='bi bi-trash'></i> </a>"+
    "</div></div>";

    return actions;
}

//get the divisions details for the user to edit
function getDivDetails(subDivId){
    var divName = document.getElementById("divName");
    $("#divFormValErr").addClass('d-none');

    $.get("/edit-div-details/"+subDivId, function(data) {
        if(data !== null){
            document.getElementById("divisionForm").reset();
            document.getElementById("divId").value = subDivId;

            divName.value = data.division_name;

            $('#saveDivBtn').html("<i class='bi bi-save-fill'></i> Save");
            $('#division_modal').modal("show");
            
        }
    });
}

//confirm deletion of a division
function confirmDivDelete(divId){
    $('#delete_div_modal').modal("show");

    document.getElementById("deleteDivConfirmText").innerHTML = "Confirm delete of this division";

    $('#deleteDivBtn').click(function(){
        deleteDivision(divId);
  });
}

//deletion of a division
function deleteDivision(divId){

    $.ajax({
        url: "/delete-division",
        type: 'post',
        //dataType: 'json',
        data: {
            _token: CSRF_TOKEN,
            divId: divId
        },
        beforeSend: function () { // show loading spinner
            $('#loader').removeClass('hidden');
        },
        success: function(data) {
            if(data.success){
                mIsAlertOn = true;
                $('#custom-alert').addClass('custom-alert success');
                $('#alert-msg').html("<i class='fas fa-check-circle'></i> "+data.success);
                $('#custom-alert').show();

                $('#delete_div_modal').modal("hide");
                getUserDivsions();
            }

            if(data.error){
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert');
                $('#alert-msg').html("<i class='fas fa-x'></i> "+data.error);
                $('#custom-alert').show();
            }

            if(data.info){
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert');
                $('#alert-msg').html("<i class='fas fa-x'></i> "+data.info);
                $('#custom-alert').show();
            }
        },
        complete: function () { // hiding the spinner.
            $('#loader').addClass('hidden');
        }
    });
}

//the select for the divisions when adding a user to a division / sub division
$('#user_division_dropdown').select2({
    placeholder: 'Select division',
    searchInputPlaceholder: 'Search division',
    ajax: {
        url: '/divisions-dropdown',
        dataType: 'json',
        delay: 250,
        data: function (data) {
            //_token: CSRF_TOKEN;
            return {
                keyword: data.term // search term
            };
        },
        processResults: function (response) {
            return {
                results: $.map(response, function (item) {
                    return {
                        text: item.division,
                        //slug: item.slug,
                        id: item.id
                    };
                })
            };
        },
        cache: true
    }
});

//get the id of the selected division id 
//use it to get the sub divisions
function getSelectedDivision(event){
    var divisionId = event.target.value;
    
    //unselect previously selected
    $('#user_sub_division_dropdown').val(0);
    
    $('#user_sub_division_dropdown').select2({
      placeholder: 'Select sub division',
      searchInputPlaceholder: 'Search sub division',
      //minimumInputLength: 2,
        //tags: [],
        ajax: {
            url: "/sub-division-dropdown/"+divisionId,
            dataType: 'json',
            delay: 250,
            data: function (data) {
                //_token: CSRF_TOKEN;
                return {
                    keyword: data.term // search term
                };
            },
            processResults: function (response) {
                return {
                    results: $.map(response, function (item) {
                        return {
                            text: item.label,
                            //slug: item.slug,
                            id: item.value
                        };
                    })
                };
            }
            //cache: true
        }
    });
}

//show modal to create voter base
$('#createVotersBtn').on('click', function (e) {
    $("#voterBaseElectId").show();
    $("#election_voters_table").hide();
    $('#voters_modal').modal("show");
});

//populate the elections dropdown when selecting a voter base
var elect_drpdwn = $("#voters_election_dropdown");
    $.get("/elections-dropdown", function(data) {
        //console.log(data);
        $.each(data, function(index,item) {
            $("#voters_election_dropdown").append($("<option />").val(item.id).text(item.name));
    });
});

//the select for the divisions when adding voters to an election
$('#voters_division_dropdown').select2({
    placeholder: 'Select division',
    searchInputPlaceholder: 'Search division',
    ajax: {
        url: '/divisions-dropdown',
        dataType: 'json',
        delay: 250,
        data: function (data) {
            //_token: CSRF_TOKEN;
            return {
                keyword: data.term // search term
            };
        },
        processResults: function (response) {
            return {
                results: $.map(response, function (item) {
                    return {
                        text: item.division,
                        //slug: item.slug,
                        id: item.id
                    };
                })
            };
        },
        cache: true
    }
});



//get the id of the selected divsion
//use it to get the sub divisions in that division
function getVoterBaseSelectedDivision(event){
    var divisionId = event.target.value;
    
    //unselect previously selected
    $('#voters_sub_division_dropdown').val(0);
    
    $('#voters_sub_division_dropdown').select2({
      placeholder: 'Select sub division',
      searchInputPlaceholder: 'Search sub division',
      //minimumInputLength: 2,
        //tags: [],
        ajax: {
            url: "/sub-division-dropdown/"+divisionId,
            dataType: 'json',
            delay: 250,
            data: function (data) {
                //_token: CSRF_TOKEN;
                return {
                    keyword: data.term // search term
                };
            },
            processResults: function (response) {
                
                return {
                    results: $.map(response, function (item) {
                        return {
                            text: item.label,
                            //slug: item.slug,
                            id: item.value
                        };
                    })
                };
            }
            //cache: true
        }
    });

    $("#voters_sub_division_dropdown").append($("<option />").val(0).text("All Sub Divisions"));
    $("#voters_sub_division_dropdown").trigger('change');
}

//holds map of scanned for disposal approval
var mVoterIds = new Map();

//add voters selected to map of voters
$('#addVotersBtn').on('click', function (e) {
    e.preventDefault();
    let myForm = document.getElementById('votersForm');
    let data = new FormData(myForm);

    var electionId = $("#voters_election_dropdown").children("option:selected").val();
    var division_name = $("#voters_division_dropdown").children("option:selected").text();
    var divId = $("#voters_division_dropdown").children("option:selected").val();
    var subDivId = $("#voters_sub_division_dropdown").children("option:selected").val();
    var subDivName = $("#voters_sub_division_dropdown").children("option:selected").text();

    var votersId = document.getElementById("votersId").value;

    //if user is editing the voter base for an election
    if(votersId > 0){
        electionId = votersId;
    }

    if (typeof subDivId == "undefined") {
        subDivId = 0;
    }

    if (divId > 0) {
        if (electionId > 0) {
            data.append("election_id", electionId);
            data.append("division_id", divId);
            data.append("sub_div_id", subDivId);

            // disable the submit button
            $("#addVotersBtn").prop("disabled", true);

            $.ajax({
                url: "add-voters",
                type: 'post',
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function () { // show loading spinner
                    $('#loader').removeClass('hidden');
                },
                data: data,
                success: function(data) {
                    //console.log(data);
                    $("#addVotersBtn").prop("disabled", false);
                    if(data.success){
                        mIsAlertOn = true;
                        $('#custom-alert').addClass('custom-alert success');
                        $('#alert-msg').html("<i class='fas fa-check-circle'></i>"+data.success);
                        $('#custom-alert').show();

                        getElectionVoters(electionId);
                        document.getElementById("votersForm").reset();
                        document.getElementById("voters_sub_division_dropdown").value = "";
                        document.getElementById("votersId").value = "";
                        $("#election_voters_table").hide();
                        $("#division_modal").modal("hide");
                    }

                    if(data.error){
                        mIsAlertOn = true;

                        $('#custom-alert').addClass('custom-alert');
                        $('#alert-msg').html("<i class='fas fa-x'></i> "+data.error);
                        $('#custom-alert').show();
                    }

                    if(data.info){
                        mIsAlertOn = true;

                        $('#custom-alert').addClass('custom-alert warning');
                        $('#alert-msg').html("<i class='fas fa-info-circle'></i> "+data.info);
                        $('#custom-alert').show();
                    }
                },
                error: function (e) {
                    let responseHtml = '';
                    var errors = e.responseJSON.errors;
                    
                    $.each( errors , function( key, value ) {
                        responseHtml = '<p> * ' + value + '</p>';
                    });

                    //console.log(errors);
                    $.each(errors, function (key, value) {
                        $('#divFormValErr').append('<p> * ' + value + '</p>');
                        $("#divFormValErr").removeClass('d-none');
                        document.getElementById("divFormValErr").scrollIntoView();
                    });

                    mIsAlertOn = true;

                    $('#custom-alert').addClass('custom-alert');
                    $('#alert-msg').html("<i class='fas fa-x'></i> "+responseHtml);
                    $('#custom-alert').show();

                $("#addVotersBtn").prop("disabled", false);
                },
                complete: function () { // hiding the spinner.
                    $('#loader').addClass('hidden');
                }
            });
        }
        
    }else{
        mIsAlertOn = true;

        $('#custom-alert').addClass('custom-alert primary');
        $('#alert-msg').html("<i class='fas fa-info-circle'></i> First select an election and a division");
        $('#custom-alert').show();
    }
    //hide the alert
    setTimeout(function() {
        if(mIsAlertOn === true){
            $('#custom-alert').fadeOut('fast');
            $('#custom-alert').removeClass('custom-alert info');
            $('#custom-alert').removeClass('custom-alert warning');
            $('#custom-alert').removeClass('custom-alert success');
            $('#custom-alert').removeClass('custom-alert');
        }
        mIsAlertOn = false;
    }, 5000); // <-- time in milliseconds

});

function getElectionVoters(electionId){
    var votersList = $("#election_voters_table");
    $('#election_voters_table tbody').empty();
        
    $.get("/get-election-voters/"+electionId, function(data) {
        if (data[0]['election_name'] != null){
            votersList.show();
            $.each(data, function(index,item) {

                votersList.append("<tr><td>"+item.division_name+
                "</td><td>"+item.sub_division_name+
                "</td><td><a id='rem-voters-id' class='mg-r-10' data-toggle='tooltip' onclick='removeElectionVoter("+item.id+ ',' +item.election_id+")' data-placement='bottom' title='Remove from list' data-id='"+item.id+"' href='#'>"+
                "<i style='font-size: 1em; color: #FF0000;' class='bi bi-trash'></i></a>"+
                "</td></tr>");
            });
        }else{
            votersList.hide();
        }

    });
}

//delete a voter form the election
function removeElectionVoter(voterId, electionId){

    $.ajax({
        url: "/delete-election-voter",
        type: 'post',
        //dataType: 'json',
        data: {
            _token: CSRF_TOKEN,
            voter_id: voterId
        },
        beforeSend: function () { // show loading spinner
            $('#loader').removeClass('hidden');
        },
        success: function(data) {
            if(data.success){
                mIsAlertOn = true;
                $('#custom-alert').addClass('custom-alert success');
                $('#alert-msg').html("<i class='fas fa-check-circle'></i> "+data.success);
                $('#custom-alert').show();
                getElectionVoters(electionId);
            }

            if(data.error){
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert');
                $('#alert-msg').html("<i class='fas fa-x'></i> "+data.error);
                $('#custom-alert').show();
            }

            if(data.info){
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert');
                $('#alert-msg').html("<i class='fas fa-x'></i> "+data.info);
                $('#custom-alert').show();
            }
        },
        complete: function () { // hiding the spinner.
            $('#loader').addClass('hidden');
        }
    });
}

//populate elections dropdown for viewing election voter base
$('#view_election_voters').select2({
    placeholder: 'Select election',
    searchInputPlaceholder: 'Search election',
    ajax: {
        url: '/elections-dropdown',
        dataType: 'json',
        delay: 250,
        data: function (data) {
            //_token: CSRF_TOKEN;
            return {
                keyword: data.term // search term
            };
        },
        processResults: function (response) {
            return {
                results: $.map(response, function (item) {
                    return {
                        text: item.name,
                        id: item.id
                    };
                })
            };
        },
        cache: true
    }
});

//get the voter base of the selected election
$("#view_election_voters").on('change', function() {
    var electionId = $(this).children("option:selected").val();
    getElectionVoters(electionId);
});

function getElectionVoters(electionId){
    if ($.fn.DataTable.isDataTable('#voters_table')) {
        $('#voters_table').DataTable().destroy();
        $('#voters_table').empty();
    }

    $('#voters_table').append("<thead class='thead-dark'><tr>"+
        "<th class='wd-20p'>Election Name</th>"+
        "<th class='wd-20p'>Division Name</th>"+
        "<th class='wd-25p'>Sub Division Name</th>"+
        "<th class='wd-10p'>Action</th>"+
    "</tr></thead>");

    $('#voters_table').DataTable({
        //responsive: true,
        processing: true,
        //serverSide: true,
        order: [],
        ajax: {
            url: "/get-election-voters/"+electionId,
            dataSrc: function (json) {
                //console.log(json.length);
              var return_data = new Array();
              for(var i=0;i< json.length; i++){
                return_data.push({
                  action: displayVoterBaseActionButtons(json[i].id, json[i].election_id),
                  division_name: json[i].division_name,
                  election_name: json[i].election_name,
                  sub_division_name: json[i].sub_division_name
                });
                
              }
              return return_data;
            }
        },
        columns: [
        {data: 'election_name'},
        {data: 'division_name'},
        {data: 'sub_division_name'},
        {data: 'action', orderable: false, searchable: false}
        ]
    });
}

function displayVoterBaseActionButtons(votersId,election_id){

    var actions =  "<div class='btn-toolbar'> <div> "+
    "<a href='#' class='btn btn-xs btn-outline-danger btn-icon' onclick='confirmVoterBaseDelete("+votersId+ ',' +election_id+")' id='delete-post' data-id='"+votersId+"'> <i class='bi bi-trash'></i> </a>"+
    "</div></div>";

    return actions;
}

//confirm deletion of a voter base for an election
function confirmVoterBaseDelete(votersId, election_id){
    $('#delete_voters_modal').modal("show");

    document.getElementById("confirmVotersDeleteLabel").innerHTML = "Confirm delete of this voter base";

    $('#deleteVotersBtn').on('click', function (e) {
        deleteVoterBase(votersId,election_id);
    });
}

//deletion of a voter base
function deleteVoterBase(votersId,election_id){

    $.ajax({
        url: "/delete-election-voter",
        type: 'post',
        //dataType: 'json',
        data: {
            _token: CSRF_TOKEN,
            voter_id: votersId
        },
        beforeSend: function () { // show loading spinner
            $('#loader').removeClass('hidden');
        },
        success: function(data) {
            if(data.success){
                mIsAlertOn = true;
                $('#custom-alert').addClass('custom-alert success');
                $('#alert-msg').html("<i class='fas fa-check-circle'></i> "+data.success);
                $('#custom-alert').show();

                $('#delete_voters_modal').modal("hide");
                getElectionVoters(election_id);
            }

            if(data.error){
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert');
                $('#alert-msg').html("<i class='fas fa-x'></i> "+data.error);
                $('#custom-alert').show();
            }

            if(data.info){
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert');
                $('#alert-msg').html("<i class='fas fa-x'></i> "+data.info);
                $('#custom-alert').show();
            }
        },
        complete: function () { // hiding the spinner.
            $('#loader').addClass('hidden');
        }
    });
}

getElectionResults();

//get the election results
function getElectionResults(){
    if ($.fn.DataTable.isDataTable('#election_results_table')) {
        $('#election_results_table').DataTable().destroy();
        $('#election_results_table').empty();
    }

    $('#election_results_table').append("<thead class='thead-dark'><tr>"+
        "<th class='wd-20p'>Election Name</th>"+
        "<th class='wd-20p'>From</th>"+
        "<th class='wd-25p'>To</th>"+
        "<th class='wd-25p'>Winner</th>"+
        "<th class='wd-10p'>Action</th>"+
    "</tr></thead>");

    $('#election_results_table').DataTable({
        //responsive: true,
        processing: true,
        //serverSide: true,
        order: [],
        ajax: {
            url: "/get-election-results",
            dataSrc: function (json) {
                //console.log(json.length);
              var return_data = new Array();
              for(var i=0;i< json.length; i++){
                return_data.push({
                  action: displayElectionResultsActionButtons(json[i].id, json[i].election_id),
                  start_date: formatDate(json[i].start_date),
                  end_date: formatDate(json[i].end_date),
                  election_name: json[i].election_name,
                  candidate_name: json[i].candidate_name
                });
                
              }
              return return_data;
            }
        },
        columns: [
        {data: 'election_name'},
        {data: 'start_date'},
        {data: 'end_date'},
        {data: 'candidate_name'},
        {data: 'action', orderable: false, searchable: false}
        ]
    });
}

function displayElectionResultsActionButtons(votesId,election_id){

    var actions =  "<div class='btn-toolbar'> <div> "+
    "<a href='#' class='btn btn-xs btn-outline-primary btn-icon' onclick='getElectionResultsSummary("+votesId+ ',' +election_id+")' id='delete-post' data-id='"+votesId+"'> <i class='bi bi-eye'></i> </a>"+
    "</div></div>";

    return actions;
}

//show summary details of an election
function getElectionResultsSummary(votesId, electionId){

    document.getElementById("election_id").value = electionId;
    
    $.ajax({
        url: "/election-summary-details/"+votesId+"/"+electionId,
        type: 'get',
        beforeSend: function () { // show loading spinner
            $('#loader').removeClass('hidden');
        },
        success: function(data) {
            //console.log(data[6][0].post_name);
            //console.log(data[6].length);
            if(data !== null){
                var candidates = $("#elect_candidates_res");
                candidates.html("");
                var highestVotes = 0;
                var canName = "";
                $('#votes_cast').html("<strong>" + data[1].votes_cast + "</strong>");
                $('#voter_base').html("<strong>" + data[3] + "</strong>");
                $('#election_period').html("<strong>" + formatDate(data[4]) + " - " + formatDate(data[5]) +"</strong>");
                $('#elect_candidates').html("<strong>" + data[2].candidates_num + "</strong>");

                $('#elecResModalLabel').html("<strong>" + data[0].name + "</strong>");
                $('#election_res_modal').modal("show");

                for (i=0; i<data[6].length; i++) {
                    
                    if(data[6][i].total_votes > highestVotes){
                        highestVotes = data[6][i].total_votes;
                        canName = "<i class='bi bi-stars' style='color: #f5cf13;'></i> <strong><u> "+ data[6][i].candidate_name +" </u></strong><i class='bi bi-stars' style='color: #f5cf13;'></i>"
                    }else{
                        canName = data[6][i].candidate_name;
                    }
                    
                    candidates.append("<div class='col-lg-3'><article class='d-flex flex-column'>"+
                        "<div class='post-img'>"+
                            "<img src='images/candidates/"+ data[6][i].image +"' alt="+ data[6][i].candidate_name +" class='img-fluid' >"+
                        "</div>" + 
                        "<h5 class='title m-0 mg-b-0'>"+
                            canName +
                        "</h5>"+
                        "<div class='content mg-t-0 p-0'><p>"+
                            "<h6 class='tx-normal tx-primary mg-b-0 mg-r-5 lh-1'>"+ data[6][i].total_votes +" Vote(s) </h6>"+
                        "</p></div>"+
                        "</article></div>");
                }
            }
        },
        complete: function () { // hiding the spinner.
            $('#loader').addClass('hidden');
        }
    });
}

//download election results excel
$('#election-res-excel-download').on('click',function(){

    $.ajax({
        url: "/election/download_election_results_excel",
        type: 'get',
        beforeSend: function () { // show loading spinner
            $('#loader').removeClass('hidden');
        },
        success: function(data) {
            window.location = "/election/download_election_results_excel";
        },
        complete: function () { // hiding the spinner.
            $('#loader').addClass('hidden');
        }
    });
});

//download candidate results excel
$('#candidate-res-excel-download').on('click',function(){
    var election_id = document.getElementById("election_id").value;

    $.ajax({
        url: "/election/download_candidate_results_excel/"+election_id,
        type: 'get',
        beforeSend: function () { // show loading spinner
            $('#loader').removeClass('hidden');
        },
        success: function(data) {
            window.location = "/election/download_candidate_results_excel/"+election_id;
        },
        complete: function () { // hiding the spinner.
            $('#loader').addClass('hidden');
        }
    });
});