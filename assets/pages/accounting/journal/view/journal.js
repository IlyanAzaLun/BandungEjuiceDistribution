const main = () => {
    $(document).ready(function () {
        'use strict'
        let lastKey, commodity_type_value;

        const data = [
            { "code": null, "account": null, "debit": null, "credit": null, "description": null },
        ];

        const container = document.querySelector('#handsontable');
        const hot = new Handsontable(container, {
            data: data,
            rowHeaders: true,
            contextMenu: true,
            multiColumnSorting: true,
            filters: true,
            rowHeaders: true,
            manualColumnResize: true,
            stretchH: 'all',
            licenseKey: "non-commercial-and-evaluation",
            width: '100%',
            colWidths: [50, 200, 100, 100, 200],
            colHeaders: ["Code", "Account", "Debit", "Credit", "Description",],
            columns: [
                {
                    data: 'code',
                    readOnly: true
                }, {
                    data: 'account',
                    type: 'dropdown',
                    allowInvalid: true,
                    invalidCellClassName: '',
                    source: function (query, process) {
                        $.ajax({
                            url: location.base + 'master_information/accounting/chart_of_account_list',
                            method: 'POST',
                            dataType: 'JSON',
                            data: {
                                query: query
                            },
                            success: function (response, callback) {
                                // ALL YOU NEED
                                let HeadName = []
                                HeadName['data'] = response.map(({ id, HeadCode, HeadName, PHeadName, PHeadCode }) => `${id}/${HeadCode}-${HeadName}`);
                                process(HeadName.data)
                            }
                        })
                    },
                }, {
                    type: 'numeric',
                    data: 'debit',
                    numericFormat: {
                        pattern: '0,0.00',
                    },
                }, {
                    type: 'numeric',
                    data: 'credit',
                    numericFormat: {
                        pattern: '0,0.00',
                    },
                }, {
                    type: 'text',
                    data: 'description',

                },
            ],
            beforeChange(changes, source) {
                if (changes[0][1] == 'account') {
                    var temp = changes[0][3].split('-');
                    if (temp[1] == null) {
                        hot.setCellMeta(changes[0][0], 1, 'className', 'htInvalid');
                        hot.setDataAtCell(changes[0][0], 0, null)
                    } else {
                        hot.setCellMeta(changes[0][0], 1, 'className', '');
                        hot.setDataAtCell(changes[0][0], 0, temp[0])
                    }
                    hot.setDataAtCell(changes[0][0], 4, temp[1])
                }
            },
            afterChange: function (changes, source) {
                var journal_entry = null;
                var total_debit = 0, total_credit = 0;
                if (changes != null) {
                    journal_entry = JSON.parse(JSON.stringify(commodity_type_value.getData()));
                    $.each(journal_entry, function (index, value) {
                        if (value[0] != '') {
                            if (value[2] != '' && value[2] != null) {
                                total_debit += parseFloat(value[2]);
                            }
                            if (value[3] != '' && value[3] != null) {
                                total_credit += parseFloat(value[3]);
                            }
                        }
                    });
                    $('input[name="journal_entry"]').val(JSON.stringify(commodity_type_value.getData()));
                    $('.total_debit').html(currency(total_debit));
                    $('.total_credit').html(currency(total_credit));
                    $('input[name="amount"]').val(total_debit.toFixed(2));

                }
            },
            afterRemoveRow: function (index, amount, physicalRows, source) {
                var journal_entry = null;
                var total_debit = 0, total_credit = 0;
                journal_entry = JSON.parse(JSON.stringify(commodity_type_value.getData()));
                $.each(journal_entry, function (index, value) {
                    if (value[0] != '') {
                        if (value[2] != '' && value[2] != null) {
                            total_debit += parseFloat(value[2]);
                        }
                        if (value[3] != '' && value[3] != null) {
                            total_credit += parseFloat(value[3]);
                        }
                    }
                });
                $('input[name="journal_entry"]').val(JSON.stringify(commodity_type_value.getData()));
                $('.total_debit').html(currency(total_debit));
                $('.total_credit').html(currency(total_credit));
                $('input[name="amount"]').val(total_debit.toFixed(2));

            },
            afterLoadData: function (sourceData, initialLoad, source) {
                var total_debit = 0, total_credit = 0;
                $.each(sourceData, function (index, value) {
                    if (value['code'] != '') {
                        if (value['debit'] != '' && value['debit'] != null) {
                            total_debit += parseFloat(value['debit']);
                        }
                        if (value['credit'] != '' && value['credit'] != null) {
                            total_credit += parseFloat(value['credit']);
                        }
                    }
                });

                $('input[name="journal_entry"]').val(JSON.stringify(sourceData));
                $('.total_debit').html(currency(total_debit));
                $('.total_credit').html(currency(total_credit));
                $('input[name="amount"]').val(total_debit.toFixed(2));
            }
        });
        commodity_type_value = hot;
        hot.updateSettings({
            afterDocumentKeyDown: function (e) {
                if (lastKey === 'Control' && e.key === 'b') {
                    let row = hot.getSelected()[0][0];
                    hot.alter('insert_row', (row + 1), 1)
                } else if (lastKey === 'Control' && e.key === 'n') {
                    let row = hot.getSelected()[0][0];
                    hot.alter('insert_row', row, 1)
                }
                lastKey = e.key;
            }
        });
        // FUNCTION
        $(document).ready(function () {
            loadDataHansonTable($('input#journal_date').val());
            $('input#journal_date').on('change', function () {
                loadDataHansonTable(this.value)
            })
        })
        function loadDataHansonTable(date) {
            $.ajax({
                url: `${location.base}master_information/accounting`,
                method: 'POST',
                dataType: 'JSON',
                data: {
                    'date': date.split("/").reverse().join("/"),
                    'request': 'journal',
                },
                async: true,
                success: function (res) {
                    hot.loadData(res.data);
                }
            })
        }
    })
};
export default main;