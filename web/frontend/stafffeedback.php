<?php
require_once 'core/init.php';
$frontProfile = new FrontendProfile();
$frontUser    = new FrontendUser();

if (!$frontUser->isLoggedIn()) {
    Redirect::to('login.php');
}
if (Input::exists('get')) {
    if (!empty(Input::get('feedback')) && !empty(Input::get('leadId')) && !empty(Input::get('userId'))) {
        $db       = FrontendDB::getInstance();
        $feedback = Input::get('feedback');
        $leadId   = Input::get('leadId');
        $userId   = Input::get('userId');

        $insert = $db->insert(Params::TBL_RATING, [
            'user_id'       => $leadId,
            'employees_id'  => $userId,
            'rating'        => $feedback
        ]);

        if ($insert) {
            Redirect::to('feedback.php?feedbackOk=success');
        } else {
            Redirect::to('feedback.php?feedbackOk=failed');
        }

    } else {
        Redirect::to('feedback.php');
    }
} else {
    Redirect::to('feedback.php');
}
?>



