//csrf token
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
var mElectionPosId = 0;
var mElectionId = 0;
var mCandidateId = 0;

//get the candidates's details and display
function getCandidateDetails(canId, electionId, clickedButton){
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
                //mElectionPosId = postId;
                mElectionId = electionId;
                mCandidateId = canId;
                confirmVoteText.innerHTML = "Confirm casting your vote for "+data.name+" for the position of "+data.post_name;
                $('#confirmVoteModal').modal("show");
            }
            
        }
    });
    //console.log('You are running jQuery version: ' + window.jQuery.fn.jquery);
    //console.log("jQuery "+ (jQuery ? $().jquery : "NOT") +" loaded");
}

//cast a vote for a candiatet
function voteCandidate(canId, electionId, postId){
    var alreadyVotedText = document.getElementById("alreadyVotedText");

    $.ajax({
        url: "/vote-candidate",
        type: 'post',
        //dataType: 'json',
        data: {
            _token: CSRF_TOKEN,
            candidate_id: canId,
            election_id: electionId,
            post_id: postId
        },
        success: function(data) {
            if(data == 1){
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
                alreadyVotedText.innerHTML = "You already voted "+data.name+" for the position of "+data.post_name;
                $('#alreadyVotedModal').modal("show");
                document.getElementById("votePromptBtn").innerHTML = 'You already voted';
                document.getElementById("votePromptBtn").disabled = true; 

            }else{
                //error occured
                $("#voteFailedAlert").fadeTo(2000, 500).slideUp(500, function(){
                    $("#voteFailedAlert").slideUp(500);
                });
            }

        }
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

//edit election dates picker
editElectFrom = $('#editElectDateFrom')
.datepicker({
    //defaultDate: '+1w',
    dateFormat: 'yy-mm-dd'
    //minDate: 0
    //numberOfMonths: 2
})
.on('change', function() {
    editElectTo.datepicker('option','minDate', getDate( this ) );
}),

editElectTo = $('#editElectDateTo').datepicker({
    //defaultDate: '+1w',
    dateFormat: 'yy-mm-dd'
    //numberOfMonths: 2
})
.on('change', function() {
    editElectFrom.datepicker('option','maxDate', getDate( this ) );
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

//populate the datatable with elections when the edit button is clicked
$('#edit-candidate-tab').click(function(){
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
});

  //populate the datatable with elections when the edit button is clicked
  $('#edit-election-tab').click(function(){
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
  });

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
    var actions =  "<a href='#edit_election_modal' class='btn btn-outline-dark' onclick='getElectionDetails("+electionId+")' id='edit-election' data-id='"+electionId+"'>Edit</a>"+
    "<a href='#delete_election_modal' class='btn btn-outline-danger' onclick='confirmElectionDelete("+electionId+")' id='delete-election' data-id='"+electionId+"' data-toggle='modal' data-animation='effect-scale'>Delete</a>";

    return actions;
}

//display action buttons for candidates list
function displayCandidatesActionButtons(candidateId){
    var actions =  "<div class='btn-toolbar'> <div> "+
    "<a href='#edit_candidate_modal' class='btn btn-xs btn-outline-dark btn-icon' onclick='getCandidateDetails("+candidateId+")' id='edit-candidate' data-id='"+candidateId+"'>Edit</a>"+
    "<a href='#delete_candidate_modal' class='btn btn-xs btn-outline-danger btn-icon' onclick='confirmCandidateDelete("+candidateId+")' id='delete-candidate' data-id='"+candidateId+"' data-toggle='modal' data-animation='effect-scale'>Delete</a>"+
    "</div></div>";

    return actions;
}

//get the election details for the user to edit
function getElectionDetails(electionId){
    var electionName = document.getElementById("editElectionName");
    var electionStart = document.getElementById("editElectDateFrom");
    var electionEnd = document.getElementById("editElectDateTo");
    var electionStatus = document.getElementById("editElectionStatus");
    var electionImage = document.getElementById("editElectionThumbImg");
    var electionBigImage = document.getElementById("editElectionBigImg");
    var electionDescription = document.getElementById("editElectionDescription");
    var imgpath = "images/elections/";
    var electionImg;
    var electionBigImg;
    $("#electFormValErr").addClass('d-none');

    $.get("/edit-election-details/"+electionId, function(data) {
        if(data !== null){
            mElectionId = electionId;
            electionImg = imgpath+data.image;
            electionBigImg = imgpath+data.image_big;
            electionName.value = data.name;
            electionStart.value = data.start_date;
            electionEnd.value = data.end_date;
            electionDescription.value = data.description;
            $('#editElectionStatus').val(data.status).change();

            $("#editThumbImg").html('<img src="' + electionImg + '" alt="Card image" width="200" height="200"/>');
            $("#editBigImg").html('<img src="' + electionBigImg + '" alt="Card image" width="200" height="200"/>');

            $('#edit_election_modal').modal("show");
            
        }
    });
}

//confirm deletion of an election
function confirmElectionDelete(electionId){
    $('#delete_election_modal').modal("show");

    document.getElementById("deleteElectionConfirmText").innerHTML = "Confirm delete of this election";

    //populate the posts table when the edit button is clicked
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

//save the election edits
function saveElectionEdit(){
    let myForm = document.getElementById('editElectionForm');
    let formData = new FormData(myForm);
    var electionName = document.getElementById("editElectionName").value;
    var electionStart = document.getElementById("editElectDateFrom").value;
    var electionEnd = document.getElementById("editElectDateTo").value;
    var electionStatus = document.getElementById("editElectionStatus").value;
    var electionImage = document.getElementById("editElectionThumbImg").value;
    var electionBigImage = document.getElementById("editElectionBigImg").value;
    var electionDescription = document.getElementById("editElectionDescription").value;

    $.ajax({
        url: "/save-edit-election-details/"+mElectionId,
        type: 'post',
        //dataType: 'json',
        data:formData,
        processData: false,
        contentType: false,
        success: function(data) {
            //console.log(data);
            if(data != null || data > 0){
                    mElectionId = 0;
                    $("#edit_election_modal").modal("hide");
                    document.getElementById('electFormValErr').reset();
                }else{
                    alert("Failed to save election");
            }
        },
        error: function(data){
            var errors = data.responseJSON;
            console.log(errors);
            $.each(errors, function (key, value) {
                $('#electFormValErr').append(value);
                $("#electFormValErr").removeClass('d-none');
                document.getElementById("electFormValErr").scrollIntoView();
                //console.log(key+" "+value.errors);
                
            });
          }
    });
}

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

//populate the posts table when the edit button is clicked
$('#edit-posts-tab').click(function(){
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
  });

  //display posts table action buttons
function displayPostsActionButtons(postId){
    var actions =  "<a href='#' class='btn btn-outline-success' onclick='getPostDetails("+postId+")' id='edit-post' data-id='"+postId+"'>Edit</a>"+
    "<a href='#delete_post_modal' class='btn btn-outline-danger' id='delete-post' data-id='"+postId+"' data-toggle='modal' data-animation='effect-scale'>Delete</a>";

    return actions;
}

//get the election details for the user to edit
function getPostDetails(postId){
    var postName = document.getElementById("editPostName");
    var postDesc = document.getElementById("editPostDescription");
    var electionId = $("#edit_elections_dropdown").children("option:selected").val();
    $("#postsFormValErr").addClass('d-none');

    $.get("/edit-post-details/"+postId, function(data) {
        if(data !== null){
            mElectionPosId = postId;

            postName.value = data.post_name;
            postDesc.value = data.description;
            $('#edit_elections_dropdown').val(data.election_id).change();

            $('#edit_posts_modal').modal("show");
            
        }
    });
}

//save the posts edits
function savePostEdit(){
    let myForm = document.getElementById('editPostsForm');
    let formData = new FormData(myForm);
    var post = document.getElementById("editPostName").value;
    var election = document.getElementById("edit_elections_dropdown").value;

    $.ajax({
        url: "/save-edit-post-details/"+mElectionPosId,
        type: 'post',
        //dataType: 'json',
        data:formData,
        processData: false,
        contentType: false,
        success: function(data) {
            //console.log(data);
            if(data != null || data > 0){
                    mElectionId = 0;
                    $("#edit_posts_modal").modal("hide");
                    document.getElementById('editPostsForm').reset();
                }else{
                    alert("Failed to save post");
            }
        },
        error: function(data){
            var errors = data.responseJSON;
            console.log(errors);
            $.each(errors, function (key, value) {
                $('#postsFormValErr').append(value);
                $("#postsFormValErr").removeClass('d-none');
                document.getElementById("postsFormValErr").scrollIntoView();
                //console.log(key+" "+value.errors);
                
            });
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
      placeholder: 'Select election',
      searchInputPlaceholder: 'Search election',
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
