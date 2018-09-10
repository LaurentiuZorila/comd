<?php

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>COMD</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">
    <!-- Custom Font Icons CSS-->
    <link rel="stylesheet" href="css/font.css">
    <!-- Google fonts - Muli-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,700">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="css/custom.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/favicon.ico">
    <!-- Bootstrap select -->
    <!-- Latest compiled and minified CSS -->
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
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
    <div class="page-content" style="padding-bottom: 70px;">
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom">Messages</h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <section>
            <div class="container-fluid mt-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="input-group">
                                    <input placeholder="Message" class="form-control" type="text">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-outline-secondary"><i
                                                class="fa fa-send"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group card-list-group">
                                <div class="list-group-item py-5">
                                    <div class="media">
                                        <div class="media-object avatar avatar-md mr-3">ZL</div>
                                        <div class="media-body">
                                            <div class="media-heading">
                                                <small class="float-right">10 min</small>
                                                <h5 class="text-gray">Nathan Andrews</h5>
                                            </div>
                                            <div class="text-small">One morning, when Gregor Samsa woke from troubled
                                                dreams, he found himself transformed in his bed into a horrible vermin.
                                                He lay on his armour-like back, and if he lifted his head a little he
                                                could see his brown belly, slightly domed and divided by arches into
                                                stiff sections
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item py-5">
                                    <div class="media">
                                        <div class="media-object avatar avatar-md mr-3">ZL</div>
                                        <div class="media-body">
                                            <div class="media-heading">
                                                <small class="float-right text-muted">12 min</small>
                                                <h5>Nathan Andrews</h5>
                                            </div>
                                            <div class="text-small">Samsa was a travelling salesman - and above it there
                                                hung a picture that he had recently cut out of an illustrated magazine
                                                and housed in a nice, gilded frame.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item py-5">
                                    <div class="media">
                                        <div class="media-object avatar avatar-md mr-3">ZL</div>
                                        <div class="media-body">
                                            <div class="media-heading">
                                                <small class="float-right text-muted">34 min</small>
                                                <h5>Nathan Andrews</h5>
                                            </div>
                                            <div class="text-small">He must have tried it a hundred times, shut his eyes
                                                so that he wouldn't have to look at the floundering legs, and only
                                                stopped when he began to feel a mild, dull pain there that he had never
                                                felt before.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <footer class="footer">
            <div class="footer__block block no-margin-bottom">
                <div class="container-fluid text-center">
                    <p class="no-margin-bottom">2018 © Your company. </p>
                </div>
            </div>
        </footer>
    </div>
    <?php
    include 'includes/footer.php';
    ?>
</div>
</div>
<!-- JavaScript files-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/popper.js/umd/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/jquery.cookie/jquery.cookie.js"></script>
<script src="vendor/chart.js/Chart.min.js"></script>
<script src="vendor/jquery-validation/jquery.validate.min.js"></script>
<script src="js/charts-home.js"></script>
<script src="js/front.js"></script>

</body>
</html>