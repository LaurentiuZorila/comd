<?php
?>
<script>
    var ABSENTEESCHART    = $('#absenteesPieChart');
    var absentees_Pie_Chart = new Chart(ABSENTEESCHART, {
        type: 'pie',
        options: {
            legend: {
                display: false
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
                display: false
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
                display: false
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
    var employees_chart    = $('#employees_chart');
    var employeesChart = new Chart(employees_chart, {
        type: 'bar',
        options: {
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
                    },
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
        },
        data: {
            labels: <?php echo $chartLabels; ?>,
            datasets: [
                {
                    label: "<?php if (Input::exists()) { echo Translate::t($lang, Input::post('table'), ['ucfirst' => true]); } ?>",
                    backgroundColor: "#864DD9",
                    hoverBackgroundColor: "#864DD9",
                    borderColor: "#864DD9",
                    borderWidth: 0.5,
                    data: [<?php echo $chartValues; ?>],
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
