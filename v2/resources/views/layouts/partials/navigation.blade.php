{{ Form::open(['name' => 'form_logout', 'route' => 'logout', 'method' => 'POST']) }} {{ Form::close() }}

<div class="top_nav">
  <div class="nav_menu">
    <nav>
      <div class="nav toggle">
        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
      </div>

      <ul class="nav navbar-nav navbar-right">
        <li>
          <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <img src="{{ asset('images/ai-s.png') }}" alt=""> {{ $user->name }}
            <span class=" fa fa-angle-down"></span>
          </a>
          <ul class="dropdown-menu dropdown-usermenu pull-right">
            <li>
              <a href="{{ route('users.show', ['id' => $user->id ]) }}"><i class="fa fa-user pull-right"></i> Perfil</a>
            </li>
            @yield('navbar')
            <li>
              <a href="{{ route('account.edit') }}"><i class="fa fa-gear pull-right"></i> Configurações</a>
            </li>
            <li>
              <a onClick="document.querySelector('form[name=form_logout]').submit()"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</div>
