<?php
?>


<script>
    var BARCHART1 = $('#upaidChart');
    var barChartHome = new Chart(BARCHART1, {
        type: 'bar',
        options:
            {
                scales:
                    {
                        xAxes: [{
                            display: true,
                            barPercentage: 0.2
                        }],
                        yAxes: [{
                            ticks: {
                                max: 100,
                                min: 0
                            },
                            display: false
                        }],
                    },
                legend: {
                    display: false
                }
            },
        data: {
            labels: <?php echo $chartNames; ?>,
            datasets: [
                {
                    label: "Data Set 1",
                    backgroundColor: [
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99'
                    ],
                    borderColor: [
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99',
                        '#EF8C99'
                    ],
                    borderWidth: 0.3,
                    data: [<?php echo $chartValues; ?>]
                }
            ]
        }
    });


</script>
