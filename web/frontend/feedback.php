<?php
require_once 'core/init.php';
$user    = new FrontendUser();
$records = new FrontendProfile();

if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}

/** All leads */
$teamLeads = $records->getLeads(['departments_id', $user->departmentId()], ['name', 'id']);

/** Leads id */
foreach ($teamLeads as $leads) {
    $leadsId[] = $leads->id;
}
/** Get not rated leads  */
if (count($records->getFeedback($user->userId())) > 0) {
    foreach ($records->getFeedback($user->userId()) as $item) {
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
<?php
include '../common/includes/head.php';
?>
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
                <h2 class="h5 no-margin-bottom">Feedback</h2>
            </div>
        </div>
            <?php
            if (Session::exists('FeedbackSuccess') || Session::exists('FeedbackFailed')) {
                include '../common/errors/feedbackMessage.php';
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
                                        echo 'Thanks four your feedback';
                                        } else {
                                           echo 'Give me a feedback';
                                        }
                                ?>
                            </div>
                        </div>
                        <div class="col-lg-3 text-center">
                            <div class="contributions text-monospace text-center">
                                <?php
                                if (in_array($leads->id, $noFeedback)) {
                                    echo 'Rating ' . $records->rating($leads->id) . '/5' . '<br />';
                                    for ($i=1;$i<6;$i++) {
                                        if ($i<= $records->rating($leads->id)) { ?>
                                        <a class="text-secondary" href="#"><span class="fa fa-star checked"></span></a>
                                    <?php } else { ?>
                                        <a class="text-secondary" href="#"><span class="fa fa-star"></span></a>
                                     <?php }
                                    }
                                } else {
                                    for ($i = 1; $i < 6; $i++) { ?>
                                        <a class="text-danger" href="userfeedback.php?feedback=<?php echo $i; ?>&leadId=<?php echo $leads->id; ?>&userId=<?php echo $user->userId(); ?>"><span class="fa fa-star"></span></a>
                                    <?php }
                                }?>
                            </div>
                        </div>
                        <?php
                        if (in_array($leads->id, $noFeedback)) { ?>
                        <div class="col-lg-3 text-center">
                            <div class="contributions text-monospace">
                                <span><?php echo 'Given stars ' . $givenFeedbacks[$leads->id]; ?></span><span class="fa fa-star ml-1 text-danger rating-face" ></span>
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

