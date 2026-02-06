<!-- Layout container -->
<div class="layout-page">
    <!-- Navbar -->

    <nav
        class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
        id="layout-navbar"
    >
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="ti ti-menu-2 ti-sm"></i>
            </a>
        </div>

        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <div class="navbar-nav align-items-center">
                <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                    <i class="ti ti-sm"></i>
                </a>
            </div>

            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <img src="{{Auth::user()->photo }}" alt class="h-auto rounded-circle" />
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <img src="{{Auth::user()->photo }}" alt class="h-auto rounded-circle" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                        <small class="text-muted">{{Auth::user()->type}}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
{{--                        <li>--}}
{{--                            <div class="dropdown-divider"></div>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a class="dropdown-item" href="#">--}}
{{--                                <i class="ti ti-user-check me-2 ti-sm"></i>--}}
{{--                                <span class="align-middle">My Profile</span>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a class="dropdown-item" href="#">--}}
{{--                                <i class="ti ti-settings me-2 ti-sm"></i>--}}
{{--                                <span class="align-middle">Settings</span>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a class="dropdown-item" href="#">--}}
{{--                        <span class="d-flex align-items-center align-middle">--}}
{{--                          <i class="flex-shrink-0 ti ti-credit-card me-2 ti-sm"></i>--}}
{{--                          <span class="flex-grow-1 align-middle">Billing</span>--}}
{{--                          <span class="flex-shrink-0 badge badge-center rounded-pill bg-label-danger w-px-20 h-px-20"--}}
{{--                          >2</span--}}
{{--                          >--}}
{{--                        </span>--}}
{{--                            </a>--}}
{{--                        </li>--}}
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="ti ti-logout me-2 ti-sm"></i>
                                <span class="align-middle">Log Out</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
                <!-- Notification -->
                <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                    <a
                        class="nav-link dropdown-toggle hide-arrow"
                        href="javascript:void(0);"
                        data-bs-toggle="dropdown"
                        data-bs-auto-close="outside"
                        aria-expanded="false"
                    >
                        <i class="ti ti-bell ti-md"></i>
                        <span class="badge bg-danger rounded-pill badge-notifications" id="notifications-item-count" data-count="5">5</span>
{{--                        <span class="badge bg-danger rounded-pill badge-notifications" id="notifications-item-count" data-count="{{$notifications_all}}">{{$notifications_all}}</span>--}}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end py-0">
                        <li class="dropdown-menu-header border-bottom">
                            <div class="dropdown-header d-flex align-items-center py-3">
                                <h5 class="text-body mb-0 me-auto">Notification</h5>
                                <a
                                    href="{{route('admin.notification.read_all')}}"
                                    class="dropdown-notifications-all text-body"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Mark all as read"
                                ><i class="ti ti-mail-opened fs-4"></i
                                    ></a>
                            </div>
                        </li>
                        <li class="dropdown-notifications-list scrollable-container">
                            <ul class="list-group list-group-flush" id="ul_notifications">
{{--                                @foreach ($notifications as $item)--}}
{{--                                <li class="list-group-item list-group-item-action dropdown-notifications-item {{$item->read_at!=null?'marked-as-read':''}}">--}}
{{--                                    <a href="{{route('notification.show',$item->id)}}">--}}
{{--                                        <a class="d-flex" href="{{route('notification.show',$item->id)}}">--}}
{{--                                            <div class="flex-shrink-0 me-3">--}}
{{--                                                <div class="avatar">--}}
{{--                                                    <img src="{{asset('/bell.png')}}" alt class="h-auto rounded-circle" />--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="flex-grow-1">--}}
{{--                                                <h6 class="mb-1">{{$item->data['title']}}</h6>--}}
{{--                                                <small class="text-muted">{{$item->created_at->diffForHumans()}}</small>--}}
{{--                                            </div>--}}
{{--                                            <div class="flex-shrink-0 dropdown-notifications-actions">--}}
{{--                                                <span href="javascript:void(0)" class="dropdown-notifications-read"--}}
{{--                                                ><span class="badge badge-dot"></span--}}
{{--                                                    ></span>--}}

{{--                                            </div>--}}
{{--                                        </a>--}}
{{--                                    </a>--}}

{{--                                </li>--}}
{{--                                @endforeach--}}

                            </ul>
                        </li>
{{--                        <li class="dropdown-menu-footer border-top">--}}
{{--                            <a--}}
{{--                                href="{{url('notifications')}}"--}}
{{--                                class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center"--}}
{{--                            >--}}
{{--                                View all notifications--}}
{{--                            </a>--}}
{{--                        </li>--}}
                    </ul>
                </li>
                <!--/ Notification -->

                <audio id="notify">
                    <source src="{{asset('audio/notfiy.mp3')}}" type="audio/mpeg">
                </audio>
                <!--/ User -->
            </ul>
        </div>
    </nav>

    <!-- / Navbar -->
    <!-- Content wrapper -->
    <div class="content-wrapper">
