<?php
?>
<script>
    $(document).on('click', '.eventAction', function () {
        var $this       = $(this);
        var employeeId  = $this.data("employee");
        var eventId     = $this.data("eventid");
        var statusEvent = $this.data("accepted");
        var titleEvent  = $this.data("title");
        var month       = $this.data("month");
        var year        = $this.data("year");

        if (employeeId && eventId) {
            $.ajax({
                url: "./calendar-customer/update-event-customer.php",
                dataType: 'Json',
                data: {
                    'eventId': eventId,
                    'employeeId': employeeId,
                    'statusEvent': statusEvent,
                    'title': titleEvent,
                    'month': month,
                    'year': year
                },
                success: function (response) {
                    if(parseInt(response) > 0) {
                        $('#myModal').modal('show');
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                        displayMessage("success", "<?php echo Translate::t('event_updated', ['ucfirst'=>true]); ?>");
                    } else {
                        displayMessage("danger", "<?php echo Translate::t('Db_error', ['ucfirst'=>true]); ?>");
                    }
                }
            });
        }
    });


    // Delete modal
    $(document).on('click', '#deleteEvent', function () {
        var eventId = $('#eventId').val();
        var userId  = $('#userId').val();
        var table   = $('#table').val();
        var status  = $('#status').val();
        var month   = $('#month').val();
        var year    = $('#year').val();
        $('#deleteEventModal').modal('hide');
        $.ajax({
            type: "GET",
            url: "./calendar-customer/delete-event-customer.php",
            data: '&id=' + eventId + '&userId=' + userId + '&table=' + table + '&status=' + status + '&month=' + month + '&year=' + year,
            success: function (response) {
                if(parseInt(response) > 0) {
                    $('#myModal').modal('show');
                    setTimeout(function () { window.location.reload(); }, 2000);
                    displayMessage("success", "<?php echo Translate::t('event_deleted', ['ucfirst'=>true]); ?>");
                } else {
                    displayMessage("danger", "<?php echo Translate::t('Db_error', ['ucfirst'=>true]); ?>");
                }
            }
        });
    });

    $(document).on('click', '.acceptEvent', function () {
        $('#deleteEventModal').modal('hide');
        var $this       = $(this);
        var employeeId  = $('#userId').val();
        var eventId     = $('#eventId').val();
        var statusEvent = 1;
        var titleEvent  = $('#table').val();
        var month       = $('#month').val();
        var year        = $('#year').val();

        if (employeeId && eventId) {
            $.ajax({
                url: "./calendar-customer/update-event-customer.php",
                dataType: 'Json',
                data: {
                    'eventId': eventId,
                    'employeeId': employeeId,
                    'statusEvent': statusEvent,
                    'title': titleEvent,
                    'month': month,
                    'year': year
                },
                success: function (response) {
                    if(parseInt(response) > 0) {
                        $('#myModal').modal('show');
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                        displayMessage("success", "<?php echo Translate::t('event_updated', ['ucfirst'=>true]); ?>");
                    } else {
                        displayMessage("danger", "<?php echo Translate::t('Db_error', ['ucfirst'=>true]); ?>");
                    }
                }
            });
        }
    });

    // Add event
    $(document).on('click', '#submitButton', function () {
        var employeeId  = $('#employees').val();
        var statusEvent = 1;
        var titleEvent  = $('#request').val();
        var startDate   = $('#startDate').val();
        var endDate     = $('#endDate').val();

        if (titleEvent === '' || startDate === '' || endDate === '' || employeeId === '') {
            $('#createEventModal').modal('hide');
            displayMessage("danger","<?php echo Translate::t('all_required', ['ucfirst'=>true]); ?>");
        } else {
            var sDate = new Date(startDate);
            var eDate = new Date(endDate);

            // Check if start date si lower as end date
            if (sDate.getTime() <= eDate.getTime()) {
                // Hide modal
                $('#createEventModal').modal('hide');
                $.ajax({
                    url: "./calendar-customer/add-event-customer.php",
                    dataType: 'Json',
                    data: {
                        'employeeId': employeeId,
                        'statusEvent': statusEvent,
                        'title': titleEvent,
                        'start': startDate,
                        'end': endDate,
                        'userId': <?php echo $lead->officesId(); ?>,
                    },
                    success: function (response) {
                        if(parseInt(response) === 1) {
                            $('#myModal').modal('show');
                            setTimeout(function () {
                                window.location.reload();
                            }, 2000);
                            displayMessage("success", "<?php echo Translate::t('event_added', ['ucfirst'=>true]); ?>");
                        } else if (parseInt(response) === 2) {
                            displayMessage("info", "<?php echo Translate::t('one_month_event', ['ucfirst'=>true]); ?>");
                        } else {
                            displayMessage("danger", "<?php echo Translate::t('Db_error', ['ucfirst'=>true]); ?>");
                        }
                    }
                });
            } else {
                $('#createEventModal').modal('hide');
                displayMessage("danger", "<?php echo Translate::t('ascending_dates', ['ucfirst'=>true]); ?>");
            }
        }
    });

    $(function() {
        $( "#startDate" ).datepicker({
            startDate: '-15d',
            autoclose: true,
        });
        $( "#endDate" ).datepicker({
            startDate: '-15d',
            autoclose: true,
        });
    });

    $("#info_calendar_pulsate").pulsate({color:"#633b70;"});

    $('#filter').on('submit', function () {
        var $this = $(this);
        $.post($this.attr('action'), $this.serialize(), function (html) {
            $('#filter-response').html(html);
        }, 'html');
        return false;
    }).trigger('submit');
</script>
