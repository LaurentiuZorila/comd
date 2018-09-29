<?php
require_once 'core/init.php';
require_once 'vendor/autoload.php';


use Dompdf\Dompdf;

// instantiate and use the dompdf commonClasses
$dompdf = new Dompdf();
$dompdf->loadHtml('
            <div commonClasses="container-fluid">
            <h2 commonClasses="h5 no-margin-bottom">Dashboard</h2>
            </div>
');

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();
$output = $dompdf->output();
// Output the generated PDF to Browser
$dompdf->stream("codexworld",array("Attachment"=>0));