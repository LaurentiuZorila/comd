<?php
require_once 'core/init.php';
$allEmployees = $leadData->records(Params::TBL_EMPLOYEES, AC::where(['offices_id', $lead->officesId()]), ['name', 'id']);
?>
<!DOCTYPE html>
<head>
    <?php
    include '../common/includes/head.php';
    ?>
<link rel="stylesheet" href="./../common/css/spiner/style.css">
<script src="./../common/vendor/fullcalendar/lib/jquery.min.js"></script>
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

        eventClick: function (event) {
            var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
            var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");
            var deleteMsg = confirm("Do you really want to delete?");
            if (deleteMsg) {
                $.ajax({
                    type: "GET",
                    url: "./calendar-customer/delete-event-customer.php",
                    data: '&id=' + event.id + '&userId=' + event.userId + '&table=' + event.table + '&status=' + event.status + '&month=' + event.month + '&year=' + event.year,
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
            }
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
        <?php
        if (Errors::countAllErrors()) {
            include './../common/errors/errors.php';
        }
        ?>
        <section>
            <div class="response"></div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card-header" style="background-color: rgb(45, 48, 53);">
                            <div class="right-menu list-inline no-margin-bottom">
                                <div class="list-inline-item logout">
                                    <a type="button" data-toggle="modal" data-target="#info_calendar" class="btn btn-primary btn-sm float-right" id="info_calendar_pulsate"><i class="fa fa-info-circle"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="block">
                            <div id="calendar" class="fc fc-bootstrap3 fc-ltr"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card-header" style="background-color: rgb(45, 48, 53);">
                            <h4 class="text-center"><?php echo Translate::t('all', ['ucfirst'=>true]) . ' ' . Translate::t('event_request', ['strtolower'=>true]); ?></h4>
                        </div>
                        <div class="block">
                            <p>
                                <button class="btn-sm btn-outline-secondary" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="filter">
                                    <?php echo Translate::t('Filters'); ?>
                                </button>
                            </p>
                            <form method="post" id="filter" class="collapse mb-1" action="calendar-customer/tableStatus.php">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <?php
                                        $status = Input::get('status');
                                        $status = empty($status) ? 'all' : $status;
                                        ?>
                                        <select name="event_status" id="event_status">
                                            <option><?php echo Translate::t('Status'); ?></option>
                                            <?php foreach (Params::EVENTS_STATUS as $key => $value) { ?>
                                                <option value="<?php echo $key; ?>" <?php echo $key == $status ? 'selected' : ''; ?>><?php echo $value; ?></option>
                                            <? } ?>
                                            <option value="all" <?php echo $status == 'all' ? 'selected' : ''; ?>><?php echo Translate::t('all', ['ucfirst' => true]); ?></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-5">
                                        <select name="event_month" id="event_month">
                                            <option value=""><?php echo Translate::t('Select_month'); ?></option>
                                            <?php foreach (Common::getMonths($lang) as $key => $value) { ?>
                                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            <? } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-sm-3">
                                        <button name="eventFilter" id="eventFilter" value="<?php echo Translate::t('Submit'); ?>" class="btn-sm btn-outline-secondary" type="submit"><?php echo Translate::t('Submit'); ?></button>
                                        <input type="hidden" name="<?php echo Tokens::getInputName(); ?>" value="<?php echo Tokens::getSubmitToken(); ?>">
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive" style="height:519px; overflow-y: scroll;" id="filter-response">
                                <table class="table">
                                    <thead>
                                    <tr role="row">
                                        <th class="text-primary">Name</th>
                                        <th class="text-primary">Date</th>
                                        <th class="text-primary">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (count($allEvents) > 0) {
                                    foreach ($allEvents as $allEvent) { ?>
                                    <tr>
                                        <td>
                                            <a data-toggle="tooltip" data-placement="top" title="Tooltip on top">
                                                <?php echo Common::makeAvatar($leadData->records(Params::TBL_EMPLOYEES, AC::where(['id', $allEvent->user_id]), ['name'], false)->name); ?>
                                            </a>

                                        </td>
                                        <td class="text-small">
                                            <?php
                                            $collapseData = $allEvent->status == 2 ? 'data-target=#collapseExample' . $allEvent->id . ' aria-controls=collapseExample' . $allEvent->id : '';
                                            ?>
                                            <a class="" style="cursor: pointer;" type="button" data-toggle="collapse" <?php echo $collapseData; ?> aria-expanded="false">
                                                <?php
                                                $all_days = explode(',', $allEvent->days);
                                                if (count($all_days) > 1) {
                                                    echo current($all_days) . ' - ' . end($all_days);
                                                } else {
                                                    echo $all_days[0];
                                                }
                                                ?>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo Params::EVENTS_STATUS_COLORS[$allEvent->status]; ?>"><?php echo Params::EVENTS_STATUS[$allEvent->status]; ?></span>
                                            <div class="collapse" id="collapseExample<?php echo $allEvent->id; ?>">
                                                <div class="btn-group btn-group-sm mt-3" role="group" aria-label="Basic example">
                                                    <a type="" class="btn-sm btn-primary p-1 eventAction" style="cursor: pointer;" id="accepted" data-accepted="1" data-employee="<?php echo $allEvent->user_id; ?>" data-eventid="<?php echo $allEvent->id; ?>" data-title="<?php echo $allEvent->title; ?>" data-month="<?php echo $allEvent->month; ?>" data-year="<?php echo $allEvent->year; ?>"><small>Accept</small></a>
                                                    <a type="" class="btn-sm btn-danger p-1 ml-2 eventAction" style="cursor: pointer;" id="declined" data-accepted="3" data-employee="<?php echo $allEvent->user_id; ?>" data-eventid="<?php echo $allEvent->id; ?>" data-title="<?php echo $allEvent->title; ?>"><small>Decline</small></a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php }
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" class="modal fade">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header"><strong id="exampleModalLabel" class="modal-title dashtext-3"><?php echo Translate::t('Make_attention'); ?></strong>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                        <label class="form-control-label"><?php echo Translate::t('Select_Employees', ['ucfirst'=>true]); ?></label>
                        <select name="employees" class="form-control" id="request">
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
                            <option value="furlough"><?php echo Translate::t('Furlough'); ?></option>
                            <option value="unpaid"><?php echo Translate::t('Unpaid'); ?></option>
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
        <!-- Modal-->
        <div id="info_calendar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" class="modal fade text-left show" style="display: none;">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header"><strong id="exampleModalLabel" class="modal-title dashtext-1"><?php echo Translate::t('Info'); ?></strong>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo Translate::t('calendar_info_lead'); ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Tooltip on top"><?php echo Translate::t('Close'); ?></button>
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

    $(function() {
        $( "#startDate" ).datepicker({
            startDate: '-15d',
            autoclose: true
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
</body>
</html>