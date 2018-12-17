<?php
require_once 'core/init.php';

/** All leads */
$teamLeads = $frontProfile->getLeads(['departments_id', $frontUser->departmentId()], ['name', 'id']);

/** Leads id */
foreach ($teamLeads as $leads) {
    $leadsId[] = $leads->id;
}

if (Input::existsName('get', 'feedbackOk')) {
    Errors::setErrorType('success', Translate::t($lang, 'thk_feedback'));
} elseif (Input::existsName('get', 'feedbackKo')) {
    Errors::setErrorType('danger', Translate::t($lang, 'Db_error'));
}
/** Get not rated leads  */
if (count($frontProfile->getFeedback($frontUser->userId())) > 0) {
    foreach ($frontProfile->getFeedback($frontUser->userId()) as $item) {
        if (in_array($item->user_id, $leadsId)) {
            $noFeedback[] = $item->user_id;
        }
        $givenFeedbacks[$item->user_id] = $item->rating;
    }
} else {
    $noFeedback = [];
    $givenFeedbacks = [];
}
?>

<!DOCTYPE html>
<html>
<head>
<?php
include '../common/includes/head.php';
?>
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
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t($lang, 'feedback'); ?></h2>
            </div>
        </div>
            <?php
            if (Input::exists('get') && Errors::countAllErrors()) {
                include './../common/errors/errors.php';
            }
            ?>
        <section>
            <form method="post">
                <?php
                    $x = 1;
                    foreach ($teamLeads as $leads) {  ?>
                <div class="public-user-block block">
                    <div class="row d-flex align-items-center">
                        <div class="col-lg-3 d-flex align-items-center">
                            <div class="order"><?php echo $x ;?></div>
                            <div class="avatar"> <img src="./../common/img/user.png" alt="..." class="img-fluid"></div><a href="#" class="name"><strong class="d-block"><?php echo $leads->name; ?></strong></a>
                        </div>
                        <div class="col-lg-3 text-center">
                            <div class="contributions">
                                <?php
                                if (in_array($leads->id, $noFeedback)) {
                                        echo Translate::t($lang, 'Feedback_given');
                                        } else {
                                           echo Translate::t($lang, 'give_feedback');
                                        }
                                ?>
                            </div>
                        </div>
                        <div class="col-lg-3 text-center">
                            <div class="contributions text-monospace text-center">
                                <?php
                                if (in_array($leads->id, $noFeedback)) {
                                    echo Translate::t($lang, 'Rating') . ' ' . $frontProfile->rating($leads->id) . '/5' . '<br />';
                                    for ($i=1;$i<6;$i++) {
                                        if ($i<= $frontProfile->rating($leads->id)) { ?>
                                        <a class="text-primary" href="#"><span class="fa fa-star checked"></span></a>
                                    <?php } else { ?>
                                        <a class="text-secondary" href="#"><span class="fa fa-star"></span></a>
                                     <?php }
                                    }
                                } else {
                                    for ($i = 1; $i < 6; $i++) { ?>
                                        <a class="btn-sm btn-outline-primary" href="userfeedback.php?feedback=<?php echo $i; ?>&leadId=<?php echo $leads->id; ?>&userId=<?php echo $frontUser->userId(); ?>"><span class="fa fa-star"></span></a>
                                    <?php }
                                }?>
                            </div>
                        </div>
                        <?php
                        if (in_array($leads->id, $noFeedback)) { ?>
                        <div class="col-lg-3 text-center">
                            <div class="contributions text-monospace">
                                <span><?php echo Translate::t($lang, 'Given_stars') . ' ' . $givenFeedbacks[$leads->id]; ?></span><span class="fa fa-star ml-1 text-danger rating-face" ></span>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php $x++; } ?>
            </form>
        </section>
    </div>
        <?php
        include '../common/includes/footer.php';
        ?>
    </div>
</div>
<!-- JavaScript files-->
<?php
include "./../common/includes/scripts.php";
?>
</body>
</html>

