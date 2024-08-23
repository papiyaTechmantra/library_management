// const { values } = require("lodash");

// Enable tooltips everywhere
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})

// pincode loader image
const baseUrl = window.location.origin;
const pinLoaderImg = `<img src="${baseUrl}/images/loader/Rolling-1s-40px.gif" alt="pincode-loading" height="34">`;

// sweetalert toast
function toastFire(type = 'success', title) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'bottom',
        // timer: 2500,
        showConfirmButton: false,
        showCloseButton: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    Toast.fire({
        icon: type,
        title: title
    })
}

// delivery pincode button on navbar
$(document).on('click', '.userPincodeOpen', function() {
    $.ajax({
        url : baseUrl+'/api/pincode/status',
        beforeSend: function() {
            toastFire('info', 'Please wait...');
            // $('.pincode-loader').html(pinLoaderImg);
            // $('.savePincodeBar').attr('disabled', true).text('Please wait');
        },
        success: function(result) {
            if (result.status == 400) {
                var pincodeStatus = '<p>No pincode found. Add new address</p>';
                var newAddressContainer = 'show';
            } else {
                var pincodeStatus = '<p>Select your address or <a href="#addNewAddress" data-bs-toggle="collapse">Add new address</a></p>';
                var newAddressContainer = '';
            }            

            var modalBody = `
            <div class="row">
                <div class="col-12">${pincodeStatus}</div>
                <div class="col-12">
                    <div class="d-flex mb-3" style="overflow: hidden;overflow-x: auto;">`;

                $.each(result.data, (key, value) => {
                    const check = key == 0 ? 'checked' : '';
                    modalBody += `
                    <div class="radio-card me-3">
                        <input type="radio" name="selectedPincode" id="pin_${key}" ${check}>
                        <label for="pin_${key}">
                            <div class="card" style="width: max-content;">
                                <div class="card-body">
                                    <h5 class="card-title mb-0">${value.pincode}</h5>
                                    <p class="card-text review-title mb-0">${value.locality}</p>
                                    <p class="card-text"><small class="text-muted">${value.city}, ${value.state}</small></p>
                                </div>
                            </div>
                        </label>
                    </div>
                    `;
                });

                modalBody += `
                    </div>
                </div>
                <div class="col-12 collapse ${newAddressContainer}" id="addNewAddress">
                    <form action="${baseUrl}/api/pincode/store" method="post" id="pincodeBarForm">
                        <div class="form-floating mb-3">
                            <input type="number" class="pincode form-control form-control-sm" name="pincode" id="pincodeBar" placeholder="xxxxxx" autocomplete="none" autofocus>
                            <label for="pincode">Pincode</label>
                            <span class="pincode-loader"></span>
                        </div>
                        <div class="form-floating localityBar" style="display:none">
                            <select class="form-select form-select-sm locality" name="locality" id="locality"></select>
                            <label for="locality">Locality *</label>
                        </div>
                        <input type="hidden" name="city" class="city">
                        <input type="hidden" name="state" class="state">
                        <input type="hidden" name="country" class="country">
                        <p class="small mt-2" id="pincodeBarResp">Enter your delivery pincode</p>
                        <button type="submit" class="btn btn-primary savePincodeBar" style="display: none">Save Pincode</button>
                    </form>
                </div>
            </div>`;

            $('#pincodeModal .modal-title').text('Your delivery pincode');
            $('#pincodeModal .modal-body').html(modalBody);
            $('#pincodeModal').modal('show');

            // $('.savePincodeBar').attr('disabled', false).text('Save Pincode');
        }
    });
});

// delivery pincode modal on navbar, on show
/*
var addNewAddress = document.getElementById('addNewAddress')
addNewAddress.addEventListener('shown.bs.collapse', function () {
    document.querySelector('#pincodeBar').focus();
})

var pincodeModalEl = document.getElementById('pincodeModal')
pincodeModalEl.addEventListener('shown.bs.modal', function (event) {
    document.querySelector('#pincodeBar').focus();
})
*/

// delivery pincode modal on navbar data fetch
$(document).on('keyup', '#pincodeBar', function() {
    if ($('#pincodeBar').val().length == 6) {
        postalPinCode($('#pincodeBar').val());
    }
});

$(document).ready(function() {
    if ($('#pincodeBar').val().length == 6) {
        postalPinCode($('#pincodeBar').val());
    }

    if ($('input[name="pincode"]').val().length == 6) {
        postalPinCode($('input[name="pincode"]').val());
    }
});

