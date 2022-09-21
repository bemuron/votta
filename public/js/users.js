//csrf token
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

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
function onDownloadTemplateFileClick(item_id){
    //console.log("called to donwload");
    $.ajax({
        url: "/download-requisition-file/"+item_id,
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
                window.location = "/download-requisition-file/"+item_id;
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

//populate the divisions dropdown dropdown
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
