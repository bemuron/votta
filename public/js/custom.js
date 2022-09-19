//csrf token
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
var mElectionPosId = 0;
var mElectionId = 0;
var mCandidateId = 0;
var mIsAlertOn = false;

//get the candidates's details and display
function getCandidateDetails(canId, electionId, postId, clickedButton){
    var candidateName = document.getElementById("canName");
    var candidatePost = document.getElementById("canPost");
    var candidateDets = document.getElementById("canDets");
    var confirmVoteText = document.getElementById("confirmText");
    var imgpath = "images/candidates/";
    var candidateImg;

    $.get("/candidate-details/"+canId+"/"+electionId, function(data) {
        if(data !== null){
            if (clickedButton == 1){
                candidateName.innerHTML = data.name;
                candidatePost.innerHTML = data.post_name;
                candidateDets.innerHTML = data.description;
                console.log("Candidate name "+data.name);
                candidateImg = imgpath+data.image;

                $("#canImg").html('<img src="' + candidateImg + '" alt="Card image" style="width:100%"/>');

                $('#canDetailsModal').modal("show");
            }else{
                mElectionPosId = postId;
                mElectionId = electionId;
                mCandidateId = canId;
                confirmVoteText.innerHTML = "Confirm casting your vote for <strong>"+data.name+"</strong>"+ " for the position of <strong>"+data.post_name+"</strong>";
                $('#confirmVoteModal').modal("show");
            }
            
        }
    });
    //console.log('You are running jQuery version: ' + window.jQuery.fn.jquery);
    //console.log("jQuery "+ (jQuery ? $().jquery : "NOT") +" loaded");
}

//cast a vote for a candidate
function voteCandidate(){
    var alreadyVotedText = document.getElementById("alreadyVotedText");

    $.ajax({
        url: "/vote-candidate",
        type: 'post',
        //dataType: 'json',
        data: {
            _token: CSRF_TOKEN,
            candidate_id: mCandidateId,
            election_id: mElectionId,
            post_id: mElectionPosId
        },
        success: function(data) {
            if(data == 1){
                mElectionPosId = 0;
                mElectionId = 0;
                mCandidateId = 0;
                
                $("#voteSuccessAlert").fadeTo(2000, 5000).slideUp(500, function(){
                    $("#voteSuccessAlert").slideUp(500);
                });

                document.getElementById("votePromptBtn").innerHTML = 'Thanks for voting';
                document.getElementById("votePromptBtn").disabled = true; 
                
            }else if(data == 0){
                //console.log(data);
                //alert("Somethng went wrong, details not saved");
                $("#voteFailedAlert").fadeTo(2000, 500).slideUp(500, function(){
                    $("#voteFailedAlert").slideUp(500);
                });
            }else if(data.name != null ){
                alreadyVotedText.innerHTML = "You already voted "+data.name+" for the position of <strong>"+data.post_name+"</strong>";
                $('#alreadyVotedModal').modal("show");
                document.getElementById("votePromptBtn").innerHTML = 'You already voted';
                document.getElementById("votePromptBtn").disabled = true; 

            }else{
                //error occured
                $("#voteFailedAlert").fadeTo(2000, 500).slideUp(500, function(){
                    $("#voteFailedAlert").slideUp(500);
                });
            }

        },
        error: function (e) {
            let responseHtml = '';
            var errors = e.responseJSON.errors;
            
        },
    });
}

//election duration date pickers
var dateFormat = 'mm-dd-yy',
from = $('#electDateFrom')
.datepicker({
    //defaultDate: '+1w',
    dateFormat: 'yy-mm-dd'
    //minDate: 0
    //numberOfMonths: 2
})
.on('change', function() {
    to.datepicker('option','minDate', getDate( this ) );
}),

to = $('#electDateTo').datepicker({
    //defaultDate: '+1w',
    dateFormat: 'yy-mm-dd'
    //numberOfMonths: 2
})
.on('change', function() {
    from.datepicker('option','maxDate', getDate( this ) );
});

