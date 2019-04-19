<?php
?>
<script>
    $ ( '.employeeStats a' ).on('click', function () {
        var statsId = $(this).data("stats");
        var employeeId = $(this).data("employeeid");
        $.ajax({
            type: "GET",
            url: "./includes/response/responseEmployeeStats.php",
            data: '&statsId=' + statsId + '&employeeId=' + employeeId + '&office_id=<?php echo $lead->officesId(); ?>&departments_id=<?php echo $lead->departmentId();?>',
            success: function (response) {
                if(parseInt(response) > 0) {
                    setTimeout(function () { window.location.reload(); }, 2000);
                    displayMessage("success", "<?php echo Translate::t('status_success_updated', ['ucfirst'=>true]); ?>");
                } else {
                    displayMessage("danger", "<?php echo Translate::t('Db_error', ['ucfirst'=>true]); ?>");
                }
            }
        });
    });

    $ ( '.deleteEmployee' ).on('click', function () {
        var employeeName = $(this).data("employeename");
        var employeeId = $(this).data("employeeid");
        var leadOfficeId = $(this).data("leadofficeid");
        $("#userName").html(employeeName);
        $('#employeeId').val(employeeId);
        $('#leadofficeid').val(leadOfficeId);
        $('#deleteEventModal').modal('show');
    });

    $ ( '.deleteOk' ).on('click', function () {
        var employeeId = $('#employeeId').val();
        var leadOfficeId  = $('#leadofficeid').val();
        $('#deleteEventModal').modal('hide');
        $.ajax({
            type: "GET",
            url: "./includes/response/responseEmployeeDelete.php",
            data: 'employeeId=' + employeeId + '&leadOfficeId=' + leadOfficeId + '&office_id=<?php echo $lead->officesId(); ?>&departments_id=<?php echo $lead->departmentId();?>',
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