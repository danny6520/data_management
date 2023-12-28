<!DOCTYPE html>
<html lang="en" ng-app="myApp">
<head>
  <meta charset="UTF-8">
  <title>Coimbatore Bar Chart</title>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 
  <style>
    #chart-container {
      width: 80%;
      margin: auto;
    }
  </style>
</head>
<body ng-controller="MainController">

  <div id="chart-container">
    <canvas id="barChart"></canvas>
  </div>

</body>


<script>
    var app = angular.module('myApp', []);

app.controller('MainController', function ($scope, $http) {
    
    var apiUrl = 'cbe_bar_chart_data.php';
    $http.get(apiUrl)
        .then(function (response) {
            var processedData = processData(response.data);
            createCharts(processedData);
        })
        .catch(function (error) {
            console.error('Error fetching data:', error);
        });
        function processData(data) {
     

        var uniquePollingStationNo = Array.from(new Set(data.map(item => item.Polling_Station_No)));
        var processedData = [];
        uniquePollingStationNo.forEach(function (pollingstationno) {
            var pollingstationnoData = data.filter(item => item.Polling_Station_No === pollingstationno);
            var labels = pollingstationnoData.map(item => item.Candidate_Name);
            var votes = pollingstationnoData.map(item => item.No_of_Votes);
            var party_names = pollingstationnoData.map(item => item.Party_Name);

            processedData.push({
                pollingstationno: pollingstationno,
                labels: labels,
                votes: votes,
                party_names: party_names
            });
        });

        return processedData;
    }

    function createCharts(processedData) {
        var container = document.getElementById('chart-container');
        processedData.forEach(function (data) {
            var chartCanvas = document.createElement('canvas');
            chartCanvas.width = 600;
            chartCanvas.height = 400;
            container.appendChild(chartCanvas);
            var ctx = chartCanvas.getContext('2d');      
            var uniquePartyNames = Array.from(new Set(data.party_names));
            var partynameColors = {};
            uniquePartyNames.forEach(function (partyname, index) {
                partynameColors[partyname] = getTeamColor(index);
            });

            var datasets = [];

            data.party_names.forEach(function (partyname, index) {
                datasets.push({
                    label: data.labels[index] + ' Party Name - ' + partyname,
                    data: [data.votes[index]],
                    backgroundColor: partynameColors[partyname], 
                    borderColor: partynameColors[partyname], 
                    borderWidth: 1
                });
            });

            var barChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Votes'],
                    datasets: datasets
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });

            container.appendChild(document.createElement('hr'));

        });
    }

    
    function getTeamColor(index) {
        var colors = ['#D81159', '#218380', '#FBB13C', '#7E7F9A', '#FBCA7D', '#566D7E', '#D6A9A9', '#838CC7', '#A5CAD2', '#FE5F55'];
        return colors[index % colors.length];
    }
});

</script>


</html>
