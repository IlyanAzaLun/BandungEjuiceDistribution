import DataUser from "../data/DataUser.js";

const data_user_marketing = new DataUser();

const main = () => {
    $(document).ready(function () {
        'use strict'

        /* jQueryKnob */
        $('.knob').knob()

        // Make the dashboard widgets sortable Using jquery UI
        $('.connectedSortable').sortable({
            placeholder: 'sort-highlight',
            connectWith: '.connectedSortable',
            handle: '.card-header, .nav-tabs',
            forcePlaceholderSize: true,
            zIndex: 999999
        })
        $('.connectedSortable .card-header, .connectedSortable .nav-tabs-custom').css('cursor', 'move')

        // Sales chart
        var salesChartCanvas = document.getElementById('revenue-chart-canvas').getContext('2d');

        var salesChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        display: true,
                    }
                }],
                yAxes: [{
                    ticks: {
                        callback: function (value, index, values) {
                            if (1000 < parseInt(value)) {
                                return `Rp. ${value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")} Juta`
                            }
                            else {
                                return `Rp. ${value} Juta`;
                            }
                        }
                    },
                    gridLines: {
                        display: true,
                    }
                }]
            }
        }

        // This will get the first returned node in the jQuery collection.
        var salesChart = new Chart(salesChartCanvas, {
            type: 'line',
            data: {},
            options: salesChartOptions
        });
        var salesChartData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [
                {
                    label: 'Digital Goods',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    pointRadius: false,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: [28, 48, 40, 19, 86, 27, 90]
                },
                {
                    label: 'Electronics',
                    backgroundColor: 'rgba(210, 214, 222, 1)',
                    borderColor: 'rgba(210, 214, 222, 1)',
                    pointRadius: false,
                    pointColor: 'rgba(210, 214, 222, 1)',
                    pointStrokeColor: '#c1c7d1',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data: [65, 59, 80, 81, 56, 55, 40]
                },
            ]
        }
        $.ajax({
            type: 'POST', //post method
            url: location.base + 'invoice/sale/monthly_statistic', //ajaxformexample url
            dataType: "json",
            success: function (result, textStatus, jqXHR) {
                salesChart.data = result;
                salesChart.update();
            }
        });


        // Donut Chart
        var pieChartCanvas = document.getElementById('sales-chart-canvas').getContext('2d')
        var pieData = {
            labels: [
                'Instore Sales',
                'Download Sales',
                'Mail-Order Sales',
            ],
            datasets: [
                {
                    data: [30, 12, 20],
                    backgroundColor: ['#f56954', '#00a65a', '#f39c12'],
                }
            ]
        }
        var pieOptions = {
            legend: {
                display: false
            },
            maintainAspectRatio: false,
            responsive: true,
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        var pieChart = new Chart(pieChartCanvas, {
            type: 'doughnut',
            data: pieData,
            options: pieOptions
        });

        // Sales graph chart
        var salesGraphChartCanvas = $('#line-chart').get(0).getContext('2d');
        //$('#revenue-chart').get(0).getContext('2d');

        var salesGraphChartData = {
            labels: ['2011 Q1', '2011 Q2', '2011 Q3', '2011 Q4', '2012 Q1', '2012 Q2', '2012 Q3', '2012 Q4', '2013 Q1', '2013 Q2'],
            datasets: [
                {
                    label: 'Digital Goods',
                    fill: false,
                    borderWidth: 2,
                    lineTension: 0,
                    spanGaps: true,
                    pointRadius: 3,
                    pointHoverRadius: 7,
                    data: ["2666", "2778", "4912", "3767", "6810", "5670", "4820", "15073", "10687", "8432"]
                }
            ]
        }

        var salesGraphChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: false,
            },
            scales: {
                xAxes: [{
                    ticks: {
                    },
                    gridLines: {
                        display: true,
                        drawBorder: false,
                    }
                }],
                yAxes: [{
                    ticks: {
                        stepSize: 5000,
                        callback: function (value, index, values) {
                            // if (parseInt(value) >= 1000) {
                            //     return 'Rp. ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            // }
                            if (1000000 < parseInt(value)) {
                                return `Rp. ${Math.round((parseInt(value) / 1000000), 1).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")} Juta`
                            }
                            else {
                                return 'Rp. ' + value;
                            }
                        }
                    },
                    gridLines: {
                        display: true,
                        drawBorder: false,
                    }
                }]
            }
        }

        // This will get the first returned node in the jQuery collection.
        var salesGraphChart = new Chart(salesGraphChartCanvas, {
            type: 'line',
            data: {},
            options: salesGraphChartOptions
        })

        $.ajax({
            type: 'POST', //post method
            url: location.base + 'invoice/sale/daily_statistic', //ajaxformexample url
            dataType: "json",
            success: function (result, textStatus, jqXHR) {
                salesGraphChart.data = result;
                salesGraphChart.update();
            }
        });

        $(document).on('keyup', 'input#user', function () {
            let valueElement = $(this).val();
            let selfElement = $(this);
            data_user_marketing.user_info_search(valueElement, function (data) {
                let result = data.map(({
                    id, name, username,
                }) => [
                        id, name, username,
                    ]
                );
                $(`input#${selfElement.attr('id')}`).autocomplete({
                    source: result,
                    focus: function (event, ui) {
                        $('input#user_id').val(ui.item[0])
                        $('input#user').val(ui.item[1])
                        return false;
                    },
                    select: function (event, ui) {
                        $('input#user_id').val(ui.item[0])
                        $('input#user').val(ui.item[1])
                        return false;
                    }
                }).data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $('<li>').data("item.autocomplete", item)
                        .append(`<div>${item[1]}</div>`).appendTo(ul)
                }
            })
        });

        $(document).on('click', 'button#search', function () {

        })
    })
}
export default main;