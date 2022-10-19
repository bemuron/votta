@extends('layouts.app')

@section('content')

<div class="breadcrumbs d-flex align-items-center" style="background-image: url('{{ asset('images/img/ongoing-elections.jpg' ) }}');">
  <div class="container position-relative d-flex flex-column align-items-center">

    <h2>Change Password</h2>
  </div>
</div>

<section id="contact" class="contact">
      <div class="container position-relative" data-aos="fade-up">

        <div class="row gy-4 justify-content-center">

             @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
            @endif

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">

            <form action="{{ route('changepassword') }}" method="post" role="form" class="php-email-form">
             @csrf
                <div class="form-group mt-3">
                    <input type="password" class="form-control @error('current-password') is-invalid @enderror" name="current-password" id="current-password" placeholder="Current password" required>
                    @error('current-password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mt-3">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="New password" required>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              
                <div class="form-group mt-3">
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm new password" required>
                </div>
                
              <div class="text-center"><button type="submit">Change Password</button></div>
            </form>

          </div>

        </div>

      </div>
    </section><!-- End Contact Section -->
@endsection
