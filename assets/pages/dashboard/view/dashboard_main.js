import DataUser from "../data/DataUser.js";
import DataCustomer from "../data/DataCustomer.js";

const data_customer = new DataCustomer();
const data_user_marketing = new DataUser();

const main = () => {
    $(document).ready(function () {
        'use strict'

        function colorize(opaque, hover, ctx) {
            const v = ctx.parsed;
            const c = v < -50 ? '#D60000'
                : v < 0 ? '#F46300'
                    : v < 50 ? '#0358B6'
                        : '#44DE28';

            const opacity = hover ? 1 - Math.abs(v / 150) - 0.2 : 1 - Math.abs(v / 150);

            return opaque ? c : Utils.transparentize(c, opacity);
        }

        function hoverColorize(ctx) {
            return colorize(false, true, ctx);
        }
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
        // Expedition chart

        var mode = 'index'
        var intersect = true
        var ticksStyle = {
            fontColor: '#495057',
            fontStyle: 'bold'
        }
        var expeditionBarCanvas = document.getElementById('expedition-chart').getContext('2d');
        var expeditionBar = new Chart(expeditionBarCanvas, {
            type: 'bar',
            data: {},
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect
                },
                hover: {
                    mode: mode,
                    intersect: intersect
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        // display: false,
                        gridLines: {
                            display: true,
                            lineWidth: '4px',
                            color: 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks: $.extend({
                            beginAtZero: true,

                            // Include a dollar sign in the ticks
                            callback: function (value, index, values) {
                                if (value >= 1000) {
                                    value /= 1000
                                    value += 'k'
                                }
                                return value
                            }
                        }, ticksStyle)
                    }],
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: true
                        },
                        ticks: ticksStyle
                    }]
                },
                onClick: function (event, data) {
                    console.log(event)
                    console.log(data)
                }
            }
        })
        // Sales chart
        var salesChartCanvas = document.getElementById('revenue-chart-canvas').getContext('2d');

        var salesChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Total Selling Monthly'
                },
            },
            interaction: {
                intersect: true,
            },
            legend: {
                display: true
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
                            return `Rp. ${value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`
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
        $.ajax({
            type: 'POST', //post method
            url: location.base + 'dashboard/monthly_statistic', //ajaxformexample url
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
                display: true
            },
            maintainAspectRatio: false,
            responsive: true,
        }

        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        var pieChart = new Chart(pieChartCanvas, {
            type: 'pie',
            data: {},
            options: pieOptions,
        });

        $.ajax({
            type: 'POST', //post method
            url: location.base + 'dashboard/expense_statment_daily', //ajaxformexample url
            dataType: "json",
            success: function (result, textStatus, jqXHR) {
                $('b#today_total_sales').text(currency(result.datasets[0]['data'][1]))
                $('b#today_total_purchase').text(currency(result.datasets[0]['data'][0]))
                $('b#today_summary').text(currency(((result.datasets[0]['data'][1] - result.datasets[0]['data'][0]))))
            }
        });
        $.ajax({
            type: 'POST', //post method
            url: location.base + 'dashboard/expense_statment_monthly', //ajaxformexample url
            dataType: "json",
            success: function (result, textStatus, jqXHR) {
                pieChart.data = result;
                pieChart.update();

                $('b#monthly_total_sales').text(currency(result.datasets[0]['data'][0]))
                $('b#monthly_total_purchase').text(currency(result.datasets[0]['data'][1]))
            }
        });

        // Sales graph chart
        var salesGraphChartCanvas = $('#line-chart').get(0).getContext('2d');
        //$('#revenue-chart').get(0).getContext('2d');

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

        function request(date = "", data = "") {
            $.ajax({
                type: 'POST', //post method
                url: location.base + 'dashboard/daily_statistic', //ajaxformexample url
                data: {
                    'date': {
                        'startdate': startdate,
                        'enddate': enddate
                    },
                    'user_id': data['user_id'],
                    'user': data['user'],
                    'group_by': data['group_by']
                },
                dataType: "json",
                success: function (result, textStatus, jqXHR) {
                    salesGraphChart.data = result;
                    salesGraphChart.update();
                }
            });
            $.ajax({
                type: 'POST', //post method
                url: location.base + 'dashboard/expedition', //ajaxformexample url
                data: {
                    'date': {
                        'startdate': startdate,
                        'enddate': enddate
                    },
                    'user_id': data['user_id'],
                    'user': data['user'],
                },
                dataType: "json",
                success: function (result, textStatus, jqXHR) {
                    expeditionBar.data = result;
                    expeditionBar.update();
                    $('b#top-item').text(result['items']['item_name'])
                }
            });

        }

        request();

        // USER
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
            const date = $('#custom_graph input#min').val();
            const data = [];
            data['user_id'] = $('#custom_graph input#user_id').val();
            data['user'] = $('#custom_graph input#user').val();
            data['group_by'] = $('#custom_graph select#group_by').val();
            request(date, data);
        })
    })
}
export default main;