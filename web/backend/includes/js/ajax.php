<?php
?>
<script>
    $( "select[name='teams']" ).change(function () {
        var officeId = $(this).val();
        console.log(officeId);
        if(officeId) {
            $.ajax({
                url: "includes/response/staff_employees.php",
                dataType: 'Json',
                data: {'office_id':officeId},
                success: function(data) {
                    $('select[name="employees"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="employees"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            });
        }else{
            $('select[name="employees"]').empty();
        }
    });
</script>

<script>
    $( "select[name='teams']" ).change(function () {
        var userID = $(this).val();
        if(userID) {
            $.ajax({
                url: "includes/response/years.php",
                dataType: 'Json',
                data: {'id':userID},
                success: function(data) {
                    $('select[name="year"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="year"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            });
        }else{
            $('select[name="year"]').empty();
        }
    });
</script>

<script>
    $( "select[name='employees']" ).change(function () {
        var employeesID = $(this).val();
        if(employeesID) {
            $.ajax({
                url: "includes/response/months.php",
                dataType: 'Json',
                data: {'employees_id':employeesID},
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