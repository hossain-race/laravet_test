@if (Auth::check())
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="https://placehold.it/160x160/00a65a/ffffff/&text={{ mb_substr(Auth::user()->name, 0, 1) }}" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p>{{ Auth::user()->name }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          <li class="header">{{ trans('backpack::base.administration') }}</li>
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>

          <li>
            <a href="{{ url('admin/product') }}">
              <i class="fa fa-amazon"></i>

              <span>
                            Product List
              </span>
              <span class="pull-right-container">

                @if(\App\Models\Product::whereHas('User', function($query) {
                             $query->where('user_id',\Auth::id());
                         })->count()>0)
                  <small class="label pull-right bg-blue">{{\App\Models\Product::whereHas('User', function($query) {
                             $query->where('user_id',\Auth::id());
                         })->count()}}</small>
                @endif
              </span>

            </a>
          </li>
          <li>
            <a href="{{ url('admin/hijackercheck') }}">
              <i class="fa fa-warning"></i>
              <span>
                            Hijacker Check
              </span>
              <span class="pull-right-container">

                @if(\App\Models\Product::where('selling_qty','>',1)->whereHas('User', function($query) {
                             $query->where('user_id',\Auth::id());
                         })->count()>0)
                  <small class="label pull-right bg-red">{{\App\Models\Product::where('selling_qty','>',1)->whereHas('User', function($query) {
                             $query->where('user_id',\Auth::id());
                         })->count()}}</small>
                @endif
              </span>
            </a>
          </li>
          @if (Auth::user()->hasRole('super-admin'))
            <li>
              <a href="{{ url('refreshhijackercheck') }}">
                <i class="fa fa-warning"></i>
                <span>
                              Refresh Hijacker List
                </span>

              </a>
            </li>
          @endif
          <!-- ================================================ -->
          <!-- ==== Recommended place for admin menu items ==== -->
          <!-- ================================================ -->


          <!-- ======================================= -->
          <li class="header">{{ trans('backpack::base.user') }}</li>
        @if (Auth::user()->hasRole('super-admin'))
          <!-- Users, Roles Permissions -->
            <li class="treeview">
              <a href="#">
                <i class="fa fa-group"></i>
                <span>User management</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li>
                  <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/user') }}">
                    <i class="fa fa-user"></i>
                    <span>Users</span>
                    <span class="pull-right-container">
                                      <small class="label pull-right bg-blue">{{\App\User::count()}}</small>
                                    </span>
                  </a>
                </li>
                <li>
                  <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/role') }}">
                    <i class="fa fa-group"></i> <span>Roles</span>
                  </a>
                </li>
                <li>
                  <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/permission') }}">
                    <i class="fa fa-key"></i> <span>Permissions</span>
                  </a>
                </li>
              </ul>
            </li>
          @endif

          <li>
            <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/logout') }}">
              <i class="fa fa-sign-out"></i> <span>{{ trans('backpack::base.logout') }}</span>
            </a>
          </li>
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>
@endif
