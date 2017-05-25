<?php include "../header.php"; ?>

<div class="container">
    <div class="row">
        <div class="wrapper">

        </div>
        <div class="btn get-product" data-prod-id="234">CLICK ME</div>
        <script>
            (function ($) {
                $('.btn.get-product').click(function (elem) {
                    products.getProduct(elem);
                });
            })(jQuery);
        </script>
    </div>
</div>
