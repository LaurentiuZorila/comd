<?php
require_once 'core/init.php';
$allEvents = $frontProfile->records(Params::TBL_EVENTS, AC::where(['user_id', $frontUser->userId()]), ['*'], true);

if (!empty($allEvents)) {
    foreach ($allEvents as $allEvent) {
        $events[] = ['days' => $allEvent->days, 'status' => $allEvent->status];
        }
    } else {
        $events[] = ['days' => '', 'status' => ''];
}

if (Input::existsName('get', 'notificationId')) {
    $id = Input::get('notificationId');
    if ($id == 0) {
        $frontUser->update(Params::TBL_NOTIFICATION,
            [
                'employee_view' => 1
            ], [
                'user_id'   => $frontUser->userId()
            ]);
    } else {
        $frontUser->update(Params::TBL_NOTIFICATION,
            [
                'employee_view' => 1
            ], [
                'id'   => $id
            ]);
    }
}
?>
<!DOCTYPE html>
<head>
<?php
include '../common/includes/head.php';
?>
<link rel="stylesheet" href="./../common/css/spiner/style.css">
<script src="./../common/vendor/fullcalendar/lib/moment.min.js"></script>
<script src="./../common/vendor/fullcalendar/fullcalendar.min.js"></script>
<script src="./../common/vendor/fullcalendar/locale-all.js"></script>
<script src="./../common/vendor/jquery.ui.touch/jquery.ui.touch.js"></script>
<!--DATE PICKER-->
<link rel="stylesheet" href="./../common/vendor/bootstrap-datepicker-1.6.4-dist/css/bootstrap-datepicker3.css">
<script src="./../common/vendor/bootstrap-datepicker-1.6.4-dist/js/bootstrap-datepicker.min.js"></script>
<script src="./../common/vendor/bootstrap-datepicker-1.6.4-dist/js/bootstrap-datepicker.js"></script>
<script>
$('element').addTouch();
$(document).ready(function () {
    var initialLocaleCode = '<?php echo $frontUser->language(); ?>';
    var calendar = $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listMonth'
        },
        locale: initialLocaleCode,
        buttonIcons: true, // show the prev/next text
        weekNumbers: true,
        navLinks: true, // can click day/week names to navigate views
        eventLimit: true,
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
        select: function() {
            // Display the modal.
            // You could fill in the start and end fields based on the parameters
            $('#createEventModal').modal('show');

        },

        editable: false,
        eventDrop: function (event, delta) {
            if (event.status !== 2) {
                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                $.ajax({
                    url: './calendar/edit-event-frontend.php',
                    data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
                    type: "POST",
                    success: function (response) {
                        displayMessage("success","<?php echo Translate::t('Request_success', ['ucfirst' => true]); ?>");
                    }
                });
            } else {
                displayMessage("danger","<?php echo Translate::t('can_modify_request', ['ucfirst'=>true]); ?>");
            }
        },
        eventClick: function (event) {
            if (event.status == 2) {
                var deleteMsg = confirm("Do you really want to delete?");
                if (deleteMsg) {
                    $.ajax({
                        type: "POST",
                        url: "./calendar/delete-event-frontend.php",
                        data: "&id=" + event.id,
                        success: function (response) {
                            if(parseInt(response) > 0) {
                                $('#myModal').modal('show');
                                displayMessage("success","<?php echo Translate::t('Request_success', ['ucfirst'=>true]); ?>");
                                setTimeout(function () { window.location.reload(); }, 2000);
                            }
                        }
                    });
                }
            } else {
                displayMessage("danger","<?php echo Translate::t('can_modify_request', ['ucfirst'=>true]); ?>");
            }
        }

    });

    $('#submitButton').on('click', function() {
        var title       = $('#request').val();
        var startDate   = $('#startDate').val();
        var endDate     = $('#endDate').val();

        if (title === '' || startDate === '' || endDate === '') {
            displayMessage("danger","<?php echo Translate::t('all_required', ['ucfirst'=>true]); ?>");
        }

        var sDate = new Date(startDate);
        var eDate = new Date(endDate);
        var ascendingDates;

        // Check if start date si lower as end date
        if (sDate.getTime() <= eDate.getTime()) {
            var ascendingDates = true;
        } else {
            var ascendingDates = false;
        }

        if (title && startDate && endDate && ascendingDates) {
            // hide modal
            $('#createEventModal').modal('hide');

            $.ajax({
                url: "./calendar/add-event-frontend.php",
                dataType: 'Json',
                data: {
                    'title': title,
                    'eventStatus': 'Pending',
                    'start': startDate,
                    'end': endDate,
                    'userId': <?php echo $frontUser->userId(); ?>,
                    'customerId': <?php echo $frontUser->officeId(); ?>},
                success: function (response) {
                    $('#myModal').modal('show');
                    if (response.added === 'success') {
                        $('#myModal').modal('show');
                        displayMessage("success","<?php echo Translate::t('Request_success', ['ucfirst'=>true]); ?>");
                        setTimeout(function () { window.location.reload(); }, 2000);
                    } else if (response.added === 'failed') {
                        displayMessage("danger","<?php echo Translate::t('Request_failed', ['ucfirst'=>true]); ?>");
                        setTimeout(function () { window.location.reload(); }, 2000);
                    }
                }
            });
            calendar.fullCalendar('renderEvent',
                {
                    title: 'Pending',
                    start: start,
                    end: end,
                    allDay: allDay
                },
                true
            );
        } else {
            displayMessage("danger","<?php echo Translate::t('ascending_dates', ['ucfirst'=>true]); ?>");
        }
        $('#calendar').fullCalendar('unselect');

        // Clear modal inputs
        $('#createEventModal').find('input').val('');

        // hide modal
        $('#createEventModal').modal('hide');
    });

});

