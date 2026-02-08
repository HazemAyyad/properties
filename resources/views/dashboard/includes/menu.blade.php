<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{route('admin.dashboard')}}" class="app-brand-link">
              <span class="app-brand-logo demo">
                <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                      fill="#7367F0"
                  />
                  <path
                      opacity="0.06"
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
                      fill="#161616"
                  />
                  <path
                      opacity="0.06"
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
                      fill="#161616"
                  />
                  <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                      fill="#7367F0"
                  />
                </svg>
              </span>
            <span class="app-brand-text demo menu-text fw-bold">{{config('app.name')}}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Page -->
        <li class="menu-item {{Route::is('admin.dashboard')?'active':''}}">
            <a href="{{route('admin.dashboard')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>
{{--        @can('Roles Management')--}}
            <li class="menu-item {{Route::is('admin.roles.index')?'active':''}}">
                <a class="menu-link" href="{{route('admin.roles.index')}}">
                    <i class="menu-icon tf-icons ti ti-lock"></i>
                    <div data-i18n="{{__('Role Management')}}">{{__('Role Management')}}</div>
                </a>
            </li>
{{--        @endcan--}}

{{--        @can('Staff Management')--}}
            <li class="menu-item {{Route::is('admin.staff.index')?'active':''}}">
                <a class="menu-link" href="{{route('admin.staff.index')}}">
                    <i class="menu-icon tf-icons ti ti-app-window"></i>
                    <div data-i18n="{{__('Staff')}}">{{__('Staff')}}</div>
                </a>
            </li>
{{--        @endcan--}}


        @can('User Management')
            <li class="menu-item {{Route::is('admin.users.index')||Route::is('admin.users.send_email_all_users')?'active open':''}}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                    <div data-i18n="Users Managements">Users Managements</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{route('admin.users.index')}}" class="menu-link">
                            <div data-i18n="Users">Users</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('admin.users.send_email_all_users')}}" class="menu-link">
                            <div data-i18n="Send Email To All">Send Email To All</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan
        <li class="menu-item {{Route::is('admin.users.index')||Route::is('admin.users.send_email_all_users')?'active open':''}}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div data-i18n="{{__('Properties Managements')}}">{{__('Properties Managements')}}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{route('admin.properties.index','1')}}" class="menu-link">
                        <div data-i18n="{{__('Approved Properties')}}">{{__('Approved Properties')}}</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{route('admin.properties.index','0')}}" class="menu-link">
                        <div data-i18n="{{__('Pending Properties')}}">{{__('Pending Properties')}}</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{route('admin.properties.index','2')}}" class="menu-link">
                        <div data-i18n="{{__('Rejected Properties')}}">{{__('Rejected Properties')}}</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{route('admin.categories.index')}}" class="menu-link">
                        <div data-i18n="{{__('Categories')}}">{{__('Categories')}}</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{route('admin.feature_categories.index')}}" class="menu-link">
                        <div data-i18n="{{__('Feature Categories')}}">{{__('Feature Categories')}}</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{route('admin.property_features.index')}}" class="menu-link">
                        <div data-i18n="{{__('Features')}}">{{__('Features')}}</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{route('admin.property_facilities.index')}}" class="menu-link">
                        <div data-i18n="{{__('Facilities')}}">{{__('Facilities')}}</div>
                    </a>
                </li>
            </ul>
        </li>

