<?php
?>
<script>
    $( "select[name='city']" ).change(function () {
        var cityId = $(this).val();
        if(cityId) {
            $.ajax({
                url: "includes/response/departments.php",
                dataType: 'Json',
                data: {'city_id':cityId},
                success: function(data) {
                    $('select[name="department"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="department"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            });
        }else{
            $('select[name="department"]').empty();
        }
    });

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