function getDate( element ) {
    var date;
    try {
      date = $.datepicker.parseDate( dateFormat, element.value );
    } catch( error ) {
      date = null;
    }

    return date;
  }

  //get all the election candidates
  function getElectionCandidates(){
    //display the elections created
    if ($.fn.DataTable.isDataTable('#candidates_table')) {
        $('#candidates_table').DataTable().destroy();
    }
    $('#candidates_table tbody').empty();
    $('#candidates_table').DataTable({
        //responsive: true,
        processing: true,
        //serverSide: true,
        order: [],
        ajax: {
            url: "/candidates/table-list",
            dataSrc: function (json) {
                //console.log(json.length);
            var return_data = new Array();
            for(var i=0;i< json.length; i++){
                return_data.push({
                  action: displayCandidatesActionButtons(json[i].id),
                  candidate_name: json[i].candidate_name,
                  election_name: json[i].election_name,
                  post_name: json[i].post_name,
                  description: json[i].description,
                  image: displayCandidateImage(json[i].image),
                  created_at: formatDate(json[i].created_at)
                });
                
            }
                return return_data;
            }
        },
        columns: [
        {data: 'candidate_name'},
        {data: 'election_name'},
        {data: 'post_name'},
        {data: 'description'},
        {data: 'image', orderable: false, searchable: false},
        {data: 'created_at'},
        {data: 'action', orderable: false, searchable: false}
        ]
    });
  }

  //get all elections
  function getAllElections(){
      //display the elections created
  if ($.fn.DataTable.isDataTable('#elections_table')) {
    $('#elections_table').DataTable().destroy();
}
$('#elections_table tbody').empty();
$('#elections_table').DataTable({
    //responsive: true,
    processing: true,
    //serverSide: true,
    order: [],
    ajax: {
        url: "/elections/table-list",
        dataSrc: function (json) {
            //console.log(json.length);
          var return_data = new Array();
          for(var i=0;i< json.length; i++){
            return_data.push({
              action: displayActionButtons(json[i].id),
                name: json[i].name,
                description: json[i].description,
                status: json[i].status,
                start_date: formatDate(json[i].start_date),
                end_date: formatDate(json[i].end_date),
                image: displayImage(json[i].image),
                image_big: displayImage(json[i].image_big),
                created_at: formatDate(json[i].created_at),
                created_by: json[i].created_by
              });
              
            }
            return return_data;
          }
      },
      columns: [
      {data: 'name'},
      {data: 'status'},
      {data: 'start_date'},
      {data: 'end_date'},
      {data: 'description'},
      {data: 'image', orderable: false, searchable: false},
      {data: 'image_big', orderable: false, searchable: false},
      {data: 'created_at'},
      {data: 'created_by'},
      {data: 'action', orderable: false, searchable: false}
      ]
  });
  }

//show modal to create a new election
$('#createElectionBtn').on('click', function (e) {
    document.getElementById("addElectionForm").reset();
    document.getElementById("electionId").value = 0;
    $("#electThumbImg").html('');
    $("#electBigImg").html('');
    $('#elections_modal').modal("show");
    $('#saveElectionBtn').html("<i class='bi bi-plus-circle'></i> Create Election");
});

//show the respective table data after page load
window.onload = function() {
var pageTitle = document.getElementById("page-title");
switch(pageTitle.innerHTML) {
    case "Manage Elections":
        getAllElections();
    break;
    case "Manage Candidates":
        getElectionCandidates();
    break;
    case "Manage Posts":
        getElectionPosts();
    break;
    case "Manage Users":
        getUsers();
    break;
    case "Manage Divisions":
        getUserDivsions();
    break;
    case "Manage Sub Divisions":
        getUserSubDivisions();
    break;
    default:
      // code block
  }

}

//format the date
function formatDate(dateToFormat){
    var date = new Date(dateToFormat);
    return date.toLocaleDateString("en-GB",{day: "numeric", month: "short",year: "numeric"});
}

//display image
function displayImage(imgeName){
    var imgTag = "<img src='images/elections/"+imgeName+"' width='100'>";
    return imgTag
}

//display candidate image
function displayCandidateImage(imgeName){
    var imgTag = "<img src='images/candidates/"+imgeName+"' width='100'>";
    return imgTag
}

//display action buttons
function displayActionButtons(electionId){
    var actions =  "<div class='btn-toolbar'> <div> "+
    "<a href='#elections_modal' class='btn btn-xs btn-outline-dark btn-icon' onclick='getElectionDetails("+electionId+")' id='edit-election' data-id='"+electionId+"'><i class='bi bi-pencil-square'></i> </a> "+
    "<a href='#delete_election_modal' class='btn btn-xs btn-outline-danger btn-icon' onclick='confirmElectionDelete("+electionId+")' id='delete-election' data-id='"+electionId+"' data-toggle='modal' data-animation='effect-scale'> <i class='bi bi-trash'></i></a>"+
    "</div></div>";

    return actions;
}

