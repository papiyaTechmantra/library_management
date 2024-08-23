/* When user scrolls down, hide the navbar. When user scrolls up, show the navbar */
var prevScrollpos = window.pageYOffset;
document.getElementById("onscroll-nav").style.top = "0";

if ($(window).width() > 992) {
    window.onscroll = function () {
        var currentScrollPos = window.pageYOffset;
        var lastScrollTop = 0;
        if (prevScrollpos > currentScrollPos) {
            document.getElementById("onscroll-nav").style.top = "0";
            document.getElementById("onscroll-nav").style.borderBottom = "1px solid #e4e4e4";
            document.getElementById("onscroll-nav").style.boxShadow = "0 0 0.125rem rgba(0, 0, 0, 0.125)";
            // document.getElementById("quick_container").style.top = "52px";
            // document.getElementsByClassName("product_image__holder")[0].style.top = "63px";
        } else {
            document.getElementById("onscroll-nav").style.top = "-67px";
            // document.getElementById("quick_container").style.top = "-1px";
            // document.getElementsByClassName("product_image__holder")[0].style.top = "10px";
        }
        // checking if scroll position is 15 to top
        if (currentScrollPos < 15) {
            document.getElementById("onscroll-nav").style.borderBottom = "1px solid transparent";
            document.getElementById("onscroll-nav").style.boxShadow = "0 0 0 rgba(0, 0, 0, 0)";
        }
        // checking if scrolled down
        if (currentScrollPos > lastScrollTop) {
            document.getElementById("onscroll-nav").style.borderBottom = "1px solid #e4e4e4";
            document.getElementById("onscroll-nav").style.boxShadow = "0 0 0.125rem rgba(0, 0, 0, 0.125)";
        }
        prevScrollpos = currentScrollPos;
        // lastScrollTop = currentScrollPos <= 0 ? 0 : currentScrollPos; // For Mobile or negative scrolling
    }
} else {
    window.onscroll = function () {
        var currentScrollPos = window.pageYOffset;
        var lastScrollTop = 0;
        if (prevScrollpos > currentScrollPos) {
            document.getElementById("onscroll-nav").style.top = "0";
            document.getElementById("onscroll-nav").style.borderBottom = "1px solid #e4e4e4";
            document.getElementById("onscroll-nav").style.boxShadow = "0 0 0.125rem rgba(0, 0, 0, 0.125)";
            // document.getElementById("quick_container").style.top = "80px";
            // document.getElementsByClassName("product_image__holder")[0].style.top = "63px";
        } else {
            document.getElementById("onscroll-nav").style.top = "-38px";
            // document.getElementById("quick_container").style.top = "38px";
            // document.getElementsByClassName("product_image__holder")[0].style.top = "10px";
        }
        // checking if scroll position is 15 to top
        if (currentScrollPos < 15) {
            document.getElementById("onscroll-nav").style.borderBottom = "1px solid transparent";
            document.getElementById("onscroll-nav").style.boxShadow = "0 0 0 rgba(0, 0, 0, 0)";
        }
        // checking if scrolled down
        if (currentScrollPos > lastScrollTop) {
            document.getElementById("onscroll-nav").style.borderBottom = "1px solid #e4e4e4";
            document.getElementById("onscroll-nav").style.boxShadow = "0 0 0.125rem rgba(0, 0, 0, 0.125)";
        }
        prevScrollpos = currentScrollPos;
        // lastScrollTop = currentScrollPos <= 0 ? 0 : currentScrollPos; // For Mobile or negative scrolling
    }
}

/* When user clicks on search bar, search result shows */
$('#search-bar').on('click', function () {
    $("#search-result-holder").show();
});


/* When user clicks outside search result box, it disappears */
$(document).mouseup(function (e) {
    var searchHolder = $("#search-result-holder");
    // if the target of the click isn't the container nor a descendant of the container
    if (!searchHolder.is(e.target) && searchHolder.has(e.target).length === 0) {
        searchHolder.hide();
    }
});