jQuery(document).ready(function ($) {
    $(".slider-full__list").slick({
        speed: 1000,
        infinite: true,
        arrows: false,
        // autoplay: true,
        // autoplaySpeed: 7500,
        dots: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        responsive: [{

            breakpoint: 768,
            settings: {
                adaptiveHeight: true
            }

        }]
    });

    $(".cards-list__list").each(function(){
        $(this).slick({
            dots: false,
            arrows: false,
            mobileFirst: true,
            speed: 1000,
            infinite: true,
            arrows: false,
            // autoplay: true,
            // autoplaySpeed: 7500,
            slidesToShow: 1,
            slidesToScroll: 1,
            responsive: [{
    
                breakpoint: 565,
                settings: {
                    speed: 1000,
                    infinite: true,
                    arrows: false,
                    // autoplay: true,
                    // autoplaySpeed: 7500,
                    dots: false,
                    slidesToShow: 2,
                    slidesToScroll: 1,
                }
    
            },{
    
                breakpoint: 991,
                settings: "unslick"
    
            },
        ]
        });
    });

});


