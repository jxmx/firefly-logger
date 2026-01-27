<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php print($ff_page_title); ?> | Firefly Field Day Logger</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-icons-1.13.1/bootstrap-icons.min.css" rel="stylesheet">
	<?php
		if(isset($ff_additional_css)){
			print($ff_additional_css);
		}
	?>
	<link href="css/local.css" rel="stylesheet">
	<link rel="shortcut icon" href="img/firefly.svg" sizes="any" type="image/svg+xml">
</head>
<body>
<div class="ff-page-wrapper">
<header class="shadow-sm bg-body pb-2 mb-2">
	<div class="container">
		<div class="row align-items-center">
            <!-- left box -->
			<div class="col-2 align-self-center text-start">
				<img src="img/firefly.svg" height="45px">
			</div>

            <!-- center main box -->
			<div class="col-8 align-self-center text-center">
                <?php print($ff_header_content); ?>
			</div>

            <div class="col-2 align-self-center text-end">
                <ul class="nav nav-pills f-nav-header">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-list nav-bi-big"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="index.php">Logger</a></li>
                            <li><a class="dropdown-item" href="board.php">Display Board</a></li>
                            <li><a class="dropdown-item" href="handkey.php">Handkey Interface</a></li>
                            <li><a class="dropdown-item" href="allqsos.php">All QSOs</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="cabrillo.php">Export Cabrillo</a></li>
                            <li><a class="dropdown-item" href="adif.php">Export ADIF</a></li>
                            <li><a class="dropdown-item" href="qsummary.php">Export Summary</a></li>
                            <li><a class="dropdown-item" href="csv.php">Export CSV</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="configmgr.php">Config Mgr</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-moon-stars-fill nav-bi-big"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><button id="theme-light" class="dropdown-item">
                                <i class="bi bi-sun-fill"></i>&nbsp;Light</button>
                            </li>
                            <li><button id="theme-dark" class="dropdown-item">
                                <i class="bi bi-moon-stars-fill"></i>&nbsp;Dark</button>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
	    </div>
    </div>
</header>
