<?php
?>
<script>
    var last_data_chart    = $('#lastDataChart');
    var lastDataChart = new Chart(last_data_chart, {
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
            labels: <?php echo $lastDataChartLabel; ?>,
            datasets: [
                {
                    label: "<?php echo date('Y') . ' - ' . Common::numberToMonth(date('n'), $lang); ?>",
                    backgroundColor: "#864DD9",
                    hoverBackgroundColor: "#864DD9",
                    borderColor: "#864DD9",
                    borderWidth: 0.5,
                    data: [<?php echo $lastDataChartKey; ?>],
                }
            ]
        }
    });
</script>
