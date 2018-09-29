<?php
?>
<script>
    $("#pass").blur(function () {
    var password = $(this).val();
    if(password) {
        $.ajax({
                url: "ajax/user.php",
                dataType: 'Json',
                data: {'password':password, 'id': <?php echo $customerId ; ?>},
                success: function(data) {
                    $.each(data, function(key, value) {
                        if (value == "Success") {
                            $('.passDefault').removeClass('has-error').addClass('has-success');
                            $('.passDefault').find('span').remove();
                            $('.passDefault').find('label').remove();

                            $('.passDefault').append('<label class="control-label">Correct password</label>');
                            $('.passDefault').append('<span class="form-control-feedback"><i class="material-icons">done</i></span>');
                        }
                        if (value == "Failed") {
                            $('.passDefault').removeClass('has-success').addClass('has-error');
                            $('.passDefault').find('span').remove();
                            $('.passDefault').find('label').remove();

                            $('.passDefault').append('<label class="control-label">Wrong password! Try again.</label>');
                            $('.passDefault').append('<span class="material-icons form-control-feedback">clear</span>');
                        }
                    });
                }
            });
        }
});

</script>