// navbar pincode data upload
$(document).on('submit', '#pincodeBarForm', function(e) {
    e.preventDefault();
    $.ajax({
        url : $(this).attr('action'),
        method : $(this).attr('method'),
        data : {
            // token: $('input[name="_token"]').val(),
            pincode: $('.pincode').val(),
            locality: $('.locality').val(),
            city: $('.city').val(),
            state: $('.state').val(),
            country: $('.country').val(),
        },
        beforeSend: function() {
            toastFire('info', 'Please wait...');
            $('.pincode-loader').html(pinLoaderImg);
            $('.savePincodeBar').attr('disabled', true).text('Please wait');
        },
        success: function(result) {
            // console.log(result);
            if (result.status == 201) {
                $('#pincodeModal').modal('hide');
                $('.savePincodeBar').attr('disabled', false).text('Save Pincode');

                // cart/ checkout page
                if ($('.delivery-details').length > 0) {
                    var deliverContent = `
                    <div class="col-6"><p class="text-muted mb-0">Delivery</p></div>
                    <div class="col-6 text-end"><p class="mb-0"> FREE </p></div>
                    <div class="col-12">
                        <p class="small text-muted mb-0">
                            Delivery at <strong>`+$('.pincode').val()+`</strong>
                            <a href="javascript: void(0)" class="userPincodeOpen">Change?</a>
                        </p>
                    </div>
                    `;

                    $('.delivery-details').html(deliverContent);
                }
            } else {
                $('.pincode-loader').empty();
                $('#pincodeModal #pincodeBarResp').addClass('text-danger fw-600').text(result.message);
                $('.savePincodeBar').attr('disabled', false).text('Save Pincode');
            }
        }
    });
});

// fetch data from pincode
function postalPinCode(pin) {
    if (pin.length == 6) {
        $('.pincode').val(pin);
        $.ajax({
            url : 'https://api.postalpincode.in/pincode/'+pin,
            beforeSend: function() {
                toastFire('info', 'Please wait...');
                $('.pincode-loader').html(pinLoaderImg);
                $('button').attr('disabled', true);
                $('.locality').attr('disabled', true);
                $('.city').attr('disabled', true);
                $('.state').attr('disabled', true);
                $('.localityBar').hide();
                $('.savePincodeBar').hide();
            },
            success: function(result) {
                if (result[0].Status == "Success") {
                    $('.pincode-loader').empty();
                    $('.city').val(result[0].PostOffice[0].District).attr('disabled', false);
                    $('.state').val(result[0].PostOffice[0].State).attr('disabled', false);
                    $('.country').val(result[0].PostOffice[0].Country).attr('disabled', false);
                    $('.locality').attr('disabled', false);

                    var localityOptions = '';

                    $.each(result[0].PostOffice, (key, value) => {
                        localityOptions += `<option value="${value.Name}">${value.Name} (${value.Division})</option>`;
                    });
                    toastFire('success', 'Pincode details found');
                    $('.locality').html(localityOptions);
                    $('.localityBar').show();
                    $('.savePincodeBar').show();
                } else {
                    $('.pincode-loader').empty();
                    $('.city').attr('disabled', false);
                    $('.state').attr('disabled', false);
                    $('.locality').attr('disabled', false);
                    toastFire('error', 'Please enter valid pincode');
                }
                $('button').attr('disabled', false);
            }
        });
    }
}

// login
/*
$(document).on('submit', '#loginForm', function(e) {
    e.preventDefault();
    $.ajax({
        url : $(this).attr('action'),
        method : $(this).attr('method'),
        data : {
            _token: $('input[name="_token"]').val(),
            mobile_no: $('input[name="loginph_number"]').val(),
            password: $('input[name="login_password"]').val()
        },
        beforeSend: function() {
            $('#login_button').attr('disabled', true).text('Please wait');
            $('#switchTo_signup').attr('disabled', true);
        },
        success: function(result) {
            if (result.status == 200) {
                toastFire('success', result.message);
                $('#login__holder').html('<a href="user/profile" class="btn btn-sm btn-primary">Profile</a>');
                $('#loginModal').modal('hide');
            } else {
                toastFire('error', result.message);
            }
            $('#login_button').attr('disabled', false).text('Continue');
            $('#switchTo_signup').attr('disabled', false);
        }
    });
});
*/

