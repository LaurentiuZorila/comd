<?php
require_once 'core/init.php';

$allEmployees = $leadData->records(Params::TBL_EMPLOYEES, ActionCond::where(['offices_id', $lead->officesId()]), ['name', 'id']);

if (Input::existsName('post', 'eventFilter')) {
   $eventMonth  = Input::post('event_month');
   $eventStatus = Input::post('event_status');

   if (!empty($eventMonth) && !empty($eventStatus)) {
       if ($eventStatus != 'all') {
           $where = ActionCond::where([
               ['lead_id', $lead->customerId()],
               ['status', $eventStatus],
               ['start_month', $eventMonth],
               ['lead_id', $lead->customerId()],
               ['status', $eventStatus],
               ['end_month', $eventMonth]
           ], ['AND', 'AND', 'OR', 'AND', 'AND']);
       } elseif ($eventStatus == 'all') {
           $where = ActionCond::where([
               ['lead_id', $lead->customerId()],
               ['start_month', $eventMonth],
               ['lead_id', $lead->customerId()],
               ['end_month', $eventMonth]
           ], ['AND', 'OR', 'AND']);
       } else {
           $where = ActionCond::where([
               ['lead_id', $lead->customerId()],
               ['start_month', $eventMonth],
               ['lead_id', $lead->customerId()],
               ['end_month', $eventMonth]
           ], ['AND', 'OR', 'AND']);
       }
   } else {
       if ($eventStatus !== 'all') {
           $where = ActionCond::where([
               ['lead_id', $lead->customerId()],
               ['status', $eventStatus],
           ]);
       } elseif ($eventStatus == 'all') {
           $where = ActionCond::where([
               'lead_id', $lead->customerId()
           ]);
        }
   }

    $allEvents = $leadData->records(Params::TBL_EVENTS, $where, ['*'], true);
}

if (!Input::existsName('post', 'eventFilter')) {
    $allEvents = $leadData->records(Params::TBL_EVENTS, ActionCond::where(['lead_id', $lead->customerId()]), ['*'], true);
}

?>
<!DOCTYPE html>
<head>
    <?php
    include '../common/includes/head.php';
    ?>
