$(function(){
    loadDashboard();
});

function loadDashboard(){
    $('#main').load(`pages/dashboard.html`, function(){
        // Define the URL of the server-side script that will retrieve the data
        var url = "controller/chart.php";

        // Define the parameters to pass to the server-side script (if any)
        var params = {
            // Insert any parameters to pass here
        };
        // Make the AJAX call
        $.ajax({
            type: "GET", // Or "GET" depending on your server-side script
            url: url,
            dataType: "json", // The expected data type of the response
            success: function(response) {
                // Once the response is received, extract the data for each series
                var vac = response.vaccinatedData;
                var diag = response.diagnosedData;
                var enc = response.encounterData;

                console.log(response.allData.record_count)
                $('#allData h6').html(response.allData.record_count);
                $('#allVaccinated h6').html(response.allVaccinatedData.vaccinated_count);
                $('#allEncounter h6').html(response.allEncounterData.covid_encounter_count);
                $('#allFever h6').html(response.allFeverData.high_temp_count);

                var countries = $.map(response.countryData, function(arr) {
                    return arr[0];
                });

                var countriesData = $.map(response.countryData, function(arr) {
                    return arr[1];
                });
                // Pass the data to the chart and render it
                var columnChart = new ApexCharts($("#columnChart")[0], {
                    series: [{
                        name: 'Vaccinated',
                        data: [vac.below10, vac.age11_20, vac.age21_30, vac.age31_40, vac.age41_50, vac.age51_60, vac.age61_70, vac.age71_80, vac.above80]
                    }, {
                        name: 'COVID-19 Diagnosed',
                        data: [diag.below10, diag.age11_20, diag.age21_30, diag.age31_40, diag.age41_50, diag.age51_60, diag.age61_70, diag.age71_80, diag.above80]
                    }, {
                        name: 'COVID-19 Encounter',
                        data: [enc.below10, enc.age11_20, enc.age21_30, enc.age31_40, enc.age41_50, enc.age51_60, enc.age61_70, enc.age71_80, enc.above80]
                    }],
                    chart: {
                        type: 'bar',
                        height: 350
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: ['Below 10', '11-20', '21-30', '31-40', '41-50', '51-60', '61-70', '71-80', 'Above 80'],
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + " records"
                            }
                        }
                    }
                });
                columnChart.render();

                //bar chart
                var barChart = new ApexCharts($("#barChart")[0], {
                    series: [{
                        data: countriesData
                    }],
                    chart: {
                        type: 'bar',
                        height: 350
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            horizontal: true,
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    xaxis: {
                        categories: countries,
                    }
                });
                barChart.render();
            }
        });
    });
}