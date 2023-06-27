<!doctype html>
<html lang="en" class="light-theme">

<head>
    <link rel="icon" href="/assets/images/logo/Lambang-ITS-2-300x300.png" type="image/x-icon" />
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- loader-->
    <link href="/assets/css/pace.min.css" rel="stylesheet" />
    <script src="/assets/js/pace.min.js"></script>

    <!--plugins-->
    <link href="/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
    <link href="/assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
    <link href="/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />

    <!-- CSS Files -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="/assets/css/icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <!--Theme Styles-->
    <link href="/assets/css/dark-theme.css" rel="stylesheet" />
    <link href="/assets/css/semi-dark.css" rel="stylesheet" />
    <link href="/assets/css/header-colors.css" rel="stylesheet" />

    {{-- bootstrap icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    {{-- datepicker --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    {{-- datatable checkbox --}}
    <link type="text/css"
        href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css"
        rel="stylesheet" />

    {{-- jvectormap --}}
    <link rel="stylesheet" href="/assets/css/jquery-jvectormap-2.0.5.css"/>

    {{-- fuse --}}
    <script src="https://cdn.jsdelivr.net/npm/fuse.js"></script>

    <style>
        .loader_bg {
            position: fixed;
            z-index: 999999;
            background-color: #fff;
            width: 100%;
            height: 100%;
        }

        .loader {
            border: 0 solid transparent;
            border-radius: 50%;
            width: 150px;
            height: 150px;
            position: absolute;
            top: calc(50vh - 75px);
            left: calc(50vw - 75px);
        }

        .loader:before,
        .loader::after {
            content: '';
            border: 1em solid #ff5733;
            border-radius: 50%;
            width: inherit;
            height: inherit;
            position: absolute;
            top: 0;
            left: 0;
            animation: loader 2s linear infinite;
            opacity: 0;
        }

        .loader:before {
            animation-delay: .5s;
        }

        @keyframes loader {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                transform: scale(1);
                opacity: 0;
            }
        }
    </style>
    @yield('css')

    <title>Assessment System</title>
</head>

<body>
    <div class="loader_bg">
        <div class="loader"></div>
    </div>

    <!--start wrapper-->
    <div class="wrapper">
        <!--start sidebar -->
        @include('layouts.sidebar')
        <!--end sidebar -->

        <!--start top header-->
        @include('layouts.header')
        <!--end top header-->


        <!-- start page content wrapper-->
        <div class="page-content-wrapper">
            <!-- start page content-->
            <div class="page-content">
                @can('superadmin')
                    <div
                        class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 {{ Request::is('dashboard') || Request::is('dashboard/*') || Request::is('metadata/*') || Request::is('articleType') || Request::is('proses-metadata/*') || Request::is('worldmap') ? 'd-none' : '' }}">
                        <div class="breadcrumb-title pe-3">
                        </div>
                        <div
                            class="ps-3 {{ Request::is('dashboard') || Request::is('dashboard/*') || Request::is('metadata/*') || Request::is('articleType') || Request::is('proses-metadata/*') || Request::is('worldmap') ? 'd-none' : '' }}">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0 align-items-center">
                                    <li class="breadcrumb-item"><a href="javascript:;">
                                            <ion-icon name="home-outline"></ion-icon>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @endcan
                @can('admin')
                    <div
                        class="page-breadcrumb d-sm-flex align-items-center mb-3 {{ Request::is('dashboard') || Request::is('dashboard/admin/project/') || Request::is('dashboard/reviewer/*') || Request::is('metadata/*') || Request::is('dashboard/projectSummary') || Request::is('articleType') || Request::is('proses-metadata/*') || Request::is('worldmap') ? 'd-none' : '' }}">
                        <div class="breadcrumb-title pe-3">
                            @if (Request::is('dashboard/admin/project/*'))
                                Article Management
                            @elseif (Request::is('dashboard/admin/article/create*'))
                                Add Article
                            @elseif (Request::is('dashboard/admin/article/*'))
                                Edit Article
                            @elseif (Request::is('dashboard/admin/assign*'))
                                Assign Article
                            @elseif (Request::is('dashboard/admin/articleStatus*'))
                                Article Status
                            @endif
                        </div>
                        <div
                            class="ps-3 {{ Request::is('dashboard') || Request::is('dashboard/admin/project/') || Request::is('metadata/*') || Request::is('articleType') || Request::is('proses-metadata/*') || Request::is('worldmap') ? 'd-none' : '' }}">
                            <nav aria-label="breadcrumb"
                                class="{{ Request::is('dashboard/admin/project') || Request::is('dashboard/reviewer/*') || Request::is('dashboard/projectSummary') || Request::is('articleType') || Request::is('proses-metadata/*') || Request::is('worldmap') ? 'd-none' : '' }}">
                                <ol class="breadcrumb mb-0 p-0 align-items-center">
                                    <li class="breadcrumb-item">
                                        <ion-icon name="home-outline"></ion-icon>
                                    </li>
                                    <li class="breadcrumb-item">Project</li>
                                    <li
                                        class="breadcrumb-item {{ Request::is('dashboard/admin/project/*') || Request::is('dashboard/admin/articleStatus*') || Request::is('dashboard/reviewer/*') ? 'active' : '' }}">
                                        @if (Request::is('dashboard/admin/articleStatus*'))
                                            Article Status
                                        @else
                                            Article Management
                                        @endif
                                    </li>
                                    @if (Request::is('dashboard/admin/article/create*'))
                                        <li
                                            class="breadcrumb-item {{ Request::is('dashboard/admin/article/create*') ? 'active' : '' }}">
                                            Add Article</li>
                                    @elseif (Request::is('dashboard/admin/article/*'))
                                        <li
                                            class="breadcrumb-item {{ Request::is('dashboard/admin/article/*') ? 'active' : '' }}">
                                            Edit Article</li>
                                    @elseif (Request::is('dashboard/admin/assign*'))
                                        <li
                                            class="breadcrumb-item {{ Request::is('dashboard/admin/assign*') ? 'active' : '' }}">
                                            Assign Article</li>
                                    @endif
                                </ol>
                            </nav>
                        </div>
                    </div>
                @endcan
                @yield('container')
            </div>
            <!-- end page content-->
        </div>
        <!--end page content wrapper-->

        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top">
            <ion-icon name="arrow-up-outline"></ion-icon>
        </a>
        <!--End Back To Top Button-->

        {{-- <footer class="footer">
            <div class="footer-text">
                Copyright © 2023. All right reserved.
            </div>
        </footer> --}}
    </div>
    <!--end wrapper-->



    <!-- JS Files-->
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="/assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <!--plugins-->
    <script src="/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
    <script src="/assets/js/table-datatable.js"></script>
    <script src="/assets/plugins/select2/js/select2.min.js"></script>
    <script src="/assets/js/form-select2.js"></script>
    <!-- Main JS-->
    <script src="/assets/js/main.js"></script>
    <script type="text/javascript">
        var btnfs = document.getElementById("fullscreen-btn");
        btnfs.style.cursor = "pointer";
        btnfs.addEventListener("click", function() {
            if ((document.fullScreenElement && document.fullScreenElement !== null) ||
                (!document.mozFullScreen && !document.webkitIsFullScreen)) {
                if (document.documentElement.requestFullScreen) {
                    btnfs.classList.remove('bx-fullscreen');
                    btnfs.classList.add('bx-exit-fullscreen');
                    document.documentElement.requestFullScreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                    btnfs.classList.remove('bx-fullscreen');
                    btnfs.classList.add('bx-exit-fullscreen');
                    document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.webkitRequestFullScreen) {
                    btnfs.classList.remove('bx-fullscreen');
                    btnfs.classList.add('bx-exit-fullscreen');
                    document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
                }
            } else {
                if (document.cancelFullScreen) {
                    btnfs.classList.remove('bx-exit-fullscreen');
                    btnfs.classList.add('bx-fullscreen');
                    document.cancelFullScreen();
                } else if (document.mozCancelFullScreen) {
                    btnfs.classList.remove('bx-exit-fullscreen');
                    btnfs.classList.add('bx-fullscreen');
                    document.mozCancelFullScreen();
                } else if (document.webkitCancelFullScreen) {
                    btnfs.classList.remove('bx-exit-fullscreen');
                    btnfs.classList.add('bx-fullscreen');
                    document.webkitCancelFullScreen();
                }
            }
        });

        $(window).on("load", function() {
            $(".loader_bg").fadeOut("slow");
        });
    </script>
    {{-- sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- datepicker --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    {{-- datatable checkbox --}}
    <script type="text/javascript"
        src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
    <script src="/assets/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>
    <script src="/assets/plugins/chartjs/chart.min.js"></script>

    {{-- JvectorMap --}}
    <script src="/assets/js/jquery-jvectormap-2.0.5.min.js"></script>
    <script src="https://jvectormap.com/js/jquery-jvectormap-world-mill.js"></script>
    @yield('script')


</body>

</html>
