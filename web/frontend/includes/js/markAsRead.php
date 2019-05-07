<?php
?>
<script>
    $(document).on('click', '.markasread', function () {
        var $this = $(this);
        var employeeId  = $this.data("employee");
        $.ajax({
            url: "./includes/response/markAsReadResponse.php",
            dataType: 'Json',
            data: {
                'employeeId': employeeId
            },
            success: function (response) {
                if(parseInt(response) > 0) {
                    $('#myModal').modal('show');
                    setTimeout(function () {
                        window.location.reload();
                    }, 2000);
                } else {
                    displayMessage("danger", "<?php echo Translate::t('Db_error', ['ucfirst'=>true]); ?>");
                }
            }
        });
    });
</script>
