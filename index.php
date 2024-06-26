<?php

// Incluir archivo de conexión
include "inc/open-connection.php";

// Iniciar sesión
session_start();

// Verificar si el usuario está activo
if (empty($_SESSION['active'])) {
    header("location: login.php");
    exit(); // Asegura que el script se detenga después de redirigir
}

?>

<?php include "inc/header.php"; ?>

<body>
    <div class="wrapper">
        <?php require_once("inc/sidebar.php"); ?>

        <div class="main">
            <?php require_once("inc/navbar.php"); ?>

            <main class="content">
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-lg-6 col-xl-3 d-flex">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <div class="card-actions float-right">
                                        <div class="dropdown show">
                                            <a href="#" data-toggle="dropdown" data-display="static">
                                                <i class="align-middle" data-feather="more-horizontal"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#">Action</a>
                                                <a class="dropdown-item" href="#">Another action</a>
                                                <a class="dropdown-item" href="#">Something else here</a>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="card-title mb-0">Income</h5>
                                </div>
                                <div class="card-body my-2">
                                    <div class="row d-flex align-items-center mb-4">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 font-weight-light">
                                                $37.500
                                            </h2>
                                        </div>
                                        <div class="col-4 text-right">
                                            <span class="text-muted">57%</span>
                                        </div>
                                    </div>

                                    <div class="progress progress-sm shadow-sm mb-1">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 57%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-3 d-flex">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <div class="card-actions float-right">
                                        <div class="dropdown show">
                                            <a href="#" data-toggle="dropdown" data-display="static">
                                                <i class="align-middle" data-feather="more-horizontal"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#">Action</a>
                                                <a class="dropdown-item" href="#">Another action</a>
                                                <a class="dropdown-item" href="#">Something else here</a>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="card-title mb-0">Orders</h5>
                                </div>
                                <div class="card-body my-2">
                                    <div class="row d-flex align-items-center mb-4">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 font-weight-light">
                                                3.282
                                            </h2>
                                        </div>
                                        <div class="col-4 text-right">
                                            <span class="text-muted">82%</span>
                                        </div>
                                    </div>
                                    <div class="progress progress-sm shadow-sm mb-1">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 82%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-3 d-flex">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <div class="card-actions float-right">
                                        <div class="dropdown show">
                                            <a href="#" data-toggle="dropdown" data-display="static">
                                                <i class="align-middle" data-feather="more-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#">Action</a>
                                                <a class="dropdown-item" href="#">Another action</a>
                                                <a class="dropdown-item" href="#">Something else here</a>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="card-title mb-0">Activity</h5>
                                </div>
                                <div class="card-body my-2">
                                    <div class="row d-flex align-items-center mb-4">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 font-weight-light">
                                                19.312
                                            </h2>
                                        </div>
                                        <div class="col-4 text-right">
                                            <span class="text-muted">64%</span>
                                        </div>
                                    </div>
                                    <div class="progress progress-sm shadow-sm mb-1">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 64%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-3 d-flex">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <div class="card-actions float-right">
                                        <div class="dropdown show">
                                            <a href="#" data-toggle="dropdown" data-display="static">
                                                <i class="align-middle" data-feather="more-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#">Action</a>
                                                <a class="dropdown-item" href="#">Another action</a>
                                                <a class="dropdown-item" href="#">Something else here</a>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="card-title mb-0">Revenue</h5>
                                </div>
                                <div class="card-body my-2">
                                    <div class="row d-flex align-items-center mb-4">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 font-weight-light">
                                                $82.400
                                            </h2>
                                        </div>
                                        <div class="col-4 text-right">
                                            <span class="text-muted">32%</span>
                                        </div>
                                    </div>
                                    <div class="progress progress-sm shadow-sm mb-1">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 32%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-8 d-flex">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <div class="card-actions float-right">
                                        <div class="dropdown show">
                                            <a href="#" data-toggle="dropdown" data-display="static">
                                                <i class="align-middle" data-feather="more-horizontal"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#">Action</a>
                                                <a class="dropdown-item" href="#">Another action</a>
                                                <a class="dropdown-item" href="#">Something else here</a>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="card-title mb-0">Top Selling Products</h5>
                                </div>
                                <table id="datatables-dashboard-products" class="table table-striped my-0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th class="d-none d-xl-table-cell">Tech</th>
                                            <th class="d-none d-xl-table-cell">License</th>
                                            <th class="d-none d-xl-table-cell">Tickets</th>
                                            <th>Sales</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>AppStack Theme</td>
                                            <td><span class="badge badge-success">HTML</span></td>
                                            <td class="d-none d-xl-table-cell">Single license</td>
                                            <td class="d-none d-xl-table-cell">50</td>
                                            <td class="d-none d-xl-table-cell">720</td>
                                        </tr>
                                        <tr>
                                            <td>Spark Theme</td>
                                            <td><span class="badge badge-danger">Angular</span></td>
                                            <td class="d-none d-xl-table-cell">Single license</td>
                                            <td class="d-none d-xl-table-cell">20</td>
                                            <td class="d-none d-xl-table-cell">540</td>
                                        </tr>
                                        <tr>
                                            <td>Milo Theme</td>
                                            <td><span class="badge badge-warning">React</span></td>
                                            <td class="d-none d-xl-table-cell">Single license</td>
                                            <td class="d-none d-xl-table-cell">40</td>
                                            <td class="d-none d-xl-table-cell">280</td>
                                        </tr>
                                        <tr>
                                            <td>Ada Theme</td>
                                            <td><span class="badge badge-info">Vue</span></td>
                                            <td class="d-none d-xl-table-cell">Single license</td>
                                            <td class="d-none d-xl-table-cell">60</td>
                                            <td class="d-none d-xl-table-cell">610</td>
                                        </tr>
                                        <tr>
                                            <td>Abel Theme</td>
                                            <td><span class="badge badge-danger">Angular</span></td>
                                            <td class="d-none d-xl-table-cell">Single license</td>
                                            <td class="d-none d-xl-table-cell">80</td>
                                            <td class="d-none d-xl-table-cell">150</td>
                                        </tr>
                                        <tr>
                                            <td>Spark Theme</td>
                                            <td><span class="badge badge-success">HTML</span></td>
                                            <td class="d-none d-xl-table-cell">Single license</td>
                                            <td class="d-none d-xl-table-cell">20</td>
                                            <td class="d-none d-xl-table-cell">480</td>
                                        </tr>
                                        <tr>
                                            <td>Libre Theme</td>
                                            <td><span class="badge badge-warning">React</span></td>
                                            <td class="d-none d-xl-table-cell">Single license</td>
                                            <td class="d-none d-xl-table-cell">30</td>
                                            <td class="d-none d-xl-table-cell">280</td>
                                        </tr>
                                        <tr>
                                            <td>Von Theme</td>
                                            <td><span class="badge badge-danger">Angular</span></td>
                                            <td class="d-none d-xl-table-cell">Single license</td>
                                            <td class="d-none d-xl-table-cell">50</td>
                                            <td class="d-none d-xl-table-cell">350</td>
                                        </tr>
                                        <tr>
                                            <td>Material Blog Theme</td>
                                            <td><span class="badge badge-info">Vue</span></td>
                                            <td class="d-none d-xl-table-cell">Single license</td>
                                            <td class="d-none d-xl-table-cell">10</td>
                                            <td class="d-none d-xl-table-cell">480</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <script>
                            document.addEventListener("DOMContentLoaded", function(event) {
                                $('#datatables-dashboard-products').DataTable({
                                    pageLength: 6,
                                    lengthChange: false,
                                    bFilter: false,
                                    autoWidth: false
                                });
                            });
                            </script>
                        </div>
                        <div class="col-12 col-lg-4 d-flex">
                            <div class="card flex-fill w-100">
                                <div class="card-header">
                                    <div class="card-actions float-right">
                                        <div class="dropdown show">
                                            <a href="#" data-toggle="dropdown" data-display="static">
                                                <i class="align-middle" data-feather="more-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#">Action</a>
                                                <a class="dropdown-item" href="#">Another action</a>
                                                <a class="dropdown-item" href="#">Something else here</a>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="card-title mb-0">Sales / Revenue</h5>
                                </div>
                                <div class="card-body d-flex w-100">
                                    <div class="align-self-center chart chart-lg">
                                        <canvas id="chartjs-dashboard-bar"></canvas>
                                    </div>
                                </div>
                            </div>
                            <script>
                            document.addEventListener("DOMContentLoaded", function(event) {
                                // Bar chart
                                new Chart(document.getElementById("chartjs-dashboard-bar"), {
                                    type: 'bar',
                                    data: {
                                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug",
                                            "Sep", "Oct", "Nov", "Dec"
                                        ],
                                        datasets: [{
                                            label: "Last year",
                                            backgroundColor: "#2979ff",
                                            borderColor: "#2979ff",
                                            hoverBackgroundColor: "#2979ff",
                                            hoverBorderColor: "#2979ff",
                                            data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48,
                                                79
                                            ]
                                        }, {
                                            label: "This year",
                                            backgroundColor: "#E8EAED",
                                            borderColor: "#E8EAED",
                                            hoverBackgroundColor: "#E8EAED",
                                            hoverBorderColor: "#E8EAED",
                                            data: [69, 66, 24, 48, 52, 51, 44, 53, 62, 79, 51,
                                                68
                                            ]
                                        }]
                                    },
                                    options: {
                                        maintainAspectRatio: false,
                                        legend: {
                                            display: false
                                        },
                                        scales: {
                                            yAxes: [{
                                                gridLines: {
                                                    display: false
                                                },
                                                stacked: false,
                                                ticks: {
                                                    stepSize: 20
                                                }
                                            }],
                                            xAxes: [{
                                                barPercentage: .75,
                                                categoryPercentage: .5,
                                                stacked: false,
                                                gridLines: {
                                                    color: "transparent"
                                                }
                                            }]
                                        }
                                    }
                                });
                            });
                            </script>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php require_once("inc/footer.php"); ?>
</body>

</html>