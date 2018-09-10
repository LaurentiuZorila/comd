<?php
?>


<script>
    var target_chart   = $('#target_chart');
    var target = new Chart(target_chart, {
        type: 'line',
        options: {
            legend: {labels:{fontColor:"#777", fontSize: 12}},
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
                    }
                }]
            },
        },
        data: {
            labels: <?php echo Js::key($allData); ?>,
            datasets: [
                {
                    label: "Target",
                    fill: true,
                    lineTension: 0,
                    backgroundColor: "rgba(134, 77, 217, 0.88)",
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
                    data: [<?php echo Js::values($allData); ?>],
                    spanGaps: false
                },
                {
                    label: "Data Set Two",
                    fill: true,
                    lineTension: 0,
                    backgroundColor: "rgba(98, 98, 98, 0.5)",
                    borderColor: "rgba(98, 98, 98, 0.5)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    borderWidth: 1,
                    pointBorderColor: "rgba(98, 98, 98, 0.5)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(98, 98, 98, 0.5)",
                    pointHoverBorderColor: "rgba(98, 98, 98, 0.5)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [0, 30, 22, 20, 35, 25, 50],
                    spanGaps: false
                }
            ]
        }
    });

    var LINECHART = $('#lineCahrt');
    var myLineChart = new Chart(LINECHART, {
        type: 'line',
        options: {
            scales: {
                xAxes: [{
                    display: true,
                    gridLines: {
                        display: false
                    }
                }],
                yAxes: [{
                    ticks: {
                        max: 60,
                        min: 10
                    },
                    display: true,
                    gridLines: {
                        display: false
                    }
                }]
            },
            legend: {
                display: legendState
            }
        },
        data: {
            labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9"],
            datasets: [
                {
                    label: "Page Visitors",
                    fill: true,
                    lineTension: 0.2,
                    backgroundColor: "transparent",
                    borderColor: '#864DD9',
                    pointBorderColor: '#864DD9',
                    pointHoverBackgroundColor: '#864DD9',
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    borderWidth: 2,
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 5,
                    pointHoverRadius: 5,
                    pointHoverBorderColor: "#fff",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 0,
                    data: [20, 27, 20, 35, 30, 40, 33, 25, 39],
                    spanGaps: false
                },
                {
                    label: "Page Views",
                    fill: true,
                    lineTension: 0.2,
                    backgroundColor: "transparent",
                    borderColor: "#EF8C99",
                    pointBorderColor: '#EF8C99',
                    pointHoverBackgroundColor: "#EF8C99",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    borderWidth: 2,
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 5,
                    pointHoverRadius: 5,
                    pointHoverBorderColor: "#fff",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [25, 17, 28, 25, 33, 27, 30, 33, 27],
                    spanGaps: false
                }
            ]
        }
    });
</script>
