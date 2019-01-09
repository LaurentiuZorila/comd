<?php
require_once 'core/init.php';
require_once '../vendor/autoload.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();

$id = Input::get('id');
$days = Input::get('days');
$name = $leadData->records(Params::TBL_EMPLOYEES, AC::where(['id', $id]), ['name'], false)->name;

// Load HTML content
$dompdf->loadHtml('
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="./../common/vendor/bootstrap/css/bootstrap.css">
<style>
    body{
        background:white;
        /* font-size:0.9em !important; */
    }
    .invoice{
        width:970px !important;
        margin:50px auto;
    .invoice-body{
        border-radius:10px;
        padding:25px;
        background:#FFF;
    }
    .invoice-footer{
        padding:15px;
        font-size:0.9em;
        text-align:center;
        color:#999;
    }
    }
</style>
</head>
<body>
<div class="container invoice">
    <div class="invoice-body">
        <div class="row">
            <div class="col-xs-5">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title" style="text-align: center;">Domnule Administrator,</h3>
                    </div>
                    <br /> <br />

                    <div class="panel-body">
                        <p>Subsemnatul '. $name .', salariat al .........................., avand functia de .......................... rog sa-mi aprobati efectuarea concediului de odihna pentru perioada:</p>
                        <ul class="list-unstyled">
                            <li>'. $days .'</li>
                            <li>..........................</li>
                            <li>..........................</li>
                        </ul>
                    </div>
                    <br /> <br />
                    <div class="float-left">
                    Data: ....................
                    <br /><br/> <p>Semnatura: .................</p>
                    <br/>
                    </div>
                    <br /><br /><br /><br />
                    <div class="float-right" style="color: #0a0c0d;">
                    Administrator,
                    <br /><br />....................
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
');

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream("cerere concediu",array("Attachment"=>0));
?>