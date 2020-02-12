<?php
/**
 * Created by PhpStorm.
 * User: quand
 * Date: 4/13/2019
 * Time: 2:50 AM
 */
require_once 'vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;
error_reporting(E_ALL);
ini_set('display_errors', '1');
if (!session_id()) session_start();
try {
    $config = Yaml::parseFile('magicKeyConfig.yaml');
    $magicKeyLink = $config["link"]; //link to get another API to get Magic Key
    $magicKeySalt = $config["salt"]; // salt value to gen Magic Key
    $csrf_token = time(); //token is timestamp in second
} catch (Exception $e) {
    echo $e->getMessage();
}


?>
    <!DOCTYPE html>
    <!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
    <!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
    <!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
    <!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width" />

    <title>Amarki Engineering Exercise</title>

    <link rel="stylesheet" type="text/css" href="https://secure.qgiv.com/resources/admin/css/application.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <!--Import jQuery before export.js-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>


    <!--Data Table-->
    <script type="text/javascript"  src=" https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript"  src=" https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>

    <!--Export table buttons-->
    <script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script type="text/javascript"  src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.24/build/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.1/js/buttons.print.min.js"></script>

    <!--Export table button CSS-->

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">
    <style type="text/css">
        .container{ max-width: 1200px; margin: 0 auto; }
        .logo-header{ padding: 2em; }
        .logo{ margin: 0 auto; min-height: 80px; }
        tr.tittle {
        td {
            width: 90px;
        }
        }
    </style>
</head>

    <body>
    <section class="container">
        <div class="logo-header">
            <img class="logo" src="https://amarki.com/wp-content/uploads/2019/01/logo-main.png" alt="Amarki logo"/>
        </div>
        <div class="alert alert-primary" role="alert" id="magic-key">

        </div>

        <div class="data-table-container table-responsive">
            <table id="dataTable" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%"
            ">
            <thead>
            <tr>
                <th class="ui-secondary-color th-sm" style="width: 5%">ID</th>
                <th class="ui-secondary-color th-sm" style="width: 15%">Address</th>
                <th class="ui-secondary-color th-sm" style="width: 5%">Value</th>
                <th class="ui-secondary-color th-sm" style="width: 10%">Listed Date</th>
                <th class="ui-secondary-color th-sm" style="width: 10%">Expiry Data</th>
                <th class="ui-secondary-color th-sm" style="width: 30%">Description</th>
            </tr>
            </thead>
            <tbody>
            <tr class="data-row">
                <td>'</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="description"></td>
            </tr>

            </tbody>
            </table>
        </div>
    </section>
    </body>
    </html>
<script type="text/javascript"  src="https://viralpatel.net/blogs/demo/jquery/jquery.shorten.1.0.js">

</script>
    <script>

        var columnsDef = [
            {
                "title": "Id",
            },
            {
                "title": "Address",
            },
            {
                "title": "Value",
            },
            {
                "title": "Listed Date",
            },
            {
                "title": "Expiry Date",
            },
            {
                "title": "Description",
            },
        ];
        $(document).ready(function () {
            $(".description").shorten({
                "showChars" : 50,
                "moreText"	: "See More",
                "lessText"	: "Less",
            });

            var magic_data = {
                "csrf_token": "<?php echo $csrf_token ?>",
                "salt": "<?php echo $magicKeySalt ?>"
            };
            $.ajax({
                type: "POST",
                dataType: 'JSON',
                url: "<?php echo $magicKeyLink ?>",
                data: magic_data,
                success: function(response)
                {
                    $("#magic-key").append("Magic key today: " + response.magic_key);
                },
                error: function (xhr, error, code) {
                    console.log(xhr);
                    console.log(code);
                    console.log(error);
                },
            });


            $('#dataTable').DataTable({
                language: {
                    searchPlaceholder: "Search by Id, Address and Value"
                },
                "bProcessing": true,
                "serverSide": true,
                "ajax": {
                    url: "src/View/propertyView.php", // json datasource
                    type: "post",  // type of method  , by default would be get
                    error: function (xhr, error, code) {
                        console.log(xhr);
                        console.log(code);
                        console.log(error);
                    },
                },
                "columns": columnsDef,
                stateSave: true,
            });
        });
    </script>
    <style type="text/css">
        #url {
            width: 600px; !important;
        }
    </style>