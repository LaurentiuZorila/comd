<?php
?>


<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<div class="page-wrap d-flex flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <span class="display-1 d-block">:(</span>
                <div class="mb-4 lead">Token doesn't exist, please submit form again! </div>
                <button onclick="goBack()" class="btn btn-link">Back to Home</button>
            </div>
        </div>
    </div>
</div>

<script>
    function goBack() {
        window.history.back();
    }
</script>