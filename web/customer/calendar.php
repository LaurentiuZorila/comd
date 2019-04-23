<?php
require_once 'core/init.php';
$allEmployees = $leadData->records(Params::TBL_EMPLOYEES, AC::where(['offices_id', $lead->officesId()]), ['name', 'id'], true, ['ORDER BY' => 'name']);
if (Input::existsName('get', 'notificationId')) {
    $id = Input::get('notificationId');
    if ($id == 0) {
        $leadDb->update(Params::TBL_NOTIFICATION,
            [
                'view' => 1
            ], [
                'lead_id'   => $lead->customerId()
            ]);
    } else {
        $leadDb->update(Params::TBL_NOTIFICATION,
            [
                'view' => 1
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
<!--DATE PICKER-->
<link rel="stylesheet" href="./../common/vendor/bootstrap-datepicker-1.6.4-dist/css/bootstrap-datepicker3.css">
<script src="./../common/vendor/bootstrap-datepicker-1.6.4-dist/js/bootstrap-datepicker.min.js"></script>
<script src="./../common/vendor/bootstrap-datepicker-1.6.4-dist/js/bootstrap-datepicker.js"></script>
<script>

$(document).ready(function () {
    var initialLocaleCode = '<?php echo $lead->language(); ?>';
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
        events: './calendar-customer/fetch-event-customer.php',
        displayEventTime: false,
        eventRender: function (event, element, view) {
            if (event.allDay === 'true') {
                event.allDay = true;
            } else {
                event.allDay = false;
            }
        },
        selectable: true, // On click add event disable
        selectHelper: true,
        select: function(start, end) {
            // Display the modal.
            // You could fill in the start and end fields based on the parameters
            $('#createEventModal').modal('show');

        },
        eventClick:  function(event, jsEvent, view) {
            $("#startTime").html(moment(event.start).format('DD-MM-Y'));
            $("#endTime").html(moment(event.end).format('DD-MM-Y'));
            $("#title").html(event.title);
            $("#totalDays").html(event.totalDays);
            $('#eventId').val(event.id);
            $('#userId').val(event.userId);
            $('#table').val(event.table);
            $('#status').val(event.status);
            $('#month').val(event.month);
            $('#year').val(event.year);
            $('#deleteEventModal').modal('show');
        },
        editable: true,
        eventDrop: function (event, delta) {
                    var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                    $.ajax({
                        url: './calendar-customer/edit-event-customer.php',
                        data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
                        type: "POST",
                        success: function (response) {
                            displayMessage("Updated Successfully");
                        }
                    });
        },
    });
});

function displayMessage(type, message) {
    $(".response").html('<section class="eventMessage"><div class="row"><div class="col-lg-12"><div class="alert alert-dismissible fade show badge-'+type+'"><p class="text-white mb-0">'+message+'</p></div></div></div></section>');
    setInterval(function() { $(".eventMessage").fadeOut(); }, 5000);
}

$(function() {
    $( "#startDate" ).datepicker({
        format: 'yyyy/mm/dd',
        autoclose: true,
    });
    $( "#endDate" ).datepicker({
        format: 'yyyy/mm/dd',
        autoclose: true,
    });
});
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
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
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t('calendar'); ?></h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t('Home'); ?></a></li>
                <li class="breadcrumb-item active"><?php echo Translate::t('calendar'); ?> </li>
            </ul>
        </div>
        <?php
        if (Errors::countAllErrors()) {
            include './../common/errors/errors.php';
        }
        ?>
        <section>
            <div class="response"></div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8 p-0">
                        <div class="card-header" style="background-color: rgb(45, 48, 53);">
                            <div class="right-menu list-inline no-margin-bottom">
                                <div class="list-inline-item logout">
                                    <button type="button" data-toggle="modal" data-target="#info_calendar" class="btn btn-primary btn-sm float-right" id="info_calendar_pulsate"><i class="fa fa-info-circle"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="block">
                            <div id="calendar" class="fc fc-bootstrap3 fc-ltr"></div>
                        </div>
                    </div>
                    <div class="col-lg-4 mr-0">
                        <div class="card-header" style="background-color: rgb(45, 48, 53); border-bottom:transparent;">
                            <h4 class="text-center"><?php echo Translate::t('all', ['ucfirst'=>true]) . ' ' . Translate::t('event_request', ['strtolower'=>true]); ?></h4>
                        </div>
                        <div class="block">
                            <p>
                                <button class="btn-sm btn-secondary" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="filter">
                                    <i class="fa fa-angle-down"></i>
                                </button>
                            </p>
                            <form method="post" id="filter" class="collapse mb-1" action="calendar-customer/tableStatus.php">
                                <div class="row">
                                    <div class="col-sm-4 mt-1 mr-0">
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
<!--                                <div class="mt-1">-->

<!--                                </div>-->
                            </form>
                            <div class="table-responsive" style="height:545px; overflow-y: scroll;" id="filter-response">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
<!--        ********** CREATE EVENT MODAL ************ -->
        <div id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" class="modal fade">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header"><strong id="exampleModalLabel" class="modal-title dashtext-3"><?php echo Translate::t('insert_event'); ?></strong>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                        <label class="form-control-label"><?php echo Translate::t('Select_Employees', ['ucfirst'=>true]); ?></label>
                        <select name="employees" class="form-control" id="employees">
                            <option value=""></option>
                            <?php foreach ($allEmployees as $employees) { ?>
                                <option value="<?php echo $employees->id; ?>"><?php echo $employees->name; ?></option>
                            <?php } ?>
                        </select>
                        </div>
                        <div class="form-group">
                        <label class="form-control-label"><?php echo Translate::t('event_request', ['ucfirst'=>true]); ?></label>
                        <select name="request" class="form-control" id="request">
                            <option value=""></option>
                            <?php foreach (Params::TBL_COMMON as $item) { ?>
                                <option value="<?php echo $item; ?>"><?php echo Translate::t($item, ['strtoupper'=>true]); ?></option>
                            <?php } ?>
                        </select>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Start date</label>
                            <input type="text" value="" id="startDate" class="form-control input-datepicker-autoclose">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">End date</label>
                            <input type="text" value="" id="endDate" class="form-control input-datepicker-autoclose">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitButton">Save</button>
                    </div>
                </div>
            </div>
        </div>
<!--        *********    CREATE EVENT MODAL END ********* -->

        <!--        *********    DELETE EVENT MODAL START ********* -->
        <div id="deleteEventModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" class="modal fade">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header"><strong id="exampleModalLabel" class="modal-title dashtext-3"><?php echo Translate::t('delete_event_modal'); ?></strong>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body text-white-50" id="modalBody">
                        <h4 id="title"></h4>
                        Start: <span id="startTime"></span><br>
                        End: <span id="endTime"></span><br><br>
                        Total days: <span id="totalDays"></span><br><br>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-sm btn-outline-secondary" data-dismiss="modal" aria-hidden="true"><?php echo Translate::t('close', ['ucfirst']); ?></button>
                        <button type="submit" class="btn-sm btn-primary deleteEvent" id="deleteEvent"><?php echo Translate::t('delete', ['ucfirst']); ?></button>
                        <input type="hidden" id="eventId" value="" />
                        <input type="hidden" id="userId" value="" />
                        <input type="hidden" id="table" value="" />
                        <input type="hidden" id="status" value="" />
                        <input type="hidden" id="month" value="" />
                        <input type="hidden" id="year" value="" />
                    </div>
                </div>
            </div>
        </div>
        <!--        *********    DELETE EVENT MODAL END ********* -->

        <!-- *********    INFO Modal START  ********* -->
        <div id="info_calendar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" class="modal fade text-left show" style="display: none;">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header"><strong id="exampleModalLabel" class="modal-title"><?php echo Translate::t('Info'); ?></strong>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo Translate::t('calendar_info_lead'); ?></p>
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
<?php
require 'includes/calendar/modals_events.php';
?>
</body>
</html>