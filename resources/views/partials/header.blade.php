<!-- ======= Top Bar ======= -->
<div id="topbar" class="d-flex align-items-center fixed-top">
    <div class="container d-flex align-items-center justify-content-center justify-content-md-between">
        <div class="align-items-center d-none d-md-flex">
            <i class="bi bi-clock"></i> Monday - Sunday, 8AM to 5PM
        </div>
        <div class="d-flex align-items-center">
            <i class="bi bi-phone"></i> Call us now +63998993833
        </div>
    </div>
</div>

<!-- ======= Header ======= -->
<header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">

        <a href="/" class="logo me-auto"><img src="{{ URL::to('assets/img/logo.png') }}" alt=""></a>
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <h1 class="logo me-auto"><a href="index.html">Medicio</a></h1> -->

        <nav id="navbar" class="navbar order-last order-lg-0">
            <ul>
                <li><a class="nav-link scrollto " href="/#hero">Home</a></li>
                <li><a class="nav-link scrollto" href="/#about">About</a></li>
                <li><a class="nav-link scrollto" href="/#services">Services</a></li>
                <li><a class="nav-link scrollto" href="/#contact">Location</a></li>
                <li><a class="nav-link scrollto" href="/admin/login">Login</a></li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav><!-- .navbar -->

        <a href="{{ route('register') }}" class="appointment-btn scrollto"><span class="d-none d-md-inline">Make an</span>
            Appointment</a>

    </div>
</header>
<!-- End Header -->