// homepage banner
const homepageBanner = new Swiper('.homepage-banner', {
    pagination: {
        el: '.swiper-pagination',
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
});

// indian currency
function indianCurrency(x) {
    x=x.toString();
    var lastThree = x.substring(x.length-3);
    var otherNumbers = x.substring(0,x.length-3);
    if(otherNumbers != '')
        lastThree = ',' + lastThree;
    var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;
    return res;
}
function indianCurrencyDecimal(x) {
    x=x.toString();
    var afterPoint = '';
    if(x.indexOf('.') > 0)
       afterPoint = x.substring(x.indexOf('.'),x.length);
    x = Math.floor(x);
    x=x.toString();
    var lastThree = x.substring(x.length-3);
    var otherNumbers = x.substring(0,x.length-3);
    if(otherNumbers != '')
        lastThree = ',' + lastThree;
    var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;
    return res;
}

// add new address
$(document).on('submit', '#addressStoreForm', function(e) {
    e.preventDefault();
    $.ajax({
        url : $(this).attr('action'),
        method : $(this).attr('method'),
        data : {
            _token: $('input[name="_token"]').val(),
            redirect_url: $('input[name="redirect_url"]').val(),
            user_id: $('input[name="user_id"]').val(),
            full_name: $('input[name="full_name"]').val(),
            mobile_number: $('input[name="mobile_number"]').val(),
            email: $('input[name="email"]').val(),
            pincode: $('input[name="pincode"]').val(),
            landmark: $('input[name="landmark"]').val(),
            street_address: $('textarea[name="street_address"]').val(),
            locality: $('select[name="locality"]').val(),
            city: $('input[name="city"]').val(),
            state: $('input[name="state"]').val(),
            country: $('input[name="country"]').val(),
            type: $('input[name="type"]:checked').val()
        },
        beforeSend: function() {
            toastFire('info', 'Please wait...');
        },
        success: function(result) {
            if (result.status == 400) {
                toastFire('error', result.message);
            } else {
                toastFire('success', result.message);
                window.location = result.redirect_url;
                $('#addNewAddress').modal('hide');
            }
        }
    });
});

// add to cart
$(document).on('submit', '.addToCartForm', function(event) {
    event.preventDefault();
    // var formData = $(this).serialize();

    const userId = $('input[name="user_id"]').val();
    const productId = $('input[name="product_id"]').val();
    const addedToCartContent = `
    <a class="btn btn-primary add-cart" href="/cart">
        Go to Cart
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
    </a>
    `;

    $.ajax({
        url : $(this).attr('action'),
        method : $(this).attr('method'),
        data : {
            _token: $('input[name="_token"]').val(),
            user_id: userId,
            product_id: productId
        },
        beforeSend: function() {
            toastFire('info', 'Please wait...');
        },
        success: function(result) {
            if (result.status == 400) {
                toastFire('error', result.message);
            } else {
                if(result.token.length > 0) {
                    cookieStore.set({
                        name: "_cart-token",
                        value: result.token
                    });
                }
                toastFire('success', result.message);
                $('#cartCountShow').html('<span id="user_cartCountHeader">'+result.count+'</span>');
                $('.add-to-cart-container').html(addedToCartContent);
                // $('#addNewAddress').modal('hide');
            }
        }
    });
});

// add to wishlist
$(document).on('submit', '.wishlistForm', function(event) {
    event.preventDefault();

    const userId = $('input[name="user_id"]').val();
    const productId = $('input[name="product_id"]').val();

    if (userId != 0) {
        $.ajax({
            url : $(this).attr('action'),
            method : $(this).attr('method'),
            data : {
                _token: $('input[name="_token"]').val(),
                user_id: userId,
                product_id: productId
            },
            beforeSend: function() {
                toastFire('info', 'Please wait...');
            },
            success: function(result) {
                if (result.status == 400) {
                    toastFire('error', result.message);
                } else {
                    if (result.type == "add") {
                        $('.wishlistForm svg').attr('fill', '#FF5722').attr('stroke', '#FF5722');
                        toastFire('success', result.message);
                    } else {
                        $('.wishlistForm svg').attr('fill', 'none').attr('stroke', 'currentColor');
                        toastFire('info', result.message);
                    }
                }
            }
        });
    } else {
        toastFire('warning', '<a href="/user/login">Login</a> to continue');
    }
});

// show more/ less
var setHeight = function (element, height) {
    if (!element) {;
        return false;
    }
    else {
        var elementHeight = parseInt(window.getComputedStyle(element, null).height, 10),
        toggleButton = document.createElement('a'),
        text = document.createTextNode('Show more'),
        parent = element.parentNode;

        toggleButton.src = '#';
        toggleButton.className = 'show-more small';
        // toggleButton.style.float = 'right';
        toggleButton.style.position = 'relative';
        toggleButton.style.top = '-18px';
        toggleButton.style.paddingRight = '15px';
        toggleButton.appendChild(text);

        parent.insertBefore(toggleButton, element.nextSibling);

        element.setAttribute('data-fullheight', elementHeight);
        element.style.height = height;
        return toggleButton;
    }
}

var toggleHeight = function (element, height) {
    if (!element) {
        return false;
    }
    else {
        var full = element.getAttribute('data-fullheight'),
        currentElementHeight = parseInt(element.style.height, 10);

        element.style.height = full == currentElementHeight ? height : full + 'px';
    }
}

var toggleText = function (element) {
    if (!element) {
        return false;
    }
    else {
        var text = element.firstChild.nodeValue;
        element.firstChild.nodeValue = text == 'Show more' ? 'Show less' : 'Show more';
    }
}


var applyToggle = function(elementHeight){
    'use strict';
    return function(){
        toggleHeight(this.previousElementSibling, elementHeight);
        toggleText(this);
    }
}


var modifyDomElements = function(className, elementHeight){
    var elements = document.getElementsByClassName(className);
    var toggleButtonsArray = [];


    for (var index = 0, arrayLength = elements.length; index < arrayLength; index++) {
        var currentElement = elements[index];
        var toggleButton = setHeight(currentElement, elementHeight);
        toggleButtonsArray.push(toggleButton);
    }

    for (var index=0, arrayLength=toggleButtonsArray.length; index<arrayLength; index++){
        toggleButtonsArray[index].onclick = applyToggle(elementHeight);
    }
}
// show more/ less ends
