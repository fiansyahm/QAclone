<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="/assets/images/logo/Lambang-ITS-2-300x300.png" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text text-center">Assessment System</h4>
        </div>
        <div class="toggle-icon ms-auto">
            <ion-icon name="menu-sharp"></ion-icon>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="/dashboard">
                <div class="parent-icon">
                    <ion-icon name="home-sharp"></ion-icon>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        @can('superadmin')
            <li class="menu-label">Administrator</li>
            <li>
                <a href="/dashboard/user">
                    <div class="parent-icon">
                        <ion-icon name="person-add-sharp"></ion-icon>
                    </div>
                    <div class="menu-title">User Management</div>
                </a>
            </li>
            <li>
                <a href="/dashboard/project">
                    <div class="parent-icon">
                        <ion-icon name="briefcase-sharp"></ion-icon>
                    </div>
                    <div class="menu-title">Project Management</div>
                </a>
            </li>
        @endcan
        @can('admin')
            <li class="menu-label">Admin Projek</li>
            <li>
                <a href="/dashboard/admin/project">
                    <div class="parent-icon">
                        <ion-icon name="briefcase-sharp"></ion-icon>
                    </div>
                    <div class="menu-title">Project Management</div>
                </a>
            </li>
        @endcan
        @can('reviewer')
            @if (auth()->user()->is_reviewer == true && auth()->user()->is_admin == false)
                <li class="menu-label">Assessor</li>
            @endif
            <li>
                <a class="has-arrow" href="#">
                    <div class="parent-icon">
                        <ion-icon name="newspaper-sharp"></ion-icon>
                    </div>
                    <div class="menu-title">Quality Assessment</div>
                </a>
                <ul>
                    <li>
                        <a href="/dashboard/reviewer/assessment">
                            <div class="parent-icon">
                                <ion-icon name="close-circle"></ion-icon>
                            </div>
                            <div class="menu-title">Article Not Complete</div>
                        </a>
                    </li>
                    <li>
                        <a href="/dashboard/reviewer/assessed">
                            <div class="parent-icon">
                                <ion-icon name="checkmark-circle"></ion-icon>
                            </div>
                            <div class="menu-title">Article Completed</div>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- izin nambahin 2 fitur -->
            
        @endcan
        @can('projectSummary')
            <li>
                <a class="has-arrow" href="#">
                    <div class="parent-icon">
                        <ion-icon name="stats-chart-sharp"></ion-icon>
                    </div>
                    <div class="menu-title">Summary</div>
                </a>
                <ul>
                    <li>
                        <a href="/metadata/author">
                            <div class="parent-icon">
                                <ion-icon name="people"></ion-icon>
                            </div>
                            <div class="menu-title">Author Summary</div>
                        </a>
                    </li>
        
                    <li>
                        <a href="/metadata/article">
                            <div class="parent-icon">
                                <ion-icon name="book"></ion-icon>
                            </div>
                            <div class="menu-title">Article Summary</div>
                        </a>
                    </li>
        
                    <li>
                        <a href="/dashboard/projectSummary">
                            <div class="parent-icon">
                                <ion-icon name="pie-chart"></ion-icon>
                            </div>
                            <div class="menu-title">Quality Summary</div>
                        </a>
                    </li>

                    <li>
                        <a href="/worldmap">
                            <div class="parent-icon">
                                <ion-icon name="globe-outline"></ion-icon>
                            </div>
                            <div class="menu-title">World Map</div>
                        </a>
                    </li>

                </ul>
            </li>
        @endcan
    <!--end navigation-->
</aside>
