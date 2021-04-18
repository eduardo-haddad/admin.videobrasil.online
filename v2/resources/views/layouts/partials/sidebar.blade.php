<div class="col-md-3 left_col menu_fixed">
  <div class="left_col scroll-view">
    <div class="navbar nav_title text-center" style="border: 0;">
      <a href="{{ url('/') }}" class="site_title">Videobrasil</a>
    </div>

    <div class="clearfix"></div>

    <div class="profile clearfix">
      <div class="profile_pic">
        <img src="{{ asset('images/ai-s.png') }}" alt="..." class="img-circle profile_img">
      </div>
      <div class="profile_info">
        <span>Bem-vindo,</span>
        <h2>{{ $user->first_name }}</h2>
      </div>
    </div>

    <br />

    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <h3>Menu</h3>
        <ul class="nav side-menu">
          <li>
            <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
          </li>

          <li>
            <a><i class="fa fa-code"></i> Info <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="{{ route('sobre.edit') }}">Sobre</a></li>
              <li><a href="{{ route('equipe.index') }}">Equipe</a></li>
              <li><a href="{{ route('season.index') }}">Tipos de temporada</a></li>
            </ul>
          </li>
          
          <li>
            <a href="{{ route('edition.index') }}"><i class="fa fa-code"></i> Exposições</a>
          </li>

          
        </ul>
      </div>
    </div>
  </div>
</div>
