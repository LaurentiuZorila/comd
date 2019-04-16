<?php
?>
<script>
    var ABSENTEESCHART    = $('#absenteesPieChart');
    var absentees_Pie_Chart = new Chart(ABSENTEESCHART, {
        type: 'pie',
        options: {
            legend: {
                display: true
            }
        },
        data: {
            labels: <?php echo $absenteesChartLabel; ?>,
            datasets: [
                {
                    data: [<?php echo $absenteesChartValues ?>],
                    borderWidth: 0,
                    backgroundColor: [
                        <?php echo $absenteesColors; ?>
                    ],
                    hoverBackgroundColor: [
                        <?php echo $absenteesColors; ?>
                    ]
                }]
        }
    });
</script>
<script>
    var FURLOUGHCHART    = $('#furloughChart');
    var furlough_Pie_Chart = new Chart(FURLOUGHCHART, {
        type: 'pie',
        options: {
            legend: {
                display: true
            }
        },
        data: {
            labels: <?php echo $furloughChartLabel; ?>,
            datasets: [
                {
                    data: [<?php echo $furloughChartValues ?>],
                    borderWidth: 0,
                    backgroundColor: [
                        <?php echo $furloughColors; ?>
                    ],
                    hoverBackgroundColor: [
                        <?php echo $furloughColors; ?>
                    ]
                }]
        }
    });
</script>
<script>
    var UNPAIDCHART    = $('#unpaidChart');
    var unpaid_Pie_Chart = new Chart(UNPAIDCHART, {
        type: 'pie',
        options: {
            legend: {
                display: true
            }
        },
        data: {
            labels: <?php echo $unpaidChartLabel; ?>,
            datasets: [
                {
                    data: [<?php echo $unpaidChartValues; ?>],
                    borderWidth: 0,
                    backgroundColor: [
                        <?php echo $unpaidColors; ?>
                    ],
                    hoverBackgroundColor: [
                        <?php echo $unpaidColors; ?>
                    ]
                }]
        }
    });
</script>
<script>
    var employees_chart = $('#employees_chart');
    var employeesChart = new Chart(employees_chart, {
        type: 'line',
        options: {
            animation: {
                duration: 1000,
                easing: 'linear',
            },
            legend: {
                labels:{
                    fontColor:"#777",
                    fontSize: 12
                }
            },
            scales: {
                xAxes: [{
                    display: true,
                    gridLines: {
                        color: 'transparent'
                    }
                }],
                yAxes: [{
                    display: true,
                    gridLines: {
                        color: 'transparent'
                    }
                }]
            }
        },
        data: {
            labels: <?php echo $chartLabels; ?>,
            datasets: [
                {
                    label: "<?php if (Input::exists()) { echo Translate::t(Input::post('table'), ['ucfirst' => true]); } ?>",
                    fill: true,
                    lineTension: 0,
                    backgroundColor: "rgba(134, 77, 217, 088)",
                    borderColor: "rgba(134, 77, 217, 088)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    borderWidth: 1,
                    pointBorderColor: "rgba(134, 77, 217, 0.88)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(134, 77, 217, 0.88)",
                    pointHoverBorderColor: "rgba(134, 77, 217, 0.88)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [<?php echo $chartValues; ?>],
                    spanGaps: false,
                }
            ]
        }
    });
</script>
<script>
    var MEDICALCHART    = $('#medicalChart');
    var medical_Pie_Chart = new Chart(MEDICALCHART, {
        type: 'pie',
        options: {
            legend: {
                display: false
            }
        },
        data: {
            labels: <?php echo $medicalChartLabel; ?>,
            datasets: [
                {
                    data: [<?php echo $medicalChartValues; ?>],
                    borderWidth: 0,
                    backgroundColor: [
                        <?php echo $medicalColors; ?>
                    ],
                    hoverBackgroundColor: [
                        <?php echo $medicalColors; ?>
                    ]
                }]
        }
    });
</script>
