<div class="container">
    <div class="row">
        <form id="PaySlip" class="col s12">
            <div class="row">
                <div class="input-field col s12">
                    <label>User ID:</label>
                    <input name="uid" value="">
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <label>Amount:</label>
                    <input name="amount" value="">
                </div>
            </div>
            <div class="row center">
                <button class="btn btn-primary" type="submit">Pay with Razorpay</button>
            </div>
            <div class="form_result"></div>
        </form>
    </div>
</div>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>

    $(function () {
        var success_handler = function (success_data){
              $.ajax({
                    type: 'POST',
                    dataType: "json",
                    url: 'payment_controller/xhr_capture_order/',
                    data: success_data,
                    success: function (response) {
                        alert(response);

                    }
                });
        };

        $("#PaySlip").submit(function (event) {
            event.preventDefault();
            // alert("hellow");
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: 'payment_controller/xhr_request_order',
                data: $('#PaySlip').serialize(),
                success: function (response) {
                    response.handler = success_handler;
                    response.modal = {
                        ondismiss: function() {
                            console.log("This code runs when the popup is closed");
                        }
                    };
                    var rzp = new Razorpay(response);
                    rzp.open();
                }
            });

        });
    });


</script>