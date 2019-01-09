<?php
?>
<script>
    $ ("#month").change(function () {
        var year    = $("#year").val();
        var month   = $("#month").val();
        if (year && month) {
            $(".tables").fadeIn(1000);
        }
    });
    $( "#tables" ).change(function() {
        var year   = $("#year").val();
        var month  = $("#month").val();
        var tables = $(this).val();
        $.ajax({
            url: "includes/response/responseMonthExists.php",
            dataType: 'Json',
            data: {'year':year, 'month':month, 'tables':tables, 'officeId': <?php echo $lead->officesId(); ?>},
            success: function(data) {
                $.each(data, function(key, value) {
                    if (value === "Failed") {
                        displayMessage("info", "<?php echo Translate::t('data_month_exists', ['ucfirst'=>true]); ?>", 5000);
                        $(".confirmUpdate").show();
                    }
                });
            }
        });
    });
</script>