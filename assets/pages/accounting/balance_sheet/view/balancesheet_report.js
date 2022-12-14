const main = () => {
    $(document).ready(function () {
        'use strict'
        let lastKey, commodity_type_value;

        const data = [
            { "date": null, "account": null, "debit": null, "credit": null, "description": null },
        ];

        const container = document.querySelector('#handsontable');
        const hot = new Handsontable(container, {
            data: data,
            rowHeaders: true,
            contextMenu: true,
            multiColumnSorting: true,
            filters: true,
            height: 550,
            rowHeaders: true,
            manualColumnResize: true,
            stretchH: 'all',
            licenseKey: "non-commercial-and-evaluation",
            width: '100%',
            colWidths: [50, 200, 100, 100, 200],
            colHeaders: ["Date", "Account", "Debit", "Credit", "Description",],
            columns: [
                {
                    data: 'date',
                    type: 'date',
                }, {
                    data: 'account',
                    readOnly: true,
                }, {
                    data: 'debit',
                    type: 'numeric',
                    readOnly: true,
                    numericFormat: {
                        pattern: '0,0.00',
                    },
                }, {
                    data: 'credit',
                    type: 'numeric',
                    readOnly: true,
                    numericFormat: {
                        pattern: '0,0.00',
                    },
                }, {
                    data: 'description',
                    type: 'text',
                    readOnly: true,

                },
            ],
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
        hot.loadData(JSON.parse($('input[name="journal_entry"]').val()))
    })
};
export default main;