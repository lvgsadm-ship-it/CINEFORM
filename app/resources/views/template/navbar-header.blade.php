<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom" >
    <div class="container-fluid">
        <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
            <div class="input-group">

            </div>
        </nav>

        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
            
            <li class="nav-item topbar-icon dropdown hidden-caret">
                <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="messageDropdown"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                    >
                    <i class="fa fa-globe"></i>
                </a>
                <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                    <li>
                        <div class="dropdown-title">
                            {{__('Select your Language')}}
                        </div>
                    </li>
                    <li>
                        <div class="notif-scroll scrollbar-outer">
                            <div class="notif-center">
                                <a href="{{route('language', 'es')}}">
                                    <div class="notif-icon ">
                                        <i class="iti__flag iti__ve"></i>
                                    </div>
                                    <div class="notif-content">
                                        <span class="block"> {{__('Spanish')}}  </span>
                                        
                                    </div>
                                </a>
                                <a href="{{route('language', 'en')}}">
                                    <div class="notif-icon ">
                                        <i class="iti__flag iti__gb"></i>
                                    </div>
                                    <div class="notif-content">
                                        <span class="block">
                                            {{__('English')}}
                                        </span>
                                        
                                    </div>
                                </a>
                            </div>                        
                        </div>
                    </li>                        
                </ul>
            </li>

            <li class="nav-item topbar-icon dropdown hidden-caret">


                <a
                    class="nav-link"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                    >
                    <i class="fas fa-layer-group"></i>
                </a>
                <div class="dropdown-menu quick-actions animated fadeIn">
                    <div class="quick-actions-header">
                        <span class="title mb-1">{{__('Module List')}}</span>
                        <span class="subtitle op-7">{{__('Select')}}</span>
                    </div>
                    <div class="quick-actions-scroll scrollbar-outer">
                        <div class="quick-actions-items">
                            <div class="row m-0">
                                @foreach(Auth::user()->getModules() as $value)

                                <a class="col-6 col-md-4 p-0" href="{{route('set_module', $value->crypt_id)}}">
                                    <div class="quick-actions-item">
                                        <div class="avatar-item bg-success rounded-circle">
                                            <i class="{{__($value->icon)}}"></i>
                                        </div>
                                        <span class="text">{{__($value->name)}}</span>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            <li class="nav-item topbar-user dropdown hidden-caret">
                <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                    >

                    <div class="avatar-sm">
                        <img
                            src="{{route('show_avatar', Auth::user()->crypt_id)}}"
                            alt="..."
                            class="avatar-img rounded-circle"
                            />
                    </div>
                    <span class="profile-username">
                        <span class="op-7">{{__('Hi')}},</span>
                        <span class="fw-bold">{{Auth::user()->full_name}}</span>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                        <li>
                            <div class="user-box">
                                <div class="avatar-lg">
                                    <img
                                        src="{{route('show_avatar', Auth::user()->crypt_id)}}"
                                        alt="image profile"
                                        class="avatar-img rounded"
                                        />
                                </div>
                                <div class="u-text">
                                    <h4>{{Auth::user()->full_name}}</h4>
                                    <p class="text-muted">{{Auth::user()->email}}</p>

                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{route('update_profile')}}">{{__('Account Setting')}}</a>
                            
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{route('logout')}}">{{__('Logout')}}</a>
                        </li>
                    </div>
                </ul>
            </li>
        </ul>
    </div>
</nav>