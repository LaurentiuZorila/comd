<?php

?>

<script>
    $( "select[name='departments']" ).change(function () {
        var departments = $(this).val();
        if(departments) {
            $.ajax({
                url: "ajax/offices.php",
                dataType: 'Json',
                data: {'departments':departments},
                success: function(data) {
                    $('select[name="offices"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="offices"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            });
        }else{
            $('select[name="offices"]').empty();
        }
    });
</script>
