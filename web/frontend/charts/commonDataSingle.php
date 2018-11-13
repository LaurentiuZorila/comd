<?php
?>


<script>
    var ABSENTEESCHART    = $('#absenteesPieChart');
    var absentees_Pie_Chart = new Chart(ABSENTEESCHART, {
        type: 'pie',
        options: {
            legend: {
                display: true
            }
        },
        data: {
            labels: [
                "Absentees",
                "Team absentees"
            ],
            datasets: [
                {
                    data: [<?php echo $userAbsentees . ', ' . $totalAbsentees; ?>],
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
                display: true
            }
        },
        data: {
            labels: [
                "Furlough",
                "Team furloughs"
            ],
            datasets: [
                {
                    data: [<?php echo $userFurlough . ', ' . $totalFurloughs; ?>],
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
            labels: [
                "Unpaid",
                "Team unpaid"
            ],
            datasets: [
                {
                    data: [<?php echo $userUnpaidDays . ', ' . $totalUnpaid; ?>],
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
    var MEDICALCHART    = $('#medicalChart');
    var medical_Pie_Chart = new Chart(MEDICALCHART, {
        type: 'pie',
        options: {
            legend: {
                display: false
            }
        },
        data: {
            labels: <?php echo $medicalChartLabel; ?>,
            datasets: [
                {
                    data: [<?php echo $medicalChartValues; ?>],
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
