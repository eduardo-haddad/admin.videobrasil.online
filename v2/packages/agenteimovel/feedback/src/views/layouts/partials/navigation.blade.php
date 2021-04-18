{{ Form::open(['name' => 'form_logout', 'route' => 'logout', 'method' => 'POST']) }} {{ Form::close() }}
<div class="top_nav">
  <div class="nav_menu">
    <nav>
      <ul class="nav nav-pills navbar-left">
        @yield('navbar')
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li>
          <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <img src="{{ asset('images/avatar.png') }}" alt=""> {{ $user->name }}
            <span class=" fa fa-angle-down"></span>
          </a>
          @auth
          <ul class="dropdown-menu dropdown-usermenu pull-right">
            @if(Auth::user()->roles && in_array(Auth::user()->roles->first()->alias, ['root','campaign-manager','lead-manager',]))
            <li>
              <a href="{{_route('feedback.leads.index')}}">Leads</a>
            </li>
            <li>
              <a href="{{_route('feedback.client.index')}}">Clients(Dashboards)</a>
            </li>
            @endif
            <li>
              <a onClick="document.querySelector('form[name=form_logout]').submit()"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
            </li>
          </ul>
          @endauth
        </li>
      </ul>
    </nav>
  </div>
</div>
