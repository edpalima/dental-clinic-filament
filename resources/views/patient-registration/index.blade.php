{{-- @extends('components.layouts.app') --}}
@extends('layout')
@section('content')
    <!-- ======= Breadcrumbs Section ======= -->
    <section class="breadcrumbs">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center pt-4">
                <h2>Patient Registration</h2>
                <ol>
                    <li><a href="/">Home</a></li>
                    <li>Patient Registration</li>
                </ol>
            </div>
        </div>
    </section><!-- End Breadcrumbs Section -->

    <section class="inner-page">
        <div class="container">

            <livewire:patient-registration />
            {{-- <livewire:user-registration-form /> --}}

        </div>
    </section>

    {{-- <livewire:patient-register-form /> --}}
@endsection
