/*--------------------------*/


$(window).scroll(function () {
    if ($(window).scrollTop() >= 300) {
        $('.vs-header').addClass('fixed-header');
        $('.vs-header').addClass('visible-title');
    } else {
        $('.vs-header').removeClass('fixed-header');
        $('.vs-header').removeClass('visible-title');
    }
});


/*--------------------*/


var timelineSwiper = new Swiper('.timeline .swiper-container', {
    direction: 'vertical',
    loop: true,
    speed: 2000,
    pagination: '.swiper-pagination',
    paginationBulletRender: function (swiper, index, className) {
        var year = document.querySelectorAll('.swiper-slide')[index].getAttribute('data-year');
        return '<span class="' + className + '">' + year + '</span>';
    },
    paginationClickable: true,
    nextButton: '.swiper-button-next',
    prevButton: '.swiper-button-prev',
    breakpoints: {
        768: {
            direction: 'horizontal',
        }
    },
});

/*----------------------------------*/

(function ($) {
    $.fn.timeline = function () {
        var selectors = {
            id: $(this),
            item: $(this).find(".timeline-item"),
            activeClass: "timeline-item--active",
            img: ".timeline__img"
        };
        selectors.item.eq(0).addClass(selectors.activeClass);
        selectors.id.css(
            "background-image",
            "url(" +
            selectors.item
            .first()
            .find(selectors.img)
            .attr("src") +
            ")"
        );
        var itemLength = selectors.item.length;
        $(window).scroll(function () {
            var max, min;
            var pos = $(this).scrollTop();
            selectors.item.each(function (i) {
                min = $(this).offset().top;
                max = $(this).height() + $(this).offset().top;
                var that = $(this);
                if (i == itemLength - 2 && pos > min + $(this).height() / 2) {
                    selectors.item.removeClass(selectors.activeClass);
                    selectors.id.css(
                        "background-image",
                        "url(" +
                        selectors.item
                        .last()
                        .find(selectors.img)
                        .attr("src") +
                        ")"
                    );
                    selectors.item.last().addClass(selectors.activeClass);
                } else if (pos <= max - 40 && pos >= min) {
                    selectors.id.css(
                        "background-image",
                        "url(" +
                        $(this)
                        .find(selectors.img)
                        .attr("src") +
                        ")"
                    );
                    selectors.item.removeClass(selectors.activeClass);
                    $(this).addClass(selectors.activeClass);
                }
            });
        });
    };
})(jQuery);

$("#timeline-1").timeline();


/*---------------------*/

// var mySwiper = new Swiper('.product_slider', {
//     loop: true,
//     speed: 1500,
//     // autoplay: {
//     //     delay: 3000,
//     // },
//     effect: 'coverflow',
//     grabCursor: true,
//     centeredSlides: true,
//     slidesPerView: 'auto',
//     coverflowEffect: {
//         rotate: 0,
//         stretch: 0,
//         depth: 200,
//         modifier: 1,
//         slideShadows: false,
//     },

//     // Navigation arrows
//     navigation: {
//         nextEl: '.swiper-button-next',
//         prevEl: '.swiper-button-prev',
//     },

// })

/*---------------------*/

$("document").ready(function () {
    $(".tab-slider--body").hide();
    $(".tab-slider--body:first").show();
});

$(".tab-slider--nav li").click(function () {
    $(".tab-slider--body").hide();
    var activeTab = $(this).attr("rel");
    $("#" + activeTab).fadeIn();
    if ($(this).attr("rel") == "tab2") {
        $('.tab-slider--tabs').addClass('slide');
    } else {
        $('.tab-slider--tabs').removeClass('slide');
    }
    $(".tab-slider--nav li").removeClass("active");
    $(this).addClass("active");
});





/*-----------------------------*/


jQuery($ => {
    // The speed of the scroll in milliseconds
    const speed = 4500;

    $('a[href*="#"]')
        .filter((i, a) => a.getAttribute('href').startsWith('#') || a.href.startsWith(`${location.href}#`))
        .unbind('click.smoothScroll')
        .bind('click.smoothScroll', event => {
            const targetId = event.currentTarget.getAttribute('href').split('#')[1];
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                event.preventDefault();
                $('html, body').animate({
                    scrollTop: $(targetElement).offset().top
                }, speed);
            }
        });
});



/*--------------------*/


var counted = 0;
$(window).scroll(function () {

    var oTop = $('#counter').offset().top - window.innerHeight;
    if (counted == 0 && $(window).scrollTop() > oTop) {
        $('.count').each(function () {
            var $this = $(this),
                countTo = $this.attr('data-count');
            $({
                countNum: $this.text()
            }).animate({
                    countNum: countTo
                },

                {

                    duration: 2000,
                    easing: 'swing',
                    step: function () {
                        $this.text(Math.floor(this.countNum));
                    },
                    complete: function () {
                        $this.text(this.countNum);
                        //alert('finished');
                    }

                });
        });
        counted = 1;
    }

});


/*--------------Accordian---------------------*/

$(document).ready(function () {
    $('.accordion-list > li > .answer').hide();

    $('.accordion-list > li').click(function () {
        if ($(this).hasClass("active")) {
            $(this).removeClass("active").find(".answer").slideUp();
        } else {
            $(".accordion-list > li.active .answer").slideUp();
            $(".accordion-list > li.active").removeClass("active");
            $(this).addClass("active").find(".answer").slideDown();
        }
        return false;
    });

});


/*----------------------*/


var $cont = document.querySelector('.cont');
var $elsArr = [].slice.call(document.querySelectorAll('.el'));
var $closeBtnsArr = [].slice.call(document.querySelectorAll('.el__close-btn'));

setTimeout(function () {
    $cont.classList.remove('s--inactive');
}, 200);

$elsArr.forEach(function ($el) {
    $el.addEventListener('click', function () {
        if (this.classList.contains('s--active')) return;
        $cont.classList.add('s--el-active');
        this.classList.add('s--active');
    });
});

$closeBtnsArr.forEach(function ($btn) {
    $btn.addEventListener('click', function (e) {
        e.stopPropagation();
        $cont.classList.remove('s--el-active');
        document.querySelector('.el.s--active').classList.remove('s--active');
    });
});



! function ($) {

    "use strict";

    /**
     * Swiper slider - Timeline
     */
    var container = $('.timeline_product');

    var timelineContents = new Swiper('.timeline-contents', {
        navigation: {
            nextEl: '.timeline-button-next',
            prevEl: '.timeline-button-prev',
        },
        grabCursor: true,
        spaceBetween: 30,
        autoHeight: true,
        // autoplay: {
        // 	delay: (container.data('autoplay'))?parseInt(container.data('autoplay'), 10):7000,
        // },
        speed: (container.data('speed')) ? parseInt(container.data('speed'), 10) : 700,
    });
    var timelineDates = new Swiper('.timeline-dates', {
        spaceBetween: 100,
        centeredSlides: true,
        slidesPerView: 'auto',
        touchRatio: 0.2,
        slideToClickedSlide: true
    });
    timelineContents.controller.control = timelineDates;
    timelineDates.controller.control = timelineContents;

}(jQuery);
