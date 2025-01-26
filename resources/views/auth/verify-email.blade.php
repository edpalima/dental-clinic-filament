@extends('layout')

@section('content')
    <!-- ======= Breadcrumbs Section ======= -->
    <section class="breadcrumbs bg-light py-4">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="text-dark">Email Verified</h2>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/" class="text-primary">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Email Verified</li>
                </ol>
            </div>
        </div>
    </section>
    <!-- End Breadcrumbs Section -->

    <div class="min-vh-80 d-flex align-items-center justify-content-center bg-light py-5">
        <div class="card shadow-lg p-4 border-0 rounded-lg" style="max-width: 500px; width: 100%;">
            <h3 class="card-title text-center mb-4 text-primary">Email Verification Sent!</h3>
            <div class="card-body p-4 bg-light">
                <div class="alert alert-info text-center mb-4" role="alert">
                    A verification email has been sent to your email address. Please check your inbox and click
                    the link to verify your account.
                </div>
                <div class="d-flex justify-content-center">
                  <a href="/admin" class="btn btn-primary">Go to Login</a>
                </div>
            </div>
        </div>
    </div>
@endsection
