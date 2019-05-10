<?php
?>
<script>
    // BAR CHART
    var INDEXBARCHART    = $('#backendIndexBarChart');
    var indexBarChart = new Chart(INDEXBARCHART, {
        type: 'bar',
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
                    display: false,
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
            labels: <?php echo $chartNames; ?>,
            datasets: [
                {
                    label: "<?php echo $chartLabel . '-' . Common::getMonths($lang)[Input::post('month')]; ?>",
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
                },
            ]
        }
    });

    // LINE CHART

    var INDEXLINECHART    = $('#backendIndexLineChart');
    var indexLineChart = new Chart(INDEXLINECHART, {
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
                    display: false,
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
            labels: <?php echo $chartNames; ?>,
            datasets: [
                {
                    label: "<?php echo $chartLabel . '-' . Common::getMonths($lang)[Input::post('month')]; ?>",
                    fill: false,
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
                },
            ]
        }
    });



// PIE CHART

    var PIECHARTEXMPLE    = $('#totalCommonTables');
    var pieChartExample = new Chart(PIECHARTEXMPLE, {
        type: 'pie',
        options: {
            legend: {
                display: true,
            }
        },
        data: {
            labels: [
             <?php echo $pieLabel; ?>
            ],
            datasets: [
                {
                    data: [<?php echo $pieCommonData; ?>],
                    borderWidth: 0,
                    backgroundColor: [
                        <?php echo $pieBgColors; ?>
                    ],
                    hoverBackgroundColor: [
                        <?php echo $pieBgColors; ?>
                    ]
                }]
        }
    });

    var pieChartExample = {
        responsive: true
    };

</script>