<script src="./../common/vendor/fullcalendar/lib/jquery.min.js"></script>
<script src="./../common/vendor/fullcalendar/lib/moment.min.js"></script>
<script src="./../common/vendor/fullcalendar/fullcalendar.min.js"></script>
<script src="./../common/vendor/fullcalendar/locale-all.js"></script>
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
            var deleteMsg = confirm("Do you really want to delete?");
            if (deleteMsg) {
                $.ajax({
                    type: "POST",
                    url: "./calendar-customer/delete-event-customer.php",
                    data: "&id=" + event.id,
                    success: function (response) {
                        if(parseInt(response) > 0) {
                            $('#calendar').fullCalendar('removeEvents', event.id);
                        }
                        displayMessage("<?php echo Translate::t($lang, 'event_deleted', ['ucfirst'=>true]); ?>");
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


function displayMessage(message) {
    $(".response").html('<section class="eventMessage"><div class="row"><div class="col-lg-12"><div class="alert alert-dismissible fade show badge-success"><p class="text-white mb-0">'+message+'</p></div></div></div></section>');
    setInterval(function() { $(".eventMessage").fadeOut(); }, 2000);
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
<!--    LOADING MODAL-->
    <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" class="modal fade hide">
        <div class="loader loader-3">
            <div class="dot dot1"></div>
            <div class="dot dot2"></div>
            <div class="dot dot3"></div>
        </div>
    </div>

    <!-- Sidebar Navigation end-->
    <div class="page-content">
        <div class="page-header mb-0">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t($lang, 'Calendar'); ?></h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t($lang, 'Home'); ?></a></li>
                <li class="breadcrumb-item active"><?php echo Translate::t($lang, 'Calendar'); ?> </li>
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
                        <div class="block">
                            <p><?php echo Translate::t($lang, 'Calendar_info'); ?></p>
                            <div id="calendar" class="fc fc-bootstrap3 fc-ltr">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card-header" style="background-color: rgb(45, 48, 53);">
                            <h4 class="text-center"><?php echo Translate::t($lang, 'all', ['ucfirst'=>true]) . ' ' . Translate::t($lang, 'event_request', ['strtolower'=>true]); ?></h4>
                        </div>
                        <div class="block">
                            <p>
                                <button class="btn-sm btn-outline-secondary" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="filter">
                                    <?php echo Translate::t($lang, 'Filters'); ?>
                                </button>
                            </p>
                            <form method="post" id="filter" class="collapse mb-1">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <select name="event_status">
                                            <option><?php echo Translate::t($lang, 'Status'); ?></option>
                                            <?php foreach (Params::EVENTS_STATUS as $key => $value) { ?>
                                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            <? } ?>
                                            <option value="all"><?php echo Translate::t($lang, 'all', ['ucfirst' => true]); ?></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-5">
                                        <select name="event_month">
                                            <option value=""><?php echo Translate::t($lang, 'Select_month'); ?></option>
                                            <?php foreach (Common::getMonths($lang) as $key => $value) { ?>
                                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            <? } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-sm-3">
                                        <button name="eventFilter" id="eventFilter" value="<?php echo Translate::t($lang, 'Submit'); ?>" class="btn-sm btn-outline-secondary" type="submit"><?php echo Translate::t($lang, 'Submit'); ?></button>
                                        <input type="hidden" name="<?php echo Tokens::getInputName(); ?>" value="<?php echo Tokens::getSubmitToken(); ?>">
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive" style="height:499px; overflow-y: scroll;">
                                <table class="table">
                                    <thead>
                                    <tr role="row">
                                        <th class="text-primary">Date</th>
                                        <th class="text-primary">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (count($allEvents) > 0) {
                                    foreach ($allEvents as $allEvent) { ?>
                                    <tr>
                                        <td class="text-small">
                                            <p>
                                                <?php
                                                $collapseData = $allEvent->status == 2 ? 'data-target=#collapseExample' . $allEvent->id . ' aria-controls=collapseExample' . $allEvent->id : '';
                                                ?>
                                                <a class="" style="cursor: pointer;" type="button" data-toggle="collapse" <?php echo $collapseData; ?> aria-expanded="false">
                                                    <?php echo $allEvent->days; ?>
                                                </a>
                                            </p>
                                            <div class="collapse" id="collapseExample<?php echo $allEvent->id; ?>">
                                                <div class="p-0 mb-0 mt-0">
                                                    <p class="mb-0"><?php echo $leadData->records(Params::TBL_EMPLOYEES, ActionCond::where(['id', $allEvent->user_id]), ['name'],false)->name; ?></p>
                                                    <p class="mb-0 mt-0"><?php echo Translate::t($lang, 'event_request', ['ucfirst' => true]) . ' ' . Translate::t($lang, 'furlough', ['strtolower' => true]); ?></p>
                                                </div>

                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo Params::EVENTS_STATUS_COLORS[$allEvent->status]; ?>"><?php echo Params::EVENTS_STATUS[$allEvent->status]; ?></span>
                                            <div class="collapse" id="collapseExample<?php echo $allEvent->id; ?>">
                                                <div class="btn-group btn-group-sm mt-3" role="group" aria-label="Basic example">
                                                    <button type="button" class="btn-sm btn-primary p-1 eventAction" id="accepted" data-accepted="1" data-employee="<?php echo $allEvent->user_id; ?>" data-eventid="<?php echo $allEvent->id; ?>"><small>Accept</small></button>
                                                    <button type="button" class="btn-sm btn-outline-secondary p-0 ml-2 eventAction" id="declined" data-accepted="3" data-employee="<?php echo $allEvent->user_id; ?>" data-eventid="<?php echo $allEvent->id; ?>"><small>Decline</small></button>
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
                    <div class="modal-header"><strong id="exampleModalLabel" class="modal-title dashtext-3"><?php echo Translate::t($lang, 'Make_attention'); ?></strong>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                        <label class="form-control-label"><?php echo Translate::t($lang, 'Select_Employees', ['ucfirst'=>true]); ?></label>
                        <select name="employees" class="form-control" id="request">
                            <option value=""></option>
                            <?php foreach ($allEmployees as $employees) { ?>
                                <option value="<?php echo $employees->id; ?>"><?php echo $employees->name; ?></option>
                            <?php } ?>
                        </select>
                        </div>
                        <div class="form-group">
                        <label class="form-control-label"><?php echo Translate::t($lang, 'event_request', ['ucfirst'=>true]); ?></label>
                        <select name="request" class="form-control" id="request">
                            <option value=""></option>
                            <option value="Furlough"><?php echo Translate::t($lang, 'Furlough'); ?></option>
                            <option value="Unpaid"><?php echo Translate::t($lang, 'Unpaid'); ?></option>
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
    </div>
</div>
<?php
include '../common/includes/footer.php';
?>
<script src="./../common/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="./../common/js/front.js"></script>
<script>

    $(' .eventAction ').on('click', function () {
        var $this = $(this);
        var employeeId = $this.data("employee");
        var eventId    = $this.data("eventid");
        var statusEvent = $this.data("accepted");

        if (employeeId && eventId) {
            $.ajax({
                url: "./calendar-customer/update-event-customer.php",
                dataType: 'Json',
                data: {
                    'eventId': eventId,
                    'employeeId': employeeId,
                    'statusEvent': statusEvent
                },
                success: function(data) {
                    $.each(data, function(key, value) {
                        if (value === "Success") {
                            displayMessage("<?php echo Translate::t($lang, 'event_updated', ['ucfirst'=>true]); ?>");
                            setTimeout(function(){
                                location.reload();
                            }, 1000);
                        } else {
                            displayMessage("<?php echo Translate::t($lang, 'Db_error', ['ucfirst'=>true]); ?>");
                        }
                    });
                }
            });
        }
    });

    $('#eventFilter').click(function(){
        $('#myModal').modal('show');
    });

    $(function() {
        $( "#startDate" ).datepicker();
        $( "#endDate" ).datepicker();
    });

</script>
</body>
</html>