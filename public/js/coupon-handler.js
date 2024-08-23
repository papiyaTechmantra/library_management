$(document).on('submit', '#couponCheck', function(e) {
    e.preventDefault();
    $.ajax({
        url : $(this).attr('action'),
        method : $(this).attr('method'),
        data : {
            // token: $('input[name="_token"]').val(),
            coupon: $('#coupon').val()
        },
        beforeSend: function() {
            $('.pincode-loader').html(pinLoaderImg);
            $('.savePincodeBar').attr('disabled', true).text('Please wait');
        },
        success: function(result) {
            $('.pincode-loader').empty();
            if (result.status == 400) {
                toastFire('error', result.message);
            } else {
                var couponContent = `
                <div class="coupon-applied">
                    <div class="row mb-3">
                        <div class="col-6"><p class="text-muted mb-0">Coupon Applied</p></div>
                        <div class="col-6 text-end"><p class="mb-0 fw-600"> ${result.data.coupon_details} </p></div>
                        <div class="col-12">
                            <p class="small text-muted mb-0">
                                ${result.data.coupon}
                                <a href="javascript: void(0)" onclick="couponRemove()">Remove?</a>
                            </p>
                        </div>
                    </div>
                </div>
                `;

                let finalAmount = parseInt($('input[name="finalAmountWTCoupon"]').val());
                let couponDiscount = 0;
                if (result.data.coupon_type == 2) {
                    couponDiscount = result.data.coupon_amount;
                } else {
                    couponDiscount = parseInt(finalAmount * (result.data.coupon_amount / 100));
                }
                finalAmount = finalAmount - couponDiscount;
                console.log(finalAmount);
                $('#finalAmountDisplay').text(finalAmount);
                // $('input[name="finalAmountWTCoupon"]').val(finalAmount);
                // console.log(finalAmount);

                $('#couponStatus').html(couponContent);
                toastFire('success', result.message);
            }
        }
    });
});

function couponRemove() {
    $.ajax({
        url : baseUrl+'/api/coupon/remove',
        method : 'get',
        success: function(result) {
            if (result.status == 400) {
                toastFire('error', result.message);
            } else {
                var couponContent = `
                <div class="coupon-not-applied">
                    <a data-bs-toggle="collapse" href="#couponCollapse">
                        <p class="mb-2">I have a Voucher/ Coupon code</p>
                    </a>
                    <div class="coupon-code-container collapse" id="couponCollapse">
                        <form action="${baseUrl}/api/coupon/status" method="get" id="couponCheck">
                            <div class="input-group">
                                <input type="text" class="form-control coupon-code" placeholder="Enter Voucher/ Coupon code" id="coupon" name="coupon" value="" maxlength="10">
                                <span class="pincode-loader" style="right: 70px;"></span>

                                <button class="btn btn-secondary" type="submit" id="couponCheckBtn">Apply</button>
                            </div>
                        </form>
                    </div>
                </div>
                `;

                let finalAmount = parseInt($('input[name="finalAmountWTCoupon"]').val());
                $('#finalAmountDisplay').text(finalAmount);

                $('#subTotalRow').empty();
                $('#couponStatus').html(couponContent);
                toastFire('success', result.message);
            }
        }
    });
}
