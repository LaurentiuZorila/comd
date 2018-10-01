<?php
?>
<script>
    $("#againPass").blur(function () {
        var newPass     = $("#newPass").val();
        var againPass   = $(this).val();

        if (newPass != againPass) {
            $('.againPass').removeClass('has-success').addClass('has-error');
            $('.againPass').find('span, label').remove();

            $('.againPass').append('<label class="control-label">Password doesn\'t match.</label><span class="material-icons form-control-feedback">clear</span>');

            $('.newPass').removeClass('has-success').addClass('has-error');
            $('.newPass').find('span, label').remove();

            $('.newPass').append('<label class="control-label">Password doesn\'t match.</label><span class="material-icons form-control-feedback">clear</span>');

        } else if (newPass == againPass) {
            $('.againPass').removeClass('has-error').addClass('has-success');
            $('.againPass').find('span, label').remove();

            $('.againPass').append('<label class="control-label">Password matches.</label><span class="material-icons form-control-feedback">clear</span>\'');

            $('.newPass').removeClass('has-error').addClass('has-success');
            $('.newPass').find('span , label').remove();

            $('.newPass').append('<label class="control-label">Password matches.</label><span class="material-icons form-control-feedback">clear</span>');
        }
    });
</script>
