<?php
?>
<script>
    $( "select[name='department']" ).change(function () {
        var departmentId = $(this).val();
        if(departmentId) {
            $.ajax({
                url: "includes/response/offices.php",
                dataType: 'Json',
                data: {'departments_id':departmentId},
                success: function(data) {
                    $('select[name="office"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="office"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            });
        }else{
            $('select[name="office"]').empty();
        }
    });
</script>