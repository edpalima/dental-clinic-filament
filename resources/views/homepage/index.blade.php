@extends('layout')
@section('content')
    <!-- ======= Hero Section ======= -->
    <section id="hero">
        <div id="heroCarousel" data-bs-interval="5000" class="carousel slide carousel-fade" data-bs-ride="carousel">

            <ol class="carousel-indicators" id="hero-carousel-indicators"></ol>

            <div class="carousel-inner" role="listbox">

                <!-- Slide 1 -->
                <div class="carousel-item active"
                    style="background-image: url({{ URL::to('assets/img/slide/bgs.jpg') }})">
                    <div class="container">
                        <h2>Smile Brighter, Live Better</h2>
                        <p>At Almoro Santiago Dental Clinic, we're dedicated to brightening smiles and improving lives.
                            With personalized care and advanced treatments, we'll help you achieve optimal oral health
                            and confidence in your smile.</p>
                        <a href="{{ route('register') }}" class="btn-get-started scrollto">Make An Appointment</a>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item" style="background-image: url({{ URL::to('assets/img/slide/bgss.jpg') }})">
                    <div class="container">
                        <h2>Our Passion, Your Smile</h2>
                        <p>Almoro Santiago Dental Clinic is committed to making your smile our top priority. Our
                            experienced team provides compassionate care and innovative treatments tailored to your
                            unique needs.</p>
                        <a href="{{ route('register') }}" class="btn-get-started scrollto">Make An Appointment</a>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item" style="background-image: url({{ URL::to('assets/img/slide/bgssss.jpg') }})">
                    <div class="container">
                        <h2>Crafting Radiant Smiles, Every Day.</h2>
                        <p>At Almoro Santiago Dental Clinic, we specialize in crafting radiant smiles that light up your
                            life. From routine care to cosmetic enhancements, trust us to keep your smile healthy and
                            beautiful.</p>
                        <a href="{{ route('register') }}" class="btn-get-started scrollto">Make An Appointment</a>
                    </div>
                </div>

            </div>

            <a class="carousel-control-prev" href="#heroCarousel" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
            </a>

            <a class="carousel-control-next" href="#heroCarousel" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
            </a>

        </div>
    </section><!-- End Hero -->

    <main id="main">
        <!-- ======= Announcement Section ======= -->
        <div class="container my-5">
            @if ($announcements->count())
                <h1 class="text-center mb-4">Announcements</h1>
                <div id="announcementCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($announcements->chunk(3) as $index => $announcementChunk)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <div class="row">
                                    @foreach ($announcementChunk as $announcement)
                                        <div class="col-md-4">
                                            <div class="card">
                                                @if ($announcement->image_path)
                                                    <img src="{{ asset('storage/' . $announcement->image_path) }}"
                                                        class="card-img-top" alt="{{ $announcement->title }}">
                                                @endif
                                                <div class="card-body">
                                                    <h5 class="card-title d-flex justify-content-center">{{ $announcement->title }}</h5>
                                                    <p class="card-text">{{ $announcement->description }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#announcementCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#announcementCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            @endif
        </div>

        <!-- End Announcement Section -->

        <!-- ======= Featured Services Section ======= -->
        <section id="featured-services" class="featured-services">
            <div class="container" data-aos="fade-up">

                <h1 class="text-center mb-4">Services</h1>
                <div class="row">
                    <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
                        <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
                            <div class="icon"><i class="fas fa-heartbeat"></i></div>
                            <h4 class="title"><a href="">Personalized Care</a></h4>
                            <p class="description">Tailored treatments for your unique smile.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
                        <div class="icon-box" data-aos="fade-up" data-aos-delay="200">
                            <div class="icon"><i class="fas fa-pills"></i></div>
                            <h4 class="title"><a href="">Cutting-Edge Technology</a></h4>
                            <p class="description">Precise care with advanced tools</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
                        <div class="icon-box" data-aos="fade-up" data-aos-delay="300">
                            <div class="icon"><i class="fas fa-thermometer"></i></div>
                            <h4 class="title"><a href="">Experienced Team</a></h4>
                            <p class="description">Trust our skilled dentists for top-quality results.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
                        <div class="icon-box" data-aos="fade-up" data-aos-delay="400">
                            <div class="icon"><i class="fas fa-dna"></i></div>
                            <h4 class="title"><a href="">Patient Education</a></h4>
                            <p class="description">Empowering you with knowledge for a healthy smile.</p>
                        </div>
                    </div>

                </div>

            </div>
        </section><!-- End Featured Services Section -->

        <!-- ======= Cta Section ======= -->
        <section id="cta" class="cta">
            <div class="container" data-aos="zoom-in">

                <div class="text-center">
                    <h3>In an emergency? Need help now?</h3>
                    <p> We're here for you. Contact Almoro Santiago Dental Clinic immediately for urgent dental care.
                        Your health and comfort are our top priorities.</p>
                    <a class="cta-btn scrollto" href="{{ route('register') }}">Make an Appointment</a>
                </div>

            </div>
        </section><!-- End Cta Section -->

        <!-- ======= About Us Section ======= -->
        <section id="about" class="about">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h2>About Us</h2>
                    <p>Welcome to Almoro Santiago Dental Clinic, where your smile is our passion and your dental health
                        is our priority. With a commitment to excellence and compassion, our clinic has been proudly
                        serving the community for 15 years. Our team of dedicated dentists brings a wealth of
                        experience and expertise to every appointment.</p>
                </div>

                <div class="row">
                    <div class="col-lg-6" data-aos="fade-right">
                        <img src="{{ URL::to('assets/img/about.jpg') }}" class="img-fluid" alt="">
                    </div>
                    <div class="col-lg-6 pt-4 pt-lg-0 content" data-aos="fade-left">
                        <h3>Why Choose Us?</h3>
                        <ul>
                            <li><i class="bi bi-check-circle"></i> Experienced Team: Our team of skilled dentists has
                                years of experience and training in the latest dental techniques.</li>
                            <li><i class="bi bi-check-circle"></i> State-of-the-Art Facilities: We invest in advanced
                                technology and equipment to ensure precise diagnosis and comfortable treatment.</li>
                            <li><i class="bi bi-check-circle"></i> Compassionate Care: Your comfort and satisfaction
                                are our top priorities. We strive to create a positive dental experience for every
                                patient.</li>
                            <li><i class="bi bi-check-circle"></i> Patient Education: We believe in empowering our
                                patients with knowledge and resources to make informed decisions about their dental
                                health.</li>
                        </ul>
                        <p>
                            Ready to experience the difference at Almoro Santiago Dental Clinic? Contact us today to
                            schedule your appointment. We look forward to welcoming you to our dental family!
                        </p>
                    </div>
                </div>

            </div>
        </section><!-- End About Us Section -->

        <!-- ======= Services Section ======= -->
        <section id="services" class="services services">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h2>Services</h2>
                    <p>Our dental clinic offers comprehensive appointment services, including routine check-ups, cleanings,
                        orthodontic consultations, and specialized treatments, ensuring convenient scheduling and
                        personalized care for every patient.</p>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-6 icon-box" data-aos="zoom-in" data-aos-delay="100">
                        <div class="icon"><i class="fas fa-heartbeat"></i></div>
                        <h4 class="title"><a href="">Routine Check-ups and Cleanings</a></h4>
                        <p class="description">Ensure your oral health with regular check-ups and professional
                            cleanings to prevent dental issues and maintain a healthy smile.</p>
                    </div>
                    <div class="col-lg-4 col-md-6 icon-box" data-aos="zoom-in" data-aos-delay="200">
                        <div class="icon"><i class="fas fa-pills"></i></div>
                        <h4 class="title"><a href="">Cosmetic Dentistry</a></h4>
                        <p class="description">Repair and restore damaged teeth with restorative treatments including
                            fillings, crowns, bridges, and dental implants to regain full functionality and aesthetics.
                        </p>
                    </div>
                    <div class="col-lg-4 col-md-6 icon-box" data-aos="zoom-in" data-aos-delay="300">
                        <div class="icon"><i class="fas fa-hospital-user"></i></div>
                        <h4 class="title"><a href="">Orthodontic Treatment</a></h4>
                        <p class="description">Straighten your teeth and correct bite issues with orthodontic
                            treatments such as traditional braces or clear aligners for a straighter, more aligned
                            smile.</p>
                    </div>
                    <div class="col-lg-4 col-md-6 icon-box" data-aos="zoom-in" data-aos-delay="100">
                        <div class="icon"><i class="fas fa-dna"></i></div>
                        <h4 class="title"><a href="">Oral Surgery</a></h4>
                        <p class="description">Address complex dental issues with oral surgery procedures including
                            wisdom tooth extraction, dental implants, and jaw surgery to restore oral health and
                            function.</p>
                    </div>
                    <div class="col-lg-4 col-md-6 icon-box" data-aos="zoom-in" data-aos-delay="200">
                        <div class="icon"><i class="fas fa-wheelchair"></i></div>
                        <h4 class="title"><a href="">Emergency Dental Care</a></h4>
                        <p class="description">Receive prompt attention and relief for dental emergencies such as
                            severe toothaches, broken teeth, or dental trauma with our emergency dental care services.
                        </p>
                    </div>
                    <div class="col-lg-4 col-md-6 icon-box" data-aos="zoom-in" data-aos-delay="300">
                        <div class="icon"><i class="fas fa-notes-medical"></i></div>
                        <h4 class="title"><a href="">Pediatric Dentistry</a></h4>
                        <p class="description">Ensure the oral health of your children with specialized pediatric
                            dental care, including preventive treatments, early intervention for orthodontic issues, and
                            education on proper oral hygiene habits for lifelong dental health.</p>
                    </div>
                </div>

            </div>
        </section><!-- End Services Section -->

        <!-- ======= Contact Section ======= -->
        <section id="contact" class="contact" style="padding-bottom:0px">
            <div class="container">

                <div class="section-title">
                    <h2>Location</h2>
                    <p>For appointments or inquiries, please contact Almoro Santiago Dental Clinic at 0998993833 or
                        email us at almorosantiago.dentalclinic@gmail.com, located at Canlalay Biñan City of Laguna.</p>
                </div>

            </div>

            <div>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d247393.04463302746!2d120.90757997812496!3d14.339504699999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d750a2910779%3A0xa8e2b165b39642d7!2sAlmoro%20Santiago%20Dental%20Clinic!5e0!3m2!1sen!2sph!4v1713685835846!5m2!1sen!2sph"
                    width="100%" height="600" style="border:0;" allowfullscreen="" loading="lazy"
                    class="rounded-lg shadow-md"></iframe>
            </div>
        </section><!-- End Contact Section -->

    </main><!-- End #main -->
@endsection
