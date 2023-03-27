$(function(){
    $('.nav-link').click(function(event){
        event.preventDefault();
        let page = $(this).attr('href').substring(1);
        $('#main').load(`pages/${page}.html`, function(){
            $('.datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "controller/retrieveRecord.php"
            });

            if(page == 'profile'){
                $.getJSON("inc/all.json", function(data) {
                    data.sort(function(a, b) {
                        var labelA = a.name.common.toUpperCase();
                        var labelB = b.name.common.toUpperCase();
                        if (labelA < labelB) {
                            return -1;
                        }
                        if (labelA > labelB) {
                            return 1;
                        }
                        return 0;
                    });
                    $.each(data, function(index, item) {
                        var selected = item.name.common === "Philippines" ? "selected" : "";
                        $("#nationality").append("<option value='" + item.name.common + "' " + selected + ">" + item.name.common + "</option>");
                    });
                });

                $.validator.addMethod('regex', function(value, element, regexp) {
                    return this.optional(element) || regexp.test(value);
                }, 'Please enter a valid value.');

                $('#myForm').validate({
                    rules: {
                        contact: {
                            number: true,
                            regex: /^09\d{9}$/
                        }
                    },
                    messages: {
                        contact: {
                            number: 'Please enter a valid mobile number',
                            regex: 'Start with 09*********'
                        },
                        bodyTemp: {
                            required: true,
                            number: true,
                            step: 0.01
                        },
                    },
                    submitHandler: function(form) {
                        var url = ($('#submitBtn').html()!=='Update') ? "controller/addRecord.php":"controller/updateRecord.php";
                        var msg = ($('#submitBtn').html()!=='Update') ? "Record added successfully":"Record updated successfully";
                        // This code is executed when the form is valid and is submitted
                        $.ajax({
                            url: url,
                            type: "POST",
                            data: $(form).serialize(),
                            success: function(data) {
                                // Refresh the DataTable object
                                $('.datatable').DataTable().ajax.reload();

                                // close the modal
                                $("#addProfileModal").modal("hide");

                                // show success message
                                toast(msg);

                            }
                        });
                    }
                });

                // Check if the form is valid when the Submit button is clicked
                $('#submitBtn').click(function() {
                    if ($('#myForm').valid()) {
                        // Submit the form if it is valid
                        $('#myForm').submit();
                    }
                });
            }

            else if(page == 'dashboard') {
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
                        console.log(response.countryData)

                        var countries = $.map(response.countryData, function(arr) {
                            return arr[0];
                        });

                        var countriesData = $.map(response.countryData, function(arr) {
                            return arr[1];
                        });
                        console.log(countries,countriesData)
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
                            yaxis: {
                                title: {
                                    text: '$ (thousands)'
                                }
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




                //pie chart
                // new Chart($('#pieChart'), {
                //     type: 'pie',
                //     data: {
                //         labels: [
                //             'Red',
                //             'Blue',
                //             'Yellow'
                //         ],
                //         datasets: [{
                //             label: 'My First Dataset',
                //             data: [300, 50, 100],
                //             backgroundColor: [
                //                 'rgb(255, 99, 132)',
                //                 'rgb(54, 162, 235)',
                //                 'rgb(255, 205, 86)'
                //             ],
                //             hoverOffset: 4
                //         }]
                //     }
                // });

                //doughnut chart
                // new Chart($('#doughnutChart'), {
                //     type: 'doughnut',
                //     data: {
                //         labels: [
                //             'Red',
                //             'Blue',
                //             'Yellow'
                //         ],
                //         datasets: [{
                //             label: 'My First Dataset',
                //             data: [300, 50, 100],
                //             backgroundColor: [
                //                 'rgb(255, 99, 132)',
                //                 'rgb(54, 162, 235)',
                //                 'rgb(255, 205, 86)'
                //             ],
                //             hoverOffset: 4
                //         }]
                //     }
                // });

                <!-- End Doughnut CHart -->

                //doughnut chart
                // new Chart($('#nationalitytChart'), {
                //     type: 'doughnut',
                //     data: {
                //         labels: [
                //             'Red',
                //             'Blue',
                //             'Yellow'
                //         ],
                //         datasets: [{
                //             label: 'My First Dataset',
                //             data: [300, 50, 100],
                //             backgroundColor: [
                //                 'rgb(255, 99, 132)',
                //                 'rgb(54, 162, 235)',
                //                 'rgb(255, 205, 86)'
                //             ],
                //             hoverOffset: 4
                //         }]
                //     }
                // });

                <!-- End Doughnut CHart -->
            }

        });
    });
});