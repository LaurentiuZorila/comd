<?php
?>
<script>
    $( "#Submit" ).on ('click', function () {
        var year = $("select[name=year]").val();
        var month = $("select[name=month]").val();
        var tables = $("select[name=tables]").val();

        if(year && month && tables) {
            $.ajax({
                url: "includes/response/responseDbUpdate.php",
                dataType: 'Json',
                data: {'year':year, 'month':month, 'tables':tables},
                success: function(data) {
                    $.each(data, function(key, value) {
                        if (value === "Success") {
                            displayMessage("success", "<?php echo Translate::t('event_updated', ['ucfirst'=>true]); ?>", 1000);
                            setTimeout(function(){
                                location.reload();
                            }, 1000);
                        } else {
                            displayMessage("danger", "<?php echo Translate::t('Db_error', ['ucfirst'=>true]); ?>");
                        }
                    });
                }
            }
        }
    });
</script>