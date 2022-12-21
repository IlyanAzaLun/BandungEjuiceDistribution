import DataSourceAccount from "../data/DataSourceAccount.js";
const dataSourceAccount = new DataSourceAccount();

const main = () => {
    $(document).ready(function () {
        'use strict'

        $(document).ready(function () {
            loadDataHansonTable($('input#profit_n_loss').val(), $('select#profit_n_loss').val());
            $('input#profit_n_loss, select#profit_n_loss').on('change', function () {
                loadDataHansonTable($('input#profit_n_loss').val(), $('select#profit_n_loss').val())
            });

            // extention
            $(`tbody>tr>td[colspan=5]`).on('click', function () {
                let element = $(this).parent()
                let id = this.textContent.substring(this.textContent.indexOf("[") + 1, this.textContent.lastIndexOf("]"))
                let group = $('select#profit_n_loss').val();
                let date = $('input#profit_n_loss').val();

                dataSourceAccount.account_get(id, date, group, function (output) {
                    let html = '';
                    if (output['data'] != null) {
                        html += `
                        <tr>
                            <th></th>
                            <th class="bg-primary">Tanggal</th>
                            <th class="bg-primary">Kredit</th>
                            <th class="bg-primary">Debit</th>
                            <th class="bg-primary" colspan="3">Description</th>
                        </tr>
                        `;
                    }
                    $.each(output['data'], function (index, item) {
                        html += `
                        <tr>
                            <td></td>
                            <td class="bg-secondary">${item.created_at}</td>
                            <td class="bg-secondary">Rp.${currency(item.credit)}</td>
                            <td class="bg-secondary">Rp.${currency(item.debit)}</td>
                            <td class="bg-secondary" colspan="3">${item.description}</td>
                        </tr>`
                    })
                    element.after(html)
                })
            })
        })
        // FUNCTION
        function loadDataHansonTable(date, group) {
            $.ajax({
                url: `${location.base}master_information/accounting`,
                method: 'POST',
                dataType: 'JSON',
                data: {
                    'date': date,
                    'group': group,
                    'request': 'report',
                    'type': 'profit_n_loss'
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
                    $.each(res.data, function (index, item) {
                        // SET DATA
                        if (item.IsTransaction == 1) { // PENDAPATAN & PENJUALAN
                            $(`tbody>tr>td#${item.HeadCode}>span.currency`).html(`${item.total_debit >= 0 ? currency(item.total_debit) : '(' + currency(Math.abs(item.total_debit)) + ')'};`)
                        }
                        else {
                            $(`tbody>tr>td#${item.HeadCode}>span.currency`).html(`${item.total >= 0 ? currency(item.total) : '(' + currency(Math.abs(item.total)) + ')'};`)
                        }
                        // CONDITION IF CURENT CODE IS NOT SAME WITH NEXT CODE
                        if (curent != item.PHeadCode.substring(0, 2)) {
                            // RESET TO 0
                            _total = 0;
                        }
                        // SET CURENT CODE
                        curent = item.PHeadCode.substring(0, 2);
                        if (item.IsTransaction == 1) { // PENDAPATAN & PENJUALAN
                            _total += Number(item.total_debit);
                            _total_debit += Number(item.total_debit);
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