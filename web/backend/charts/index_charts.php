<?php
?>

<script>
    var BARCHARTEXMPLE    = $('#charts');
    var barChartExample = new Chart(BARCHARTEXMPLE, {
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
                    label: "<?php echo Profile::getMonthsList()[Input::post('month')]; ?>",
                    backgroundColor: "#864DD9",
                    hoverBackgroundColor: "#864DD9",
                    borderColor: "#864DD9",
                    borderWidth: 0.5,
                    data: [<?php echo $chartValues; ?>],
                },
            ]
        }
    });

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
                    data: [<?php echo Values::sumAll($userFurlough, 'quantity'). ', ' . Values::sumAll($userAbsentees, 'quantity'). ', ' . Values::sumAll($userUnpaid, 'quantity'); ?>],
                    borderWidth: 0,
                    backgroundColor: [
                        "#864DD9",
                        '#723ac3',
                        "#9762e6"
                    ],
                    hoverBackgroundColor: [
                        "#864DD9",
                        '#723ac3',
                        "#9762e6"
                    ]
                }]
        }
    });

    var pieChartExample = {
        responsive: true
    };

</script>