function displayMessage(type, message) {
    if(type === "success") {
        $(".response").html('<section class="eventMessage"><div class="row"><div class="col-lg-12"><div class="alert alert-dismissible fade show badge-success"><p class="text-white mb-0">'+message+'</p></div></div></div></section>');
        setInterval(function() { $(".eventMessage").fadeOut(); }, 5000);
    } else if (type === "danger") {
        $(".response").html('<section class="eventMessage"><div class="row"><div class="col-lg-12"><div class="alert alert-dismissible fade show badge-danger"><p class="text-white mb-0">'+message+'</p></div></div></div></section>');
        setInterval(function() { $(".eventMessage").fadeOut(); }, 5000);
    }
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
    // LOADING PRELOADER MODAL
    include './../common/includes/preloaders.php';
    ?>
    <!-- Sidebar Navigation end-->
    <div class="page-content">
        <div class="page-header mb-0">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t('Calendar'); ?></h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t('Home'); ?></a></li>
                <li class="breadcrumb-item active"><?php echo Translate::t('Calendar'); ?> </li>
            </ul>
        </div>
<!--        CALENDAR-->
        <section>
            <div class="response"></div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card-header" style="background-color: rgb(45, 48, 53);">
                            <div class="right-menu list-inline no-margin-bottom">
                                <div class="list-inline-item logout">
                                    <button type="button" data-toggle="modal" data-target="#info_calendar" class="btn btn-primary btn-sm float-right" id="info_calendar_pulsate"><i class="fa fa-info-circle"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="block">
                            <div id="calendar" class="">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card-header" style="background-color: rgb(45, 48, 53);">
                            <h4 class="text-center"><?php echo Translate::t('all', ['ucfirst'=>true]) . ' ' . Translate::t('event_request', ['strtolower'=>true]); ?></h4>
                        </div>
                        <div class="block">
                            <p>
                                <button class="btn-sm btn-secondary" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="filter">
                                    <i class="fa fa-angle-down"></i>
                                </button>
                            </p>
                            <form method="post" id="filter" class="collapse mb-1" action="calendar/tableStatus.php">
                                <div class="row">
                                    <div class="col-sm-4 mt-1">
                                        <?php
                                        $status = Input::get('status');
                                        $status = empty($status) ? 'all' : $status;
                                        ?>
                                        <select name="event_status" id="event_status">
                                            <option><?php echo Translate::t('Status'); ?></option>
                                            <?php foreach (Params::EVENTS_STATUS as $key => $value) { ?>
                                                <option value="<?php echo $key; ?>" <?php echo $key == $status ? 'selected' : ''; ?>><?php echo $value; ?></option>
                                            <?php } ?>
                                            <option value="all" <?php echo $status == 'all' ? 'selected' : ''; ?>><?php echo Translate::t('all', ['ucfirst' => true]); ?></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-5 mt-1">
                                        <select name="event_month" id="event_month">
                                            <option value=""><?php echo Translate::t('Select_month'); ?></option>
                                            <?php foreach (Common::getMonths($lang) as $key => $value) { ?>
                                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <button name="eventFilter" id="eventFilter" value="<?php echo Translate::t('Submit'); ?>" class="btn-sm btn-outline-secondary" type="submit"><?php echo 'Go'; ?></button>
                                        <input type="hidden" name="<?php echo Tokens::getInputName(); ?>" value="<?php echo Tokens::getSubmitToken(); ?>">
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive" style="height:519px; overflow-y: scroll;" id="filter-response">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
<!--        CALENDAR END-->
<!--        MODAL-->
        <div id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" class="modal fade">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header"><strong id="exampleModalLabel" class="modal-title"><?php echo Translate::t('Request', ['ucfirst'=>true]); ?></strong>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <select name="request" class="form-control" id="request">
                            <option value="Furlough"><?php echo Translate::t('furlough', ['strtoupper'=>true]); ?></option>
                            <option value="Unpaid"><?php echo Translate::t('unpaid', ['strtoupper'=>true]); ?></option>
                        </select>
                        <div class="form-group mt-2">
                            <label class="form-control-label">Start date</label>
                            <input type="text" value="" id="startDate" class="form-control input-datepicker-autoclose">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">End date</label>
                            <input type="text" value="" id="endDate" class="form-control input-datepicker-autoclose">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-sm btn-outline-secondary" data-dismiss="modal" aria-hidden="true">Cancel</button>
                        <button type="submit" class="btn-sm btn-primary" id="submitButton">Save</button>
                    </div>
                </div>
            </div>
        </div>
<!--        MODAL END-->
        <!-- Modal-->
        <div id="info_calendar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" class="modal fade text-left show" style="display: none;">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header"><strong id="exampleModalLabel" class="modal-title dashtext-1"><?php echo Translate::t('Info'); ?></strong>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo Translate::t('Calendar_info'); ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn-sm btn-danger"><?php echo Translate::t('Close'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End -->
    </div>
</div>
<?php
include '../common/includes/footer.php';
?>
<script src="./../common/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="./../common/js/front.js"></script>
<script src="./../common/vendor/pulsate/jquery.pulsate.js"></script>
<script>
    $(function() {
        $( "#startDate" ).datepicker({
            format: 'yyyy/mm/dd',
            startDate: '0d',
            autoclose: true,
        });
        $( "#endDate" ).datepicker({
            format: 'yyyy/mm/dd',
            startDate: '0d',
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
</body>
</html>