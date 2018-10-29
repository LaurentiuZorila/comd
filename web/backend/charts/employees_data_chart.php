<?php
?>

<script>
    var LINECHART = $('#all_data_line');
    var employeesLineChart = new Chart(LINECHART, {
        type: 'line',
        options: {
            legend: {labels:{fontColor:"#777", fontSize: 14}},
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
            labels: <?php echo $chartLabels; ?>,
            datasets: [
                {
                    label: "<?php echo Common::getMonths($lang)[$month]; ?>",
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
                    pointRadius: 3,
                    pointHitRadius: 10,
                    data: [<?php echo $chartValues; ?>],
                    spanGaps: false
                }]
        }
    });

    var BARCHART = $('#all_data_bar');
    var employeesBarChart = new Chart(BARCHART, {
        type: 'bar',
        options: {
            legend: {labels:{fontColor:"#777", fontSize: 14}},
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
            labels: <?php echo $chartLabels; ?>,
            datasets: [
                {
                    label: "<?php echo Common::getMonths($lang)[$month]; ?>",
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
                    pointRadius: 3,
                    pointHitRadius: 10,
                    data: [<?php echo $chartValues; ?>],
                    spanGaps: false
                }]
        }
    });
</script>
