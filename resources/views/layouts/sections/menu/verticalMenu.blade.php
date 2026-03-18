@php
  use Illuminate\Support\Facades\Route;
@endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme shadow-lg"
  style="background: linear-gradient(180deg, #1d2327 0%, #2b343b 100%); color: #e1e1e1; border-right: 1px solid rgba(255,255,255,0.05);">

  <style>
    #layout-menu .menu-item.active > .menu-link {
      background: linear-gradient(270deg, rgba(105, 108, 255, 0.2) 0%, rgba(105, 108, 255, 0) 100%);
      border-left: 3px solid #696cff;
      color: #fff !important;
      font-weight: 600;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    #layout-menu .menu-link:hover {
      background: rgba(255,255,255,0.03) !important;
      transition: all 0.3s ease;
    }
    #layout-menu .menu-header-text {
      color: #696cff !important;
      letter-spacing: 1px;
      font-weight: 700;
      opacity: 0.85;
    }
    #layout-menu .menu-link i {
      font-size: 1.25rem !important;
      margin-right: 12px;
      color: rgba(255,255,255,0.7);
    }
    #layout-menu .active .menu-link i {
      color: #696cff !important;
    }
    .app-brand .app-brand-text {
      background: linear-gradient(90deg, #fff 0%, #696cff 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      text-shadow: 0 0 20px rgba(105, 108, 255, 0.3);
    }
  </style>

  <!-- ! Hide app brand if navbar-full -->
  <div class="app-brand demo">
    <a href="{{ url('/') }}" class="app-brand-link">
      <span class="app-brand-logo demo">@include('_partials.macros')</span>
      <span class="app-brand-text demo menu-text fw-bold ms-2">{{ config('variables.templateName') }}</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="icon-base bx bx-chevron-left icon-sm d-flex align-items-center justify-content-center"></i>
    </a>
  </div>

  <div class="menu-divider mt-0"></div>
  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    @foreach ($menuData[0]->menu as $menu)
      {{-- adding active and open class if child is active --}}

      @php
        $showItem = true;
        if (isset($menu->permission)) {
          // Both super-admin role and direct permission check
          $showItem = auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo($menu->permission, 'admin-guard');
        }
      @endphp

      @if ($showItem)
        {{-- menu headers --}}
        @if (isset($menu->menuHeader))
          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
          </li>
        @else
          {{-- active menu method --}}
          @php
            $activeClass = null;
            $currentRouteName = Route::currentRouteName();

            if ($currentRouteName === $menu->slug) {
                $activeClass = 'active';
            } elseif (isset($menu->submenu)) {
                if (gettype($menu->slug) === 'array') {
                    foreach ($menu->slug as $slug) {
                        if (str_contains($currentRouteName, $slug) and strpos($currentRouteName, $slug) === 0) {
                            $activeClass = 'active open';
                        }
                    }
                } else {
                    if (str_contains($currentRouteName, $menu->slug) and strpos($currentRouteName, $menu->slug) === 0) {
                        $activeClass = 'active open';
                    }
                }
            }
          @endphp

          {{-- main menu --}}
          <li class="menu-item {{ $activeClass }}">
            <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
              class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
              @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
              @isset($menu->icon)
                <i class="{{ $menu->icon }}"></i>
              @endisset
              <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
              @isset($menu->badge)
                <div class="badge rounded-pill bg-{{ $menu->badge[0] }} text-uppercase ms-auto">{{ $menu->badge[1] }}</div>
              @endisset
            </a>

            {{-- submenu --}}
            @isset($menu->submenu)
              @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
            @endisset
          </li>
        @endif
      @endif
    @endforeach
  </ul>

</aside>
