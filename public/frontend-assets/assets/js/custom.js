$(document).ready(function(){
    $(window).scroll(function() {   
        var scroll = $(window).scrollTop();
        if (scroll > 10) {
            $("header").addClass("scrolled");
        } else {
            $("header").removeClass("scrolled");
        }
    });
});

var current_ff, next_ff, previous_ff;
var opacity;
var current = 0;
var steps = $(".steps").length;
console.log("steps " + steps);

setProgressBar(current);

function setProgressBar(curStep){
    var percent = parseFloat(100 / (steps-1)) * curStep;
    percent = percent.toFixed();
    $(".progress-bar").css("width",percent+"%");
}

$(".next-form").click(function() {

    current_ff = $(this).parent().parent().parent();
    next_ff = current_ff.next();
  
    //Add Class Active
    $(".step-list li").eq($(".tab-pannel").index(current_ff)).addClass("completed");
    $(".step-list li").eq($(".tab-pannel").index(next_ff)).addClass("active");
  
    //show the next steps
    next_ff.show();
    //hide the current steps with style
    current_ff.animate({opacity: 0}, {
      step: function(now) {
      // for making fielset appear animation
      opacity = 1 - now;
  
      current_ff.css({
        'display': 'none',
        'position': 'relative'
      });
      next_ff.css({'opacity': opacity});
    }, duration: 500
    });
    setProgressBar(++current);
});

$(".previous").click(function(){

    current_ff = $(this).parent().parent().parent();
    previous_ff = current_ff.prev();
    
    //Remove class active
    $(".step-list li").eq($(".tab-pannel").index(current_ff)).removeClass("active");
    $(".step-list li").eq($(".tab-pannel").index(previous_ff)).removeClass("completed");
    
    //show the previous fieldset
    previous_ff.show();
    
    //hide the current fieldset with style
    current_ff.animate({opacity: 0}, {
        step: function(now) {
        // for making fielset appear animation
        opacity = 1 - now;
    
        current_ff.css({
        'display': 'none',
        'position': 'relative'
        });
        previous_ff.css({'opacity': opacity}); 
    }, duration: 500});
    setProgressBar(--current);
});

$("input[name='knowanyone']").click(function() {
    var inputval = $(this).val();
    if(inputval == "Yes") {
        $("#mentionReferrence").addClass('show');
    } else {
        $("#mentionReferrence").removeClass('show');
    }
});