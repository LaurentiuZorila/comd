<?php
?>

<script>

    // BAR CHART

    var INDEXBARCHART    = $('#backendIndexBarChart');
    var indexBarChart = new Chart(INDEXBARCHART, {
        type: 'bar',
        options: {
            scales: {
                xAxes: [{
                    display: true,
                    gridLines: {
                        color: 'transparent'
                    },
                    ticks: {
                        autoSkip: false
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
            labels: <?php echo $chartNames; ?>,
            datasets: [
                {
                    label: "<?php echo ucfirst(Input::post('table')) . '-' . Common::getMonths($lang)[Input::post('month')]; ?>",
                    backgroundColor: "#864DD9",
                    hoverBackgroundColor: "#864DD9",
                    borderColor: "#864DD9",
                    borderWidth: 0.5,
                    data: [<?php echo $chartValues; ?>],
                },
            ]
        }
    });

    // LINE CHART

    var INDEXLINECHART    = $('#backendIndexLineChart');
    var indexLineChart = new Chart(INDEXLINECHART, {
        type: 'line',
        options: {
            scales: {
                xAxes: [{
                    display: true,
                    gridLines: {
                        color: 'transparent'
                    },
                    ticks: {
                        autoSkip: false
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
            labels: <?php echo $chartNames; ?>,
            datasets: [
                {
                    label: "<?php echo ucfirst(Input::post('table')) . '-' . Common::getMonths($lang)[Input::post('month')]; ?>",
                    backgroundColor: "#864DD9",
                    hoverBackgroundColor: "#864DD9",
                    borderColor: "#864DD9",
                    borderWidth: 0.5,
                    data: [<?php echo $chartValues; ?>],
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
                "Furlough",
                "Absentees",
                "Unpaid"
            ],
            datasets: [
                {
                    data: [<?php echo $pieCommonData; ?>],
                    borderWidth: 0,
                    backgroundColor: [
                        "#864DD9",
                        '#723ac3',
                        "#9762e6",
                        "#9f58ff"
                    ],
                    hoverBackgroundColor: [
                        "#864DD9",
                        '#723ac3',
                        "#9762e6",
                        "#a357ff"
                    ]
                }]
        }
    });

    var pieChartExample = {
        responsive: true
    };

</script>
