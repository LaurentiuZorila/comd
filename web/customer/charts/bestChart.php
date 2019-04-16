<?php
?>
<script>
    var items = Array(
    'linear',
    'easeInQuad',
    'easeOutQuad',
    'easeInOutQuad',
    'easeInCubic',
    'easeOutCubic',
    'easeInOutCubic',
    'easeInQuart',
    'easeOutQuart',
    'easeInOutQuart',
    'easeInQuint',
    'easeOutQuint',
    'easeInOutQuint',
    'easeInSine',
    'easeOutSine',
    'easeInOutSine',
    'easeInExpo',
    'easeOutExpo',
    'easeInOutExpo',
    'easeInCirc',
    'easeOutCirc',
    'easeInOutCirc',
    'easeInElastic',
    'easeOutElastic',
    'easeInOutElastic',
    'easeInBack',
    'easeOutBack',
    'easeInOutBack',
    'easeInBounce',
    'easeOutBounce',
    'easeInOutBounce');

    var item = items[Math.floor(Math.random()*items.length)];
    var BEST = $('#bestFirst');
    var bestChart = new Chart(BEST, {
        type: 'bar',
            options: {
                animation: {
                    duration: 1000,
                    easing: item,
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
            labels: <?php echo $best->chartData(['name'], true, false); ?>,
                datasets: [
                {
                    label: "<?php echo Translate::t($best->getFirstTable(), ['ucfirst']); ?>",
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
                    data: [<?php echo $best->chartData(['average'], false, true); ?>],
                    spanGaps: false
                },
                {
                    label: "<?php echo Translate::t($best->getSecondTable(), ['ucfirst']); ?>",
                    fill: true,
                    lineTension: 0,
                    backgroundColor: "#e95f71",
                    borderColor: "#e95f71",
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
                    data: [<?php echo $best->chartData(['average'], false, false); ?>],
                    spanGaps: false
                }
            ]
        }
    });
</script>
