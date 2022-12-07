const main = () => {
    $(document).ready(function () {
        'use strict'

        $(document).ready(function () {
            loadDataHansonTable($('input#balance_sheet').val());
            $('input#balance_sheet').on('change', function () {
                loadDataHansonTable(this.value)
            })
        })
        // FUNCTION
        function loadDataHansonTable(date) {
            $.ajax({
                url: `${location.base}master_information/accounting`,
                method: 'POST',
                dataType: 'JSON',
                data: {
                    'date': date,
                    'request': 'report',
                    'type': 'balance_sheet'
                },
                async: true,
                success: function (res) {
                    $(`tbody>tr>td.text-right>span.currency`).text('0;')
                    $(`thead>tr>td.text-right>span.currency`).text('0;')
                    let _total = 0;
                    let _total_debit = 0;
                    let _total_credit = 0;
                    let curent;
                    // LOOPING DATA
                    console.log(res)
                    $.each(res.data, function (index, item) {
                        // SET DATA
                        $(`tbody>tr>td#${item.HeadCode}>span.currency`).html(`${item.total >= 0 ? currency(item.total) : '(' + currency(Math.abs(item.total)) + ')'};`)
                        // CONDITION IF CURENT CODE IS NOT SAME WITH NEXT CODE
                        if (curent != item.PHeadCode.substring(0, 2)) {
                            // RESET TO 0
                            _total = 0;
                        }
                        // SET CURENT CODE
                        curent = item.PHeadCode.substring(0, 2);
                        if (item.isFixedAssetSch == 1 && item.IsDepreciation == 0) { // AKTIVA TETAP, TANPA PENYUSUTAN
                            _total -= item.total_debit - item.total_credit;
                            _total_debit -= Number(item.total_debit);
                            _total_credit += Number(item.total_debit);
                        }
                        else {
                            _total += item.total_debit - item.total_credit;
                            _total_debit += Number(item.total_debit);
                            _total_credit += Number(item.total_credit);
                        }
                        $(`thead>tr>td#${curent}>span.currency`).html(`<b>${(_total >= 0) ? currency(_total) : `(${currency(Math.abs(_total))})`}</b>`)
                    })
                    $('strong#total_debit').text(currency(_total_debit))
                    $('strong#total_credit').text(currency(_total_credit))
                    $('strong#total').text(((_total_debit - _total_credit) >= 0) ? currency(_total_debit - _total_credit) : `(${currency(Math.abs(_total_debit - _total_credit))})`)
                }
            })
        }
    })
};
export default main;