//display action buttons for candidates list
function displayCandidatesActionButtons(candidateId){
    var actions =  "<div class='btn-toolbar'> <div> "+
    "<a href='#edit_candidate_modal' class='btn btn-xs btn-outline-dark btn-icon' onclick='getElectionCandidateDetails("+candidateId+")' id='edit-candidate' data-id='"+candidateId+"'> <i class='bi bi-pencil-square'></i></a> "+
    "<a href='#' class='btn btn-xs btn-outline-danger btn-icon' onclick='confirmElectionCandidateDelete("+candidateId+")' id='delete-candidate' data-id='"+candidateId+"'> <i class='bi bi-trash'></i></a>"+
    "</div></div>";

    return actions;
}

//handle create / edit election form submition
$('#saveElectionBtn').on('click', function (e) {
    e.preventDefault();

    // Get form
    var form = $("#addElectionForm")[0];

    // Create an FormData object
    var data = new FormData(form);
    
    var electionId = $("#electionId").val();
    
    var route;
    if(electionId > 0){
        route = "/save-edit-election-details/"+electionId;
        data.append("electionId", electionId);
    }else{
        route = "/create-election";
    }
    
    // disable the submit button
    $("#saveElectionBtn").prop("disabled", true);
    
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
            $("#saveElectionBtn").prop("disabled", false);
            if(data.success){
                mIsAlertOn = true;
                $('#custom-alert').addClass('custom-alert success');
                $('#alert-msg').html("<i class='fas fa-check-circle'></i> "+data.success);
                $('#custom-alert').show();

                getAllElections();
                document.getElementById("addElectionForm").reset();
                document.getElementById("electionId").value = 0;
                $("#elections_modal").modal("hide");
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
                $('#electFormValErr').append('<p> * ' + value + '</p>');
                $("#electFormValErr").removeClass('d-none');
                document.getElementById("electFormValErr").scrollIntoView();
            });

            mIsAlertOn = true;

            $('#custom-alert').addClass('custom-alert');
            $('#alert-msg').html("<i class='fas fa-x'></i> "+responseHtml);
            $('#custom-alert').show();

          $("#saveElectionBtn").prop("disabled", false);
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

//get the election details for the user to edit
function getElectionDetails(electionId){
    var electionName = document.getElementById("electionName");
    var electionStart = document.getElementById("electDateFrom");
    var electionEnd = document.getElementById("electDateTo");
    var electionStatus = document.getElementById("electionStatus");
    var electionImage = document.getElementById("electionThumbImg");
    var electionBigImage = document.getElementById("electionBigImg");
    var electionDescription = document.getElementById("electionDescription");
    var election_Id = document.getElementById("electionId");
    var imgpath = "images/elections/";
    var electionImg;
    var electionBigImg;
    $("#electFormValErr").addClass('d-none');

    $.get("/edit-election-details/"+electionId, function(data) {
        if(data !== null){
            document.getElementById("addElectionForm").reset();
            $("#electThumbImg").html('');
            $("#electBigImg").html('');

            electionImg = imgpath+data.image;
            electionBigImg = imgpath+data.image_big;
            electionName.value = data.name;
            electionStart.value = data.start_date;
            electionEnd.value = data.end_date;
            electionDescription.value = data.description;
            election_Id.value = electionId;
            $('#electionStatus').val(data.status).change();

            $("#electThumbImg").html('<img src="' + electionImg + '" alt="Card image" width="200" height="200"/>');
            $("#electBigImg").html('<img src="' + electionBigImg + '" alt="Card image" width="200" height="200"/>');

            $('#saveElectionBtn').html("<i class='bi bi-save-fill'></i> Save");
            $('#elections_modal').modal("show");
            
        }
    });
}

//get the candidate details for the user to edit
function getElectionCandidateDetails(candidateId){
    var candidateName = document.getElementById("candidateName");
    var electionName = document.getElementById("candidate_election_dropdown");
    var candidatePosition = document.getElementById("candidate_position_dropdown");
    var candidateImage = document.getElementById("candidateImg");
    var candidateDescription = document.getElementById("candidateDescription");
    var recordId = document.getElementById("candidateRecordId");
    var imgpath = "images/candidates/";
    var canImg;
    $("#electFormValErr").addClass('d-none');

    $.get("/edit-candidate-details/"+candidateId, function(data) {
        if(data !== null){
            document.getElementById("candidateForm").reset();
            $("#CandidateImgDisplay").html('');

            recordId.value = data.id;
            canImg = imgpath+data.image;

            candidateDescription.value = data.description;
            candidateName.value = data.candidate_name;
            $('#candidate_position_dropdown').append($("<option />").val(data.post_id).text(data.post_name));
            $('#candidate_election_dropdown').append($("<option />").val(data.election_id).text(data.election_name));

            $("#CandidateImgDisplay").html('<img src="' + canImg + '" alt="Card image" width="200" height="200"/>');

            $('#saveCandidateBtn').html("<i class='bi bi-save-fill'></i> Save");
            $('#candidate_modal').modal("show");
            
        }
    });
}

//confirm deletion of a candidate
function confirmElectionCandidateDelete(canId){
    $('#delete_candidate_modal').modal("show");

    document.getElementById("deleteCandidateConfirmText").innerHTML = "Confirm delete of this candidate";

    $('#deleteCandidateBtn').click(function(){
        deleteCandidate(canId);
    
  });
}

//deletion of a candidate
function deleteCandidate(canId){

    $.ajax({
        url: "/delete-candidate",
        type: 'post',
        //dataType: 'json',
        data: {
            _token: CSRF_TOKEN,
            candidate_id: canId
        },
        success: function(data) {
            //console.log(data);
            if(data.success){
                mIsAlertOn = true;
                $('#custom-alert').addClass('custom-alert success');
                $('#alert-msg').html("<i class='fas fa-check-circle'></i> "+data.success);
                $('#custom-alert').show();

                $('#delete_candidate_modal').modal("hide");
                getElectionCandidates();
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

//confirm deletion of an election
function confirmElectionDelete(electionId){
    $('#delete_election_modal').modal("show");

    document.getElementById("deleteElectionConfirmText").innerHTML = "Confirm delete of this election";

    $('#deletElectionBtn').click(function(){
        deleteElection(electionId);
    
  });
}

//deletion of an election
function deleteElection(electionId){

    $.ajax({
        url: "/delete-election",
        type: 'post',
        //dataType: 'json',
        data: {
            _token: CSRF_TOKEN,
            election_id: electionId
        },
        success: function(data) {
            if(data == 1){
                $("#successAlert").fadeTo(2000, 5000).slideUp(500, function(){
                    $("#successAlert").slideUp(500);
                });

                $('#delete_election_modal').modal("hide");

                //display the elections created
                getAllElections();
            }else{
                //console.log(data);
                //alert("Somethng went wrong, details not saved");
                $("#failedAlert").fadeTo(2000, 500).slideUp(500, function(){
                    $("#failedAlert").slideUp(500);
                });
            }
        }
    });
}

//show modal to create a new candidate
$('#createCandidateBtn').on('click', function (e) {
    document.getElementById("candidateForm").reset();
    document.getElementById("candidateRecordId").value = 0;
    $("#CandidateImgDisplay").html('');
    $('#candidate_modal').modal("show");
    $('#saveCandidateBtn').html("<i class='bi bi-plus-circle'></i> Create Candidate");
});

//handle candidate creation and edit
$('#saveCandidateBtn').on('click', function (e) {
    e.preventDefault();
    
    //var candidateID = $("#edit_candidate_name_dropdown").val();
    var electionID = $("#candidate_election_dropdown").val();
    var postionID = $("#candidate_position_dropdown").val();
    var recordID = $("#candidateRecordId").val();
    // Get form
    //var form = $("#candidateForm")[0];
    let myForm = document.getElementById('candidateForm');
    let data = new FormData(myForm);

    // Create an FormData object
   // var data = new FormData(form);

    var route;
    if(electionID > 0 && postionID > 0 && recordID > 0){
        route = "/edit-candidate";
        data.append("election_id", electionID);
        data.append("position_id", postionID);
        data.append("record_id", recordID);
    }else{
        route = "/create-candidate";
    }
    
    // disable the submit button
    $("#saveCandidateBtn").prop("disabled", true);
    
    $.ajax({
        url: route,
        enctype: "multipart/form-data",
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
            
            $("#saveCandidateBtn").prop("disabled", false);
            if(data.success){
                getElectionCandidates();
                
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert success');
                $('#alert-msg').html("<i class='fas fa-check-circle'></i> "+data.success);
                $('#custom-alert').show();
                
                document.getElementById("candidateForm").reset();
                document.getElementById("candidateRecordId").value = "";
                $("#candidate_modal").modal("hide");
                
            }

            if(data.error){
                mIsAlertOn = true;

                $('#custom-alert').addClass('custom-alert');
                $('#alert-msg').html("<i class='fas fa-times-circle'></i> "+data.error);
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
                $('#canFormValErr').append('<p> * ' + value + '</p>');
                $("#canFormValErr").removeClass('d-none');
                document.getElementById("canFormValErr").scrollIntoView({behavior: 'smooth'});
            });

            mIsAlertOn = true;

            $('#custom-alert').addClass('custom-alert');
            $('#alert-msg').html("<i class='fas fa-x'></i> "+responseHtml);
            $('#custom-alert').show();

            $("#saveCandidateBtn").prop("disabled", false);
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

//populate the elections dropdown dropdown
var cur_drpdwn = $("#elections_dropdown");
    $.get("/elections-dropdown", function(data) {
        //console.log(data);
        $.each(data, function(index,item) {
        cur_drpdwn.append($("<option />").val(item.id).text(item.name));
    });
});

var ele_drpdwn = $("#edit_elections_dropdown");
    $.get("/elections-dropdown", function(data) {
        //console.log(data);
        $.each(data, function(index,item) {
        ele_drpdwn.append($("<option />").val(item.id).text(item.name));
    });
});

//get the elections posts
function getElectionPosts(){
    //display the posts created
    if ($.fn.DataTable.isDataTable('#posts_table')) {
        $('#posts_table').DataTable().destroy();
    }
    $('#posts_table tbody').empty();
    $('#posts_table').DataTable({
        //responsive: true,
        processing: true,
        //serverSide: true,
        order: [],
        ajax: {
            url: "/posts/table-list",
            dataSrc: function (json) {
                //console.log(json.length);
              var return_data = new Array();
              for(var i=0;i< json.length; i++){
                return_data.push({
                  action: displayPostsActionButtons(json[i].id),
                  post_name: json[i].post_name,
                  election_name: json[i].election_name,
                  description: json[i].description,
                });
                
              }
              return return_data;
            }
        },
        columns: [
        {data: 'post_name'},
        {data: 'election_name'},
        {data: 'description'},
        {data: 'action', orderable: false, searchable: false}
        ]
    });
}

  //display posts table action buttons
function displayPostsActionButtons(postId){

    var actions =  "<div class='btn-toolbar'> <div> "+
    "<a href='#' class='btn btn-xs btn-outline-dark btn-icon' onclick='getPostDetails("+postId+")' id='edit-post' data-id='"+postId+"'> <i class='bi bi-pencil-square'></i> </a> "+
    "<a href='#' class='btn btn-xs btn-outline-danger btn-icon' onclick='confirmPostDelete("+postId+")' id='delete-post' data-id='"+postId+"'> <i class='bi bi-trash'></i> </a>"+
    "</div></div>";

    return actions;
}

//get the election details for the user to edit
function getPostDetails(postId){
    var postName = document.getElementById("postName");
    var postDesc = document.getElementById("postDescription");
    $("#postsFormValErr").addClass('d-none');

    $.get("/edit-post-details/"+postId, function(data) {
        if(data !== null){
            document.getElementById("postsForm").reset();
            document.getElementById("postId").value = postId;

            postName.value = data.post_name;
            postDesc.value = data.description;
            $('#elections_dropdown').val(data.election_id).change();

            $('#savePostBtn').html("<i class='bi bi-save-fill'></i> Save");
            $('#posts_modal').modal("show");
            
        }
    });
}

$('#createPostBtn').on('click', function (e) {
    document.getElementById("postsForm").reset();
    document.getElementById("postId").value = 0;
    $('#posts_modal').modal("show");
    $('#savePostBtn').html("<i class='bi bi-plus-circle'></i> Create Post");
});

//handle create / edit positions form submition
$('#savePostBtn').on('click', function (e) {
    e.preventDefault();

    let myForm = document.getElementById('postsForm');
    let data = new FormData(myForm);

    // Get form
    //var form = $("#postsForm")[0];

    // Create an FormData object
    //var data = new FormData(form);
    
    var postId = $("#postId").val();
    
    var route;
    if(postId > 0){
        route = "/save-edit-post-details/"+postId;
        data.append("postId", postId);
    }else{
        route = "/create-position";
    }
    
    // disable the submit button
    $("#savePostBtn").prop("disabled", true);
    
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
            $("#savePostBtn").prop("disabled", false);
            if(data.success){
                mIsAlertOn = true;
                $('#custom-alert').addClass('custom-alert success');
                $('#alert-msg').html("<i class='fas fa-check-circle'></i> "+data.success);
                $('#custom-alert').show();

                getElectionPosts();
                document.getElementById("postsForm").reset();
                document.getElementById("postId").value = 0;
                $("#posts_modal").modal("hide");
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
                $('#postsFormValErr').append('<p> * ' + value + '</p>');
                $("#postsFormValErr").removeClass('d-none');
                document.getElementById("postsFormValErr").scrollIntoView();
            });

            mIsAlertOn = true;

            $('#custom-alert').addClass('custom-alert');
            $('#alert-msg').html("<i class='fas fa-x'></i> "+responseHtml);
            $('#custom-alert').show();

          $("#savePostBtn").prop("disabled", false);
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

//confirm deletion of a post
function confirmPostDelete(postId){
    $('#delete_post_modal').modal("show");

    document.getElementById("deletePostConfirmText").innerHTML = "Confirm delete of this position";

    $('#deletePostBtn').click(function(){
        deletePost(postId);
  });
}

//deletion of a post
function deletePost(postId){

    $.ajax({
        url: "/delete-post",
        type: 'post',
        //dataType: 'json',
        data: {
            _token: CSRF_TOKEN,
            post_id: postId
        },
        success: function(data) {
            if(data.success){
                mIsAlertOn = true;
                $('#custom-alert').addClass('custom-alert success');
                $('#alert-msg').html("<i class='fas fa-check-circle'></i> "+data.success);
                $('#custom-alert').show();

                $('#delete_post_modal').modal("hide");
                getElectionPosts();
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

//the select for the candidates when adding a candidate to an election
$('#candidate_name_dropdown').select2({
    placeholder: 'Select Candidate',
    searchInputPlaceholder: 'Search candidate',
    ajax: {
        url: '/election-candidates-list',
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
        },
        cache: true
    }
});

//the select for the candidates when editing a candidate to an election
$('#edit_candidate_name_dropdown').select2({
    placeholder: 'Select Candidate',
    searchInputPlaceholder: 'Search candidate',
    ajax: {
        url: '/election-candidates-list',
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
        },
        cache: true
    }
});

//the select for the elections when adding a candidate to a election
$('#candidate_election_dropdown').select2({
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
                        //slug: item.slug,
                        id: item.id
                    };
                })
            };
        },
        cache: true
    }
});

//position dropdown when adding a candidate
$('#candidate_position_dropdown').select2({
    placeholder: 'Select position',
    searchInputPlaceholder: 'Search position',
    ajax: {
        url: '/positions-dropdown',
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
                        //slug: item.slug,
                        id: item.id
                    };
                })
            };
        },
        cache: true
    }
});

//get the id of the selected election id 
//use it to get the positions in that election
function getSelectedElection(event){
    var electonId = event.target.value;
    
    //unselect previously selected
    $('#candidate_position_dropdown').val(0);
    
    $('#candidate_position_dropdown').select2({
      placeholder: 'Select position',
      searchInputPlaceholder: 'Search position',
      //minimumInputLength: 2,
        //tags: [],
        ajax: {
            url: "/positions-dropdown/"+electonId,
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

//get the id of the selected election id 
//use it to get the positions in that election
function getSelectedElectionEdit(event){
    var electonId = event.target.value;
    console.log('election id', electonId);
    
    //unselect previously selected
    $('#edit_candidate_position_dropdown').val(0);
    
    $('#edit_candidate_position_dropdown').select2({
      placeholder: 'Select position',
      searchInputPlaceholder: 'Search position',
      //minimumInputLength: 2,
        //tags: [],
        ajax: {
            url: "/positions-dropdown/"+electonId,
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
