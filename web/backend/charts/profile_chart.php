<?php
?>

<script>
    var PROFILEBARCHART = $('#profile_bar_chart');
    var profilebarchart = new Chart(PROFILEBARCHART, {
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
                    label: "<?php echo $tableForChart . ' - ' . Common::getMonths($lang)[$month] . ' - ' . Input::post('year'); ?>",
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
                    data: [<?php echo $chartValues; ?>],
                    spanGaps: false
                }]
        }
    });

    var PROFILELINECHART = $('#profile_line_chart');
    var profilelinechart = new Chart(PROFILELINECHART, {
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
                    label: "<?php echo $tableForChart . ' - ' . Common::getMonths($lang)[$month] . ' - ' . Input::post('year'); ?>",
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
                    data: [<?php echo $chartValues; ?>],
                    spanGaps: false
                }]
        }
    });
</script>
