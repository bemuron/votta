@extends('layouts.app')

@section('content')
<div class="breadcrumbs d-flex align-items-center" style="background-image: url('{{ asset('images/elections/'.$electionDetails->image_big ) }}');">
  <div class="container position-relative d-flex flex-column align-items-center">

    <h2>{{ $electionDetails->name }}</h2>
    <p class="text-white" >{{ $electionDetails->description }}</p>
    <p class="text-white" ><i class="bi bi-clock"></i> <time datetime="2022-01-01">{{ date_format(date_create($electionDetails->end_date), "d-M-Y") }}</time></p>
  </div>
</div>

<!-- <hr class="mg-t-50 mg-b-40"> -->

<div class="container mt-5">
  <h4>Candidates</h4>
  <section id="blog" class="blog p-0">
      <div class="container p-0" data-aos="fade-up">

      <div class="row gy-5 posts-list">
  @foreach( $electionCandidates as $candidate )

  <div class="col-lg-3">
    <article class="d-flex flex-column">

      <div class="post-img">
        <img src="{{ asset('images/candidates/'.$candidate->image ) }}" alt="" class="img-fluid">
      </div>

      <h2 class="title m-0">
      {{ $candidate->name }}
      </h2>
      <h6 class="m-0"><strong>Postion:</strong> {{ $candidate->post_name }}</h6>

      <div class="content m-0 p-0">
        <p>
        {{ $candidate->description }}
        </p>
      </div>

      <div class="read-more mt-auto align-self-start">
          <button type="button" id="{{ $candidate->id }}" class="btn btn-outline-info" onclick="getCandidateDetails( {{ $candidate->id }}, {{ $candidate->election_id }}, {{ $candidate->post_id }}, 1 )">
              {{ __(' View') }}
          </button>
          @guest
          @if (Route::has('login'))
          <a href="{{ route('login') }}" class="ps-2">Log in to vote</a>
          @endif
          @else
          <button type="button" class="btn btn-outline-success" id="votePromptBtn" onclick="getCandidateDetails( {{ $candidate->id }}, {{ $candidate->election_id }}, {{ $candidate->post_id }}, 2 )" > Vote <i class="bi bi-box-arrow-in-down"></i></button>
          @endguest
      </div>

    </article>
  </div>
    @endforeach
    </div><!-- End blog posts list -->

</div>
</section><!-- End Blog Section -->
</div>

<!-- modal to show the candidates details -->
<div class="modal fade" id="canDetailsModal" tabindex="-1" role="dialog" aria-labelledby="canModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content tx-14">
          <div class="modal-header">
            <h6 class="modal-title" id="canModalLabel">Candidate Details</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </button>
          </div>
          <div class="modal-body">
            <div class="d-flex p-3">
              <div id="canImg"></div>
              <div class="flex-column ps-3">
                <div class="d-flex">
                  <span class="tx-medium tx-info pe-2"><i class="fa fa-id-card"></i></span> <p id="canName"></p>
                </div>
                <div class="d-flex">
                  <span class="tx-medium tx-info pe-2"><i class="fa fa-check-square"></i></span> <p id="canPost"></p>
                </div>
                <div class="d-flex">
                  <span class="tx-medium tx-info pe-2"><i class="fa fa-info-circle"></i></span> <p id="canDets"></p>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- modal to confirm with user if they want to vote -->
<div class="modal fade" id="confirmVoteModal" tabindex="-1" role="dialog" aria-labelledby="confirmVoteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content tx-14">
      <div class="modal-header">
        <h6 class="modal-title" id="confirmVoteLabel">Confirmation</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </button>
      </div>
      <div class="modal-body">
        <p id="confirmText"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
        <button type="button" id="voteBtn"class="btn btn-success" data-bs-dismiss="modal" onclick="voteCandidate( {{ $candidate->id }}, {{ $candidate->election_id }}, {{ $candidate->post_id }} )">Vote <i class="bi bi-box-arrow-in-down"></i></button>
      </div>
    </div>
  </div>
</div>

    <!-- modal to inform user they already voted -->
<div class="modal fade" id="alreadyVotedModal" tabindex="-1" role="dialog" aria-labelledby="alreadyVotedLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content tx-14">
      <div class="modal-header">
        <h6 class="modal-title" id="alreadyVotedLabel">You already voted</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </button>
      </div>
      <div class="modal-body">
        <p id="alreadyVotedText"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- Alert message for user to show vote cast successfully-->
<div id="voteSuccessAlert" class="modal alert-success h-auto  fade show alert-dismissible" role="document">
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  <br>
  <p class="fs-4 text-center"><strong>Success!</strong> Your vote has been recieved.</p>
  <br>
</div>

<!-- Alert message for user to show vote not cast-->
<div id="voteFailedAlert" class="modal alert-danger h-auto fade show alert-dismissible" role="document">
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  <br>
  <p class="fs-4 text-center"><strong>Danger!</strong> Your vote was not received.</p>
  <br>
</div>
@endsection
