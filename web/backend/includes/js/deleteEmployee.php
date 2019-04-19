<?php
?>
<script>
    $('.deleteOk').click(function(){
        $('#myModal').modal('show');
    });

    $(".deleteEmployee").click(function(){
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return false;
    });

    $('.deleteEmployee').on('click', function () {
        var employeeName = $(this).data("employeename");
        var employeeId = $(this).data("employeeid");
        var leadOfficeId = $(this).data("leadofficeid");
        var office = $(this).data("offices");
        var department = $(this).data("department");
        $("#userName").html(employeeName);
        $("#offices").html(office);
        $("#department").html(department);
        $('#employeeId').val(employeeId);
        $("#employeeName").val(employeeName);
        $('#leadofficeid').val(leadOfficeId);

        $('#deleteEventModal').modal('show');
    });

    $('.deleteOk').on('click', function () {
        var employeeId = $('#employeeId').val();
        var leadOfficeId  = $('#leadofficeid').val();
        var employeeName = $("#employeeName").val();
        $('#deleteEventModal').modal('hide');
        $.ajax({
            type: "GET",
            url: "./includes/response/responseEmployeeDeleted.php",
            data: 'employeeId=' + employeeId + '&leadOfficeId=' + leadOfficeId + '&employeeName=' + employeeName,
            success: function (response) {
                if(parseInt(response) > 0) {
                    setTimeout(function () { window.location.reload(); }, 2000);
                    displayMessage("success", "<?php echo Translate::t('employee_deleted', ['ucfirst'=>true]); ?>");
                } else {
                    displayMessage("danger", "<?php echo Translate::t('Db_error', ['ucfirst'=>true]); ?>");
                }
            }
        });
    });
</script>
