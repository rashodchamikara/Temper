<?PHP 
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]/app/";
$response = file_get_contents($actual_link);

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Retention curve - Temper</title>

  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/series-label.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>

  <style>
      .highcharts-figure,
        .highcharts-data-table table {
            min-width: 360px;
            max-width: 800px;
            margin: 1em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }

  </style>
</head>
<body>
    
<figure class="highcharts-figure">
    <div id="container"></div>
    
</figure>
<script>
    Highcharts.chart('container', {
                chart: {
                    type: 'spline'
                },
                title: {
                    text: 'Retention curve - Temper'
                },

                yAxis: {
                    title: {
                        text: 'Percentage of users'
                    }
                },

                xAxis: {
                    title: {
                        text: 'Onboarding Flow Steps'
                    },
                    categories: ['0%', '20%', '40%', '50%','70%','90%' ,'99%' ,'100%']
                },

                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },

                plotOptions: {
                    spline: {
                        marker: {
                            enable: false
                        }
                    }
                    
                },
                type: 'bellcurve',
                series: [
                    <?PHP echo $response;?>],



                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }

                });
</script>
</body>
</html>