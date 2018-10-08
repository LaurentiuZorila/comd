<?php
?>
<script>
    $( "select[name='year']" ).change(function () {
        var year = $(this).val();
        if(year) {
            $.ajax({
                url: "includes/response/profile_months.php",
                dataType: 'Json',
                data: {'table': $('select[name="table"]').val(), 'offices_id': <?php echo $officeId; ?>, 'year':year },
                success: function(data) {
                    $('select[name="month"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="month"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            });
        }else{
            $('select[name="month"]').empty();
        }
    });
</script>
