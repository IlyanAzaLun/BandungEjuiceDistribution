// import DataUser from "../data/DataUser.js";
import Form from "./Form.js";

const form = new Form();
// const data_user_marketing = new DataUser();

const main = () => {
    $(document).ready(function () {
        $('#jstree_demo_div').jstree({
            'core': {
                'check_callback': true
            },
            'plugins': ['types', 'dnd'],
            'types': {
                'default': {
                    'icon': 'fa fa-folder text-warning'
                },
                'html': {
                    'icon': 'fa fa-file-code text-warning'
                },
                'svg': {
                    'icon': 'fa fa-file-image text-warning'
                },
                'css': {
                    'icon': 'fa fa-file-code text-warning'
                },
                'img': {
                    'icon': 'fa fa-file-image text-warning'
                },
                'js': {
                    'icon': 'fa fa-file text-warning'
                }

            }
        });

        $('a.jstree-anchor').click(function () {
            return false;
        })
        // OPEN FORM
        $('li').on('click', 'a[data-id="child"]', function () {
            let id = this.id;
            let HeadCode = $(this).data('code');
            let area_val = $(`#${HeadCode}`).attr('aria-level')
            form.open(HeadCode, function (result) {
                $('div#form').html(result).fadeIn('slow')
            });

            $('html,body').animate({ scrollTop: "0" }, 100);
        })
    })
}
export default main;