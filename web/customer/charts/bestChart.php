<?php
?>

<script>
    var BEST = $('#bestFirst');
    var bestChart = new Chart(BEST, {
        type: 'bar',
        options:
            {
                scales:
                    {
                        xAxes: [{
                            display: false,
                            barPercentage: 0.5,
                            gridLines: {
                                color: 'transparent'
                            },
                        }],
                        yAxes: [{
                            display: true,
                            gridLines: {
                                color: 'transparent'
                            },
                        }],
                    },
                legend: {
                    display: false
                }
            },
        data: {
            labels: <?php echo Common::toJson($best->getChartLabel()); ?>,
            datasets: [
                {
                    label: "<?php echo ucfirst($best->getFirstPriorityTbl(false)); ?>",
                    backgroundColor: [<?php echo $best->getChartColor(true)?>],
                    borderColor: '#723ac3',
                    borderWidth: 0.1,
                    data: [<?php echo $best->getChartValues(); ?>]
                }
            ]
        }
    });

    var BESTSECOND    = $('#bestSecond');
    var bestSecondChart = new Chart(BESTSECOND, {
        type: 'bar',
        options:
            {
                scales:
                    {
                        xAxes: [{
                            display: false,
                            barPercentage: 0.5,
                            gridLines: {
                                color: 'transparent'
                            },
                        }],
                        yAxes: [{
                            display: true,
                            gridLines: {
                                color: 'transparent'
                            },
                        }],
                    },
                legend: {
                    display: false
                }
            },
        data: {
            labels: <?php echo Common::toJson($best->getChartLabel(false)); ?>,
            datasets: [
                {
                    label: "<?php echo ucfirst($best->getSecondPriorityTbl(false)); ?>",
                    backgroundColor: [<?php echo $best->getChartColor(false)?>],
                    borderColor: '#EF8C99',
                    borderWidth: 0.1,
                    data: [<?php echo $best->getChartValues(false); ?>]
                }
            ]
        }
    });

</script>
