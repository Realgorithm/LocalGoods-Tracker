<style>
    /********************  Preloader Demo-4 *******************/
    #preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        opacity: 0.5;
        z-index: 1000;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #preloader .loader {
        background: #ccc;
        width: 40px;
        height: 40px;
        border-radius: 24px;
        /* display: inline-block;
  position: absolute; */
    }

    [data-bs-theme="dark"] #preloader {
        background: #212529;
        opacity: 0.5;
    }

    #preloader .loader-1 {
        animation: animateDot1 1.5s linear infinite;
        left: 130px;
        background: #f73138;
    }

    #preloader .loader-2 {
        background: #00b733;
        left: 60px;
        animation: animateDot2 1.5s linear infinite;
        animation-delay: 0.5s;
    }

    #preloader .loader-3 {
        background: #448afc;
        left: 130px;
        animation: animateDot3 1.5s linear infinite;
    }

    #preloader .loader-4 {
        background: #950faf;
        left: 60px;
        animation: animateDot4 1.5s linear infinite;
        animation-delay: 0.5s;
    }

    @keyframes animateDot1 {
        0% {
            transform: rotate(0) translateX(-60px);
        }

        25%,
        75% {
            transform: rotate(180deg) translateX(-60px);
        }

        100% {
            transform: rotate(360deg) translateX(-60px);
        }
    }

    @keyframes animateDot2 {
        0% {
            transform: rotate(0) translateX(-70px);
        }

        25%,
        75% {
            transform: rotate(-180deg) translateX(-70px);
        }

        100% {
            transform: rotate(-360deg) translateX(-70px);
        }
    }

    @keyframes animateDot3 {
        0% {
            transform: rotate(0) translateX(60px);
        }

        25%,
        75% {
            transform: rotate(180deg) translateX(60px);
        }

        100% {
            transform: rotate(360deg) translateX(60px);
        }
    }

    @keyframes animateDot4 {
        0% {
            transform: rotate(0) translateX(60px);
        }

        25%,
        75% {
            transform: rotate(-180deg) translateX(60px);
        }

        100% {
            transform: rotate(-360deg) translateX(60px);
        }
    }
</style>
<div id="preloader">
    <span class="loader loader-1"></span>
    <span class="loader loader-2"></span>
    <span class="loader loader-3"></span>
    <span class="loader loader-4"></span>
</div>

<script>
    window.start_load = function() {
        if (!$('#preloader2').length) {
            $('body').prepend('<div id="preloader2"><span class="loader loader-1"></span><span class="loader loader-2"></span><span class="loader loader-3"></span><span class="loader loader-4"></span></div>');
        }
    }

    window.end_load = function() {
        $('#preloader2').fadeOut('slow', function() {
            $(this).remove();
        });
    }

    $(document).ready(function() {
        window.start_load();
    });

    $(window).on('load', function() {
        $('#preloader').fadeOut('slow', function() {
            $(this).remove();
        });
        window.end_load();
    });

    // Show loader on navigation links
    $('a').on('click', function() {
        window.start_load();
    });

    // Show loader on form submissions
    $('form').on('submit', function() {
        window.start_load();
    });

    // Show loader on back/forward navigation
    window.addEventListener('popstate', function() {
        window.start_load();
    });
</script>