{{--        @can('Site Management')--}}
            <li class="menu-item " style="">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-settings"></i>
                    <div data-i18n="Management Site">Management Site</div>
                </a>
                <ul class="menu-sub">

                    <li class="menu-item">
                        <a href="{{route('admin.partners.index')}}" class="menu-link">

                            <div data-i18n="{{__('Partners')}}">{{__('Partners')}}</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('admin.agents.index')}}" class="menu-link">

                            <div data-i18n="{{__('Agents')}}">{{__('Agents')}}</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('admin.people_say.index')}}" class="menu-link">

                            <div data-i18n="{{__('People Say')}}">{{__('People Say')}}</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('admin.benefits.index')}}" class="menu-link">

                            <div data-i18n="{{__('Why Choose Us')}}">{{__('Why Choose Us')}}</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('admin.services.index')}}" class="menu-link">

                            <div data-i18n="{{__('Services')}}">{{__('Services')}}</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('admin.countries.index')}}" class="menu-link">

                            <div data-i18n="{{__('Countries')}}">{{__('Countries')}}</div>
                        </a>
                    </li>
{{--                    <li class="menu-item">--}}
{{--                        <a href="{{route('admin.settings.index')}}" class="menu-link">--}}

{{--                            <div data-i18n="{{__('Settings')}}">{{__('Settings')}}</div>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                    <li class="menu-item">
                        <a href="{{route('admin.settings.page','settings')}}" class="menu-link">
                            <div data-i18n="{{__('Settings')}}">{{__('Settings')}}</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('admin.plans.index')}}" class="menu-link">
                            <div data-i18n="{{__('Plans')}}">{{__('Plans')}}</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('admin.plan_features.index')}}" class="menu-link">
                            <div data-i18n="{{__('Plan Feature')}}">{{__('Plan Feature')}}</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('admin.settings.page','sections')}}" class="menu-link">
                            <div data-i18n="{{__('Sections')}}">{{__('Sections')}}</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('admin.settings.page_slider')}}" class="menu-link">
                            <div data-i18n="{{__('Slider')}}">{{__('Slider')}}</div>
                        </a>
                    </li>
{{--                    <li class="menu-item">--}}
{{--                        <a href="{{route('how-it-work.index')}}" class="menu-link">--}}
{{--                            <div data-i18n="{{__('How It Work')}}">{{__('How It Work')}}</div>--}}
{{--                        </a>--}}
{{--                    </li>--}}

                    <li class="menu-item">
                        <a href="{{route('admin.faqs.index')}}" class="menu-link">
                            <div data-i18n="{{__('FAQs')}}">{{__('FAQs')}}</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('admin.policies.index')}}" class="menu-link">
                            <div data-i18n="{{__('Policies')}}">{{__('Policies')}}</div>
                        </a>
                    </li>
{{--                    <li class="menu-item">--}}
{{--                        <a href="{{route('settings.page','why_ship')}}" class="menu-link">--}}
{{--                            <div data-i18n="{{__('Why Ship')}}">{{__('Why Ship')}}</div>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                    <li class="menu-item">
                        <a href="{{route('admin.settings.page','about_us')}}" class="menu-link">
                            <div data-i18n="{{__('About Us')}}">{{__('About Us')}}</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('admin.provinces.index')}}" class="menu-link">
                            <div data-i18n="{{__('Provinces')}}">{{__('Provinces')}}</div>
                        </a>
                    </li>


{{--                    <li class="menu-item">--}}
{{--                        <a href="{{route('settings.page','terms_conditions')}}" class="menu-link">--}}
{{--                            <div data-i18n="{{__('Terms & conditions')}}">{{__('Terms & conditions')}}</div>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="menu-item">--}}
{{--                        <a href="{{route('settings.page','privacy_policy')}}" class="menu-link">--}}
{{--                            <div data-i18n="{{__('Privacy Policy')}}">{{__('Privacy Policy')}}</div>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                    <li class="menu-item">
                        <a href="{{route('admin.blogs.index')}}" class="menu-link">
                            <div data-i18n="{{__('Blogs')}}">{{__('Blogs')}}</div>
                        </a>
                    </li>


{{--                    <li class="menu-item">--}}
{{--                        <a href="{{route('settings.index')}}" class="menu-link">--}}
{{--                            <div data-i18n="{{__('Settings')}}">{{__('Settings')}}</div>--}}
{{--                        </a>--}}
{{--                    </li>--}}


                </ul>
            </li>
{{--          @endcan--}}








    </ul>
</aside>
<!-- / Menu -->
