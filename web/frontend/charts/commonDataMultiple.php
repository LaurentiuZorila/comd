<?php
?>


<script>
    var ABSENTEESCHART    = $('#absenteesPieChart');
    var absentees_Pie_Chart = new Chart(ABSENTEESCHART, {
        type: 'pie',
        options: {
            legend: {
                display: false
            }
        },
        data: {
            labels: <?php echo $absenteesChartLabel; ?>,
            datasets: [
                {
                    data: [<?php echo $absenteesChartValues ?>],
                    borderWidth: 0,
                    backgroundColor: [
                        '#da4d60',
                        "#f28695"
                    ],
                    hoverBackgroundColor: [
                        '#da4d60',
                        "#f28695"
                    ]
                }]
        }
    });
</script>

<script>
    var FURLOUGHCHART    = $('#furloughChart');
    var furlough_Pie_Chart = new Chart(FURLOUGHCHART, {
        type: 'pie',
        options: {
            legend: {
                display: false
            }
        },
        data: {
            labels: <?php echo $furloughChartLabel; ?>,
            datasets: [
                {
                    data: [<?php echo $furloughChartValues ?>],
                    borderWidth: 0,
                    backgroundColor: [
                        '#723ac3',
                        "#a678eb"
                    ],
                    hoverBackgroundColor: [
                        '#723ac3',
                        "#a678eb"
                    ]
                }]
        }
    });
</script>
<script>
    var UNPAIDCHART    = $('#unpaidChart');
    var unpaid_Pie_Chart = new Chart(UNPAIDCHART, {
        type: 'pie',
        options: {
            legend: {
                display: true
            }
        },
        data: {
            labels: <?php echo $unpaidChartLabel; ?>,
            datasets: [
                {
                    data: [<?php echo $unpaidChartValues; ?>],
                    borderWidth: 0,
                    backgroundColor: [
                        '#723ac3',
                        "#a678eb"
                    ],
                    hoverBackgroundColor: [
                        '#723ac3',
                        "#a678eb"
                    ]
                }]
        }
    });
</script>
