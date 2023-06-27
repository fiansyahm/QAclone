@extends('layouts.main')
@section('container')
    <div class="card radius-10">
        <div class="card-body">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 row-cols-xxl-4 g-3">
                <div class="col">
                    <div class="card radius-10 shadow-none bg-light-success mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="fs-2 text-success">
                                    <ion-icon name="person-sharp"></ion-icon>
                                </div>
                                <div class="fs-6 ms-auto text-success">
                                    <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-4">
                                <div class="">
                                    <p class="mb-1 text-success">Project as Admin</p>
                                    <h4 class="mb-0 text-success">{{ $project_admin }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 shadow-none bg-light-primary mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="fs-2 text-primary">
                                    <ion-icon name="briefcase-sharp"></ion-icon>
                                </div>
                                <div class="fs-6 ms-auto text-primary">
                                    <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-4">
                                <div class="">
                                    <p class="mb-1 text-primary">Project Assign</p>
                                    <h4 class="mb-0 text-primary">{{ $project_assign }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 shadow-none bg-light-warning mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="fs-2 text-warning">
                                    <ion-icon name="documents-sharp"></ion-icon>
                                </div>
                                <div class="fs-6 ms-auto text-warning">
                                    <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-4">
                                <div class="">
                                    <p class="mb-1 text-warning">Total Article</p>
                                    <h4 class="mb-0 text-warning">{{ $total_article }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 shadow-none bg-light-danger mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="fs-2 text-danger">
                                    <ion-icon name="document-lock-sharp"></ion-icon>
                                </div>
                                <div class="fs-6 ms-auto text-danger">
                                    <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-4">
                                <div class="">
                                    <p class="mb-1 text-danger">Article Assigned</p>
                                    <h4 class="mb-0 text-danger">{{ $assign_article }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->

    <div class="row">
        <div class="col-12 col-lg-8 col-xl-8 d-flex">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <h6 class="mb-0">Assessment Status</h6>
                    </div>
                    <div class="chart-container1">
                        <canvas id="chartProject"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-xl-4 d-flex">
            <div class="card radius-10 overflow-hidden w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <h6 class="mb-0">Article Status</h6>
                    </div>
                    <div class="chart-container6">
                        <div class="piechart-legend">
                            <h2 class="mb-1">{{ $total_article }}</h2>
                            <h6 class="mb-0">Total Article</h6>
                        </div>
                        <canvas id="chartArticle"></canvas>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center border-top">
                        Assessed
                        <span class="badge bg-success rounded-pill">{{ $article_assessed }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Not Assessed
                        <span class="badge bg-danger rounded-pill">{{ $article_not_assessed }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Not Assign
                        <span class="badge bg-warning rounded-pill">{{ $article_not_assign }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        // chart5
        var project_name = {!! json_encode($project_name) !!};
        var project_assessed = {!! json_encode($project_assessed) !!};
        var project_not_assessed = {!! json_encode($project_not_assessed) !!};

        var ctx = document.getElementById('chartProject').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: project_name,
                datasets: [{
                        label: 'Assessed',
                        data: project_assessed,
                        backgroundColor: [
                            '#42ba96'
                        ],
                        /* fill: {
                            target: 'origin',
                            above: 'rgb(146 62 185 / 21%)',   // Area will be red above the origin
                            below: 'rgb(146 62 185 / 21%)'    // And blue below the origin
                          }, */
                        tension: 0.4,
                        borderColor: [
                            '#42ba96'
                        ],
                        borderWidth: 0,
                        borderRadius: 0
                    },
                    {
                        label: 'Not Assessed',
                        data: project_not_assessed,
                        backgroundColor: [
                            '#df4759'
                        ],
                        fill: {
                            target: 'origin',
                            above: 'rgb(24 187 107 / 21%)', // Area will be red above the origin
                            below: 'rgb(24 187 107 / 21%)' // And blue below the origin
                        },
                        tension: 0.4,
                        borderColor: [
                            '#df4759'
                        ],
                        borderWidth: 0,
                        borderRadius: 0
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                barPercentage: 0.7,
                categoryPercentage: 0.35,
                plugins: {
                    legend: {
                        maxWidth: 20,
                        boxHeight: 20,
                        position: 'bottom',
                        display: true,
                    }
                },
                scales: {
                    x: {
                        stacked: false,
                        beginAtZero: true
                    },
                    y: {
                        stacked: false,
                        beginAtZero: true
                    }
                }
            }
        });


        var article_assessed = {!! json_encode($article_assessed) !!};
        var article_not_assessed = {!! json_encode($article_not_assessed) !!};
        var article_not_assign = {!! json_encode($article_not_assign) !!};

        // chart6
        var ctx = document.getElementById('chartArticle').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Assessed', 'Not Assessed', 'Not Assign'],
                datasets: [{
                    data: [article_assessed, article_not_assessed, article_not_assign],
                    backgroundColor: [
                        '#42ba96',
                        '#df4759',
                        '#f0ad4e'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: 105,
                plugins: {
                    legend: {
                        display: false,
                    }
                }

            }
        });
    </script>
@endsection
