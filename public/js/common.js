// sweetalert toast
function toastFire(type = 'success', title) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'bottom-end',
        timer: 2500,
        showConfirmButton: false,
        // timer: 2000,
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