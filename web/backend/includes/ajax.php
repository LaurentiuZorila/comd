<?php
?>
<script>
    $(document).ready(function(){
        $('#colour').change(function(){
            $("#theme-stylesheet").attr("href", "css/" + $(this).val() + ".css");
        });
    });

    $( "select[name='teams']" ).change(function () {
        var officeId = $(this).val();
        if(officeId) {
            $.ajax({
                url: "ajax/staff_employees.php",
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

    $( "select[name='teams']" ).change(function () {
        var userID = $(this).val();
        if(userID) {
            $.ajax({
                url: "ajax/years.php",
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

    $( "select[name='employees']" ).change(function () {
        var employeesID = $(this).val();
        if(employeesID) {
            $.ajax({
                url: "ajax/months.php",
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