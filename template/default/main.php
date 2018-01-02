<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <title><?= $title ?></title>
        <script src="template/<?= $template ?>/js/jquery-2.2.2.min.js"></script>
        <link href="template/<?= $template ?>/css/bootstrap.min.css" rel="stylesheet">
        <link href="template/<?= $template ?>/css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="template/<?= $template ?>/css/fontawesome-all.min.css" rel="stylesheet">
        <link href="template/<?= $template ?>/css/bootstrap-toggle.min.css" rel="stylesheet">
        <link href="template/<?= $template ?>/css/main.css" rel="stylesheet">
    </head>
    <body>
        <div class="container-fluid" role="main">
            <p class="clearfix"></p>

            <div id="alert" class="alert alert-info hidden" role="alert"></div>
            
            <?php if ($arlo == true) { ?>
                <div id="arloPanel" class="panel panel-default">
                    <div id="arloPanelHeading" class="panel-heading">Arlo</div>
                    <div id="arloPanelBody" class="panel-body"></div>
                </div>
                <script>
                    $(document).ready(function () {
                        $("#arloPanelBody").load('template/<?= $template ?>/arlo.php');
                    });
                </script>
            <?php } ?>

            <?php
            if ($wemo == true) {
                require_once __DIR__ . '/../../vendor/autoload.php';
                \a15lam\PhpWemo\Discovery::find(true);
                ?>
                <div id="wemoPanel" class="panel panel-default">
                    <div id="wemoPanelHeading" class="panel-heading">Wemo</div>
                    <div id="wemoPanelBody" class="panel-body"></div>
                </div>
                <script>
                    $(document).ready(function () {
                        $("#wemoPanelBody").load('template/<?= $template ?>/wemo.php');
                    });
                </script>
            <?php } ?>

            <?php if ($monero == true) { ?>
                <div id="moneroPanel" class="panel panel-default">
                    <div id="moneroPanelHeading" class="panel-heading">Monero</div>
                    <div id="moneroPanelBody" class="panel-body"></div>
                </div>
                <script>
                    $(document).ready(function () {
                        $("#moneroPanelBody").load('template/<?= $template ?>/monero.php');
                    });
                </script>
            <?php } ?>

        </div>
        <script type="text/javascript" src="template/<?= $template ?>/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="template/<?= $template ?>/js/bootstrap-toggle.min.js"></script>
    </body>
</html>