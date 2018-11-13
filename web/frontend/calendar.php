<?php
require_once 'core/init.php';
?>
<!DOCTYPE html>
<head>
    <?php
    include '../common/includes/head.php';
    ?>
<script src="./calendar/fullcalendar/lib/jquery.min.js"></script>
<script src="./calendar/fullcalendar/lib/moment.min.js"></script>
<script src="./calendar/fullcalendar/fullcalendar.min.js"></script>
<script>

$(document).ready(function () {
    var calendar = $('#calendar').fullCalendar({
        editable: true,
        events: './calendar/fetch-event-frontend.php',
        displayEventTime: false,
        eventRender: function (event, element, view) {
            if (event.allDay === 'true') {
                event.allDay = true;
            } else {
                event.allDay = false;
            }
        },
        selectable: true,
        selectHelper: true,
        select: function (start, end, allDay) {
            $('#info_modal').css("display", "block");
            $('.modal').addClass('show');
            $('.Close').click(function() {
                $('#info_modal').removeClass('show');
                $('#info_modal').css("display", "none");
            });

            $ ( '#add' ).on('click', function () {
                var title = $( "select[name='eventSelected']" ).val();
            });

            if (title) {
                var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                $.ajax({
                    url: "./calendar/add-event-frontend.php",
                    dataType: 'Json',
                    data: {
                        'title': title,
                        'start': start,
                        'end': end,
                        'userId': <?php echo $frontUser->userId(); ?>,
                        'customerId': <?php echo $frontUser->officeId(); ?>},
                    success: function (data) {
                        displayMessage("Added Successfully");
                    }
                });
                calendar.fullCalendar('renderEvent',
                        {
                            title: title,
                            start: start,
                            end: end,
                            allDay: allDay
                        },
                true
                        );
            }
            calendar.fullCalendar('unselect');
        },

        editable: true,
        eventDrop: function (event, delta) {
                    var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                    $.ajax({
                        url: './calendar/edit-event-frontend.php',
                        data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
                        type: "POST",
                        success: function (response) {
                            displayMessage("Updated Successfully");
                        }
                    });
                },
        eventClick: function (event) {
            var deleteMsg = confirm("Do you really want to delete?");
            if (deleteMsg) {
                $.ajax({
                    type: "POST",
                    url: "./calendar/delete-event-frontend.php",
                    data: "&id=" + event.id,
                    success: function (response) {
                        if(parseInt(response) > 0) {
                            $('#calendar').fullCalendar('removeEvents', event.id);
                            displayMessage("Deleted Successfully");
                        }
                    }
                });
            }
        }

    });
});

function displayMessage(message) {
	    $(".response").html("<div class='success'>"+message+"</div>");
    setInterval(function() { $(".success").fadeOut(); }, 1000);
}
</script>
</head>
<body>
<?php
include 'includes/navbar.php';
?>
<div class="d-flex align-items-stretch">
    <!-- Sidebar Navigation-->
    <?php
    include 'includes/sidebar.php';
    ?>
    <!-- Sidebar Navigation end-->
    <div class="page-content">
        <div class="page-header">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t($lang, 'Calendar'); ?></h2>
            </div>
        </div>
        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="block">
                            <div class="title"><strong>Calendar</strong></div>
                            <div id="calendar" class="fc fc-bootstrap3 fc-ltr">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Modal-->
    <div id="info_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" class="modal fade text-left" style="display: none;">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><strong id="exampleModalLabel" class="modal-title dashtext-3"><?php echo Translate::t($lang, 'Info'); ?></strong>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <select name="eventSelected">
                        <option value="ferie"><?php echo Translate::t($lang, 'furlough', ['ucfirst' => true]); ?></option>
                        <option value="permeso"><?php echo Translate::t($lang, 'unpaid', ['ucfirst' => true]); ?></option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" id="add" class="btn btn-primary add"><?php echo Translate::t($lang, 'Add'); ?></button>
                    <button type="button" data-dismiss="modal" class="btn btn-secondary Close"><?php echo Translate::t($lang, 'Close'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->
</div>
</body>


</html>