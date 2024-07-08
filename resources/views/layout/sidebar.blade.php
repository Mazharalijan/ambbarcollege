<aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand">
    <!-- Brand Logo -->
    @if(auth()->user()->hasRole('super-admin'))

        <a href="{{route('admin.dashboard')}}" class="brand-link">
            <img src="{{ asset('assets/admin/dist/img/logo-new.jpeg')}}" alt="Parlor Logo"
                 class="brand-image"
                 style="opacity: .8">
            <span class="brand-text font-weight-light"> Ambber Salma College </span>
        </a>
        @elseif(auth()->user()->hasRole('student'))
        <a href="{{route('dashboard')}}" class="brand-link">
            <img src="{{ asset('assets/admin/dist/img/logo-new.jpeg')}}" alt="Parlor Logo"
                 class="brand-image"
                 style="opacity: .8">
            <span class="brand-text font-weight-light"> Ambber Salma College </span>
        </a>
    @endif
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                @if(auth()->user()->hasRole('super-admin'))
                <li class="nav-item">
                    <a href="{{route('admin.dashboard')}}"
                       class="nav-link  {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.books.index')}}"
                       class="nav-link  {{ request()->is('admin/books*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Books</p>
                    </a>
                </li>
                <li class="nav-item">
                   <a href="{{route('admin.chapters.index')}}"
                           class="nav-link  {{ request()->is('admin/chapters*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Chapters</p>
                   </a>
                </li>
                <li class="nav-item">
                   <a href="{{route('admin.training-video.index')}}"
                           class="nav-link  {{ request()->is('admin/training-video*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-video"></i>
                            <p> Training Videos</p>
                   </a>
                </li>

               {{--<li class="nav-item">
                        <a href="{{route('admin.books.upload-book-file')}}"
                               class="nav-link  {{ request()->is('admin/book/upload-book-file*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p> Uploads Books</p>
                        </a>
               </li>--}}

                <li class="nav-item">
                    <a href="{{route('admin.students.index')}}"
                       class="nav-link  {{ request()->is('admin/students*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Students</p>
                    </a>
                </li>

                <li class="nav-item">
                   <a href="{{route('admin.attendances.index')}}"
                        class="nav-link  {{ request()->is('admin/attendances*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book-open"></i>
                            <p>Attendance</p>
                   </a>
                </li>
                @endif

                @if(auth()->user()->hasRole('student'))
                    <li class="nav-item">
                        <a href="{{route('dashboard')}}"
                           class="nav-link  {{ request()->is('dashboard*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('books')}}"
                           class="nav-link  {{ request()->is('books*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Books</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('training-videos.index')}}"
                           class="nav-link  {{ request()->is('training-videos*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-video"></i>
                            <p> Training Videos</p>
                        </a>
                    </li>

                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
