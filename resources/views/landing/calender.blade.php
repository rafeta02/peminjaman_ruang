@extends('layouts.landing')
@section('styles')
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />
@endsection
@section('content')
<main id="main">

    <!-- ======= Features Section ======= -->
    <section class="hero-section inner-page">
        <div class="wave">

            <svg width="1920px" height="265px" viewBox="0 0 1920 265" version="1.1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink">
                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Apple-TV" transform="translate(0.000000, -402.000000)" fill="#FFFFFF">
                        <path
                            d="M0,439.134243 C175.04074,464.89273 327.944386,477.771974 458.710937,477.771974 C654.860765,477.771974 870.645295,442.632362 1205.9828,410.192501 C1429.54114,388.565926 1667.54687,411.092417 1920,477.771974 L1920,667 L1017.15166,667 L0,667 L0,439.134243 Z"
                            id="Path"></path>
                    </g>
                </g>
            </svg>

        </div>

        <div class="container">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="row justify-content-center">
                        <div class="col-md-7 text-center hero-text" style="margin-top:-30px !important">
                            <h1 data-aos="fade-up" data-aos-delay="">Kalender Peminjaman Ruang</h1>
                            <p class="mb-5" data-aos="fade-up" data-aos-delay="100">Peminjaman Ruang Di lingkungan Riset dan Inovasi Universitas Sebelas Maret.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <section class="site-section mb-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12 blog-content">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
      </section>

</main><!-- End #main -->
@endsection

@section('scripts')
@parent
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
<script>
    $(document).ready(function () {
        // page is now ready, initialize the calendar...
        events={!! json_encode($events) !!};
        $('#calendar').fullCalendar({
            // put your options and callbacks here
            events: events,
            defaultView: 'listWeek',
            timeFormat: 'H(:mm)'

        })
    });
</script>
@stop
