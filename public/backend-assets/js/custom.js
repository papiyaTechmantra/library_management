$('form').on('submit', () => {
    $('button[type="submit"]').prop('disabled', true).text('Loading...');
});

// sweetalert toast
function toastFire(type = 'success', title) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'bottom',
        timer: 3000,
        showCloseButton: true,
        showConfirmButton: false,
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

//  enable tooltip everywhere
$('[data-toggle="tooltip"]').tooltip();

// category create page
$('input[name=level]').on('change', function() {
    checkCatParentLevel();
});

function checkCatParentLevel() {
    let lavel = $('input[name=level]:checked').val();

    if (lavel === "parent") {
        $('#selectParent').hide();
    } else {
        $('#selectParent').show();
    }
}

// status toggle
function statusToggle(route) {
    $.ajax({
        url: route,
        success: function(resp) {
            if (resp.status == 200) {
                toastFire('success', resp.message);
            } else {
                toastFire('error', resp.message);
            }
        }
    });
}

// product status change
function productStatus(route, status, prodId) {
    $.ajax({
        url: route,
        data: {
            status: status,
            prodId: prodId
        },
        success: function(resp) {
            if (resp.status == 200) {
                toastFire('success', resp.message);
            } else {
                toastFire('error', resp.message);
            }
        }
    });
}
