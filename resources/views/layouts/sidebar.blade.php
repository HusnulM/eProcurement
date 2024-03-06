<aside class="main-sidebar sidebar-dark-primary elevation-6">
    <a href="/" class="brand-link">
      <span class="brand-text font-weight-light">e-Procurement</span>
    </a>

    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('/assets/dist/img/avatar5.png') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @foreach(userMenu() as $headMenu)
          @if($headMenu->menugroup == null)
            @foreach(userSubMenu() as $detailMenu)
                @if($headMenu->menugroup === $detailMenu->menugroup)
                  <li class="nav-item">
                    <a href="{{ url($detailMenu->route) }}" class="nav-link {{ active($detailMenu->route) }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{ $detailMenu->menu_desc }}</p>
                    </a>
                  </li>
                @endif
            @endforeach
          @else
            <li class="nav-item {{ groupOpen($headMenu->menugroup) }}">
              <a href="#" class="nav-link {{ groupOpen($headMenu->menugroup) == 'menu-open' ? 'active' : '' }}" style="{{ groupOpen($headMenu->menugroup) == 'menu-open' ? '' : 'background-color:#265a91;' }}">
                <i class="nav-icon {{ $headMenu->groupicon ? $headMenu->groupicon : 'fas fa-tachometer-alt' }}"></i>
                <p>
                {{ $headMenu->groupname }}
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                @foreach(userSubMenu() as $detailMenu)
                  @if($headMenu->menugroup === $detailMenu->menugroup)
                  <li class="nav-item">
                    <a href="{{ url($detailMenu->route) }}" class="nav-link {{ active($detailMenu->route) }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{ $detailMenu->menu_desc }}</p>
                    </a>
                  </li>

                  {{-- <a href="{{ url($detailMenu->route) }}" class="nav-link {{ $detailMenu->route == \Route::current()->uri() ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{ $detailMenu->menu_desc }}</p>
                  </a> --}}
                  @endif
                @endforeach
              </ul>
            </li>
          @endif
        @endforeach
          <!-- <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-gear"></i>
                <p>
                    Settings
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Home</p>
                </a>
              </li>
            </ul>
          </li> -->
        </ul>
      </nav>
    </div>
</aside>
