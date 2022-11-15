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
                    'type': 'balance_sheet'
                },
                async: true,
                success: function (res) {
                    console.log(res.data);
                }
            })
        }
    })
};
export default main;