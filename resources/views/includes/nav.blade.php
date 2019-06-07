<nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top" id="mainNav">
    <a class="navbar-brand" href="/dashboard">Voucher System</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion" style="overflow:auto;">

        @if (in_array(1, $modules_access))
          <li class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Dashboard">
            <a class="nav-link" href="/dashboard">
              <i class="fa fa-fw fa-dashboard"></i>
              <span class="nav-link-text">Dashboard</span>
            </a>
          </li>
        @endif

        @if (in_array(2, $modules_access))
          <li class="nav-item {{ Request::is('members') ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Members">
            <a class="nav-link" href="/members">
              <i class="fa fa-users"></i>
              <span class="nav-link-text">Members</span>
            </a>
          </li>
        @endif

        @if (in_array(3, $modules_access))
          <li class="nav-item {{ Request::is('accounts') ? 'active' : '' }}">
            <a class="nav-link" href="/accounts">
              <i class="fa fa-credit-card"></i>
              <span class="nav-link-text">Accounts</span>
            </a>
          </li>
        @endif

        @if (in_array(4, $modules_access))
          <li class="nav-item {{ Request::is('vouchers') ? 'active' : '' }}">
            <a class="nav-link" href="/vouchers">
              <i class="fa fa-envelope"></i>
              <span class="nav-link-text">Vouchers</span>
            </a>
          </li>
        @endif

        @if (in_array(6, $modules_access))
          <li class="nav-item {{ Request::is('redemptions') ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Redemption">
            <a class="nav-link" href="/redemptions">
              <i class="fa fa-fw fa-calendar"></i>
              <span class="nav-link-text">Redemption</span>
            </a>
          </li>
        @endif

        {{-- @if (in_array(8, $modules_access))
          <li class="{{ Request::is('invoices') ? 'active' : '' }}">
            <a class="nav-link" href="/invoices">
              <i class="fa fa-fw fa-calculator"></i>
              <span class="nav-link-text">Invoices</span>
            </a>
          </li>
        @endif

        @if (in_array(5, $modules_access))
          <li class="{{ Request::is('payments') ? 'active' : '' }}">
            <a class="nav-link" href="/payments">
              <i class="fa fa-shopping-cart"></i>
              <span class="nav-link-text">Payments</span>
            </a>
          </li>
        @endif --}}

        @if(in_array(1, $modules_access))
          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Reports">
            <a class="nav-link nav-link-collapse {{ Request::is('reports/*') ? '' : 'collapsed' }}" data-toggle="collapse" href="#collapseReports" data-parent="#exampleAccordion" aria-expanded="{{ Request::is('reports/*') ? 'true' : 'false' }}">
              <i class="fa fa-fw fa-area-chart"></i>
              <span class="nav-link-text">Reports</span>
            </a>
            <ul class="sidenav-second-level collapse {{ Request::is('reports/*') ? 'show' : 'hide' }}" id="collapseReports">
            @if(in_array(12, $modules_access))  
            <li class="{{ Request::is('reports/collection') ? 'active' : '' }}">
                <a class="nav-link" href="/reports/collection">
                  <i class="fa fa-credit-card"></i>
                  <span class="nav-link-text">Collection</span>
                </a>
              </li>
              @endif
              @if(in_array(13, $modules_access)) 
              <li class="{{ Request::is('reports/members') ? 'active' : '' }}">
                <a class="nav-link" href="/reports/members">
                  <i class="fa fa-credit-card"></i>
                  <span class="nav-link-text">Members</span>
                </a>
              </li>
              @endif
              @if(in_array(14, $modules_access)) 
              <li class="{{ Request::is('reports/accounts') ? 'active' : '' }}">
                <a class="nav-link" href="/reports/accounts">
                  <i class="fa fa-credit-card"></i>
                  <span class="nav-link-text">Accounts</span>
                </a>
              </li>
              @endif
              @if(in_array(15, $modules_access)) 
              <li class="{{ Request::is('reports/vouchers') ? 'active' : '' }}">
                <a class="nav-link" href="/reports/vouchers?account_input=+&account=&date_from=2000-03-12&date_to=2019-03-12&status=all&destination=all&per_page=10">
                  <i class="fa fa-envelope"></i>
                  <span class="nav-link-text">Vouchers</span>
                </a>
              </li>
              @endif
              @if(in_array(16, $modules_access)) 
              <li class="{{ Request::is('reports/redemption') ? 'active' : '' }}">
                <a class="nav-link" href="/reports/redemption">
                  <i class="fa fa-calendar"></i>
                  <span class="nav-link-text">Redemption</span>
                </a>
              </li>
              @endif
              @if(in_array(17, $modules_access)) 
              <li class="{{ Request::is('reports/validity') ? 'active' : '' }}">
                <a class="nav-link" href="/reports/validity">
                  <i class="fa fa-envelope"></i>
                  <span class="nav-link-text">Voucher Validity</span>
                </a>
              </li>
              @endif
            </ul>
          </li>
        @endif
        
        {{-- ADMIN --}}
        @if (!empty(array_intersect(["7", "9", "10"], $modules_access)))
          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Admin">
            <a class="nav-link nav-link-collapse {{ Request::is('users') || Request::is('audit-log') || Request::is('user-groups') || Request::is('member-types') || Request::is('destinations') || Request::is('consultants') ? '' : 'collapsed' }}" data-toggle="collapse" href="#collapseAdmin" data-parent="#exampleAccordion">
              <i class="fa fa-fw fa-wrench"></i>
              <span class="nav-link-text">Administrator</span>
            </a>

            <ul class="sidenav-second-level collapse {{ Request::is('users') || Request::is('audit-log') || Request::is('user-groups') || Request::is('member-types') || Request::is('destinations') || Request::is('consultants') ? 'show' : 'hide' }}" id="collapseAdmin">
              @if (in_array(7, $modules_access))
                <li class="{{ Request::is('users') ? 'active' : '' }}">
                  <a class="nav-link" href="/users">
                    <i class="fa fa-users"></i>
                    <span class="nav-link-text">Users</span>
                  </a>
                </li>
              @endif

              @if (in_array(10, $modules_access))
                <li class="{{ Request::is('user-groups') ? 'active' : '' }}">
                  <a class="nav-link" href="/user-groups">
                    <i class="fa fa-users"></i>
                    <span class="nav-link-text">User Groups</span>
                  </a>
                </li>
              @endif

              @if (in_array(10, $modules_access))
                <li class="{{ Request::is('member-types') ? 'active' : '' }}">
                  <a class="nav-link" href="/member-types">
                    <i class="fa fa-users"></i>
                    <span class="nav-link-text">Membership Types</span>
                  </a>
                </li>
              @endif

              @if (in_array(10, $modules_access))
                <li class="{{ Request::is('destinations') ? 'active' : '' }}">
                  <a class="nav-link" href="/destinations">
                    <i class="fa fa-hotel"></i>
                    <span class="nav-link-text">Destinations</span>
                  </a>
                </li>
              @endif

               @if (in_array(10, $modules_access))
                <li class="{{ Request::is('consultants') ? 'active' : '' }}">
                    <a class="nav-link" href="/consultants">
                      <i class="fa fa-fw fa-list"></i>
                      <span class="nav-link-text">Consultants</span>
                    </a>
                  </li>
              @endif

              @if (in_array(9, $modules_access))
                <li class="{{ Request::is('audit-log') ? 'active' : '' }}">
                  <a class="nav-link" href="/audit-log">
                    <i class="fa fa-fw fa-list"></i>
                    <span class="nav-link-text">Audit Log</span>
                  </a>
                </li>
              @endif
            </ul>
          </li>
        @endif        

      </ul>
      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>
      
      <ul class="navbar-nav ml-auto">
        <li class="nav-item mr-4">
          <span class="navbar-text">{{ session('portalLabel') }}</span>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-fw fa-user"></i> {{ Auth::user()->username }}</a>
          <div class="dropdown-menu dropdown-menu-right">
            <h6 class="dropdown-header">{{ strtoupper(Auth::user()->first_name . ' ' . Auth::user()->last_name) }}</h6>
            <a class="dropdown-item" href="/changepassword"><i class="fa fa-fw fa-lock"></i> Change Password</a>
            
            {{-- @if (in_array(10, $modules_access))
              <a class="dropdown-item" href="/settings"><i class="fa fa-fw fa-cog"></i> Settings</a>
            @endif --}}
            
            @if ($can_switch == 1)
              <div class="dropdown-divider"></div>
              <h6 class="dropdown-header">PORTAL</h6>
              <a class="dropdown-item" href="#" data-toggle="modal" data-target="#portalModal"><i class="fa fa-fw fa-sign-in"></i> Change</a>
            @endif

            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-fw fa-sign-out"></i> {{ __('Logout') }}</a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
          </div>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Modal -->
<div class="modal fade" id="portalModal" tabindex="-1" role="dialog" aria-labelledby="portalModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="/portal">
        {{ csrf_field() }}
        <div class="modal-header">
          <h5 class="modal-title" id="portalModalLabel">Change Portal</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="type">Membership Type</label>
            <select class="form-control" id="type" name="type">
              @foreach ($types as $type)
                <option value="{{ $type->id }}">{{ $type->type }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

    <script type="text/javascript">
        // Restore to default sidenav setting
        (function () {
        if (Boolean(localStorage.getItem('sidenav-toggled'))) {
            var body = document.getElementsByTagName('body')[0];
            body.className = body.className + ' sidenav-toggled';
        }
        })();
    </script>