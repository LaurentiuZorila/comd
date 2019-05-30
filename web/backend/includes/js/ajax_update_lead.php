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
                    $('select[name="departments"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="departments"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                    $('.selectpicker').selectpicker('refresh');
                }
            });
        }else{
            $('select[name="departments"]').empty();
            $('.selectpicker').selectpicker('refresh');
        }
    });

    $( "select[name='departments']" ).change(function () {
        var departments = $(this).val();
        if(departments) {
            $.ajax({
                url: "includes/response/offices.php",
                dataType: 'Json',
                data: {'departments_id':departments},
                success: function(data) {
                    $('select[name="offices"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="offices"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                    $('.selectpicker').selectpicker('refresh');
                }
            });
        }else{
            $('select[name="offices"]').empty();
            $('.selectpicker').selectpicker('refresh');
        }
    });
</script>
