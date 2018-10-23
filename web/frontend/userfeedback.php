<?php
require_once 'core/init.php';
$records = new FrontendProfile();
$user    = new FrontendUser();

if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}


if (Input::exists('get')) {
    if (!empty(Input::get('feedback')) && !empty(Input::get('leadId')) && !empty(Input::get('userId'))) {
        $db      = FrontendDB::getInstance();
        $feedback = Input::get('feedback');
        $leadId   = Input::get('leadId');
        $userId   = Input::get('userId');

        $insert = $db->insert(Params::TBL_RATING, [
            'user_id'       => $leadId,
            'employees_id'  => $userId,
            'rating'        => $feedback
        ]);

        if ($insert) {
            Session::put('FeedbackSuccess', 'Thank you for your feedback!');
            Redirect::to('feedback.php');
        } else {
            Session::put('FeedbackFailed', 'Something goes wrong, please try again!');
            Redirect::to('feedback.php');
        }

    } else {
        Redirect::to('feedback.php');
    }
} else {
    Redirect::to('feedback.php');
}

?>



