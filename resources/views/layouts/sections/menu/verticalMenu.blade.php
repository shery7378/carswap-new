@php
  use Illuminate\Support\Facades\Route;
@endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme shadow-lg"
  style="background: #0f172a !important; color: #94a3b8; border-right: 1px solid rgba(255,255,255,0.05);">

  <style>
    :root {
      --menu-bg: #0f172a;
      --menu-accent: #6366f1;
      --menu-text: #94a3b8;
      --menu-hover: rgba(255, 255, 255, 0.08);
      --menu-active: #6366f1;
    }

    #layout-menu {
      background: var(--menu-bg) !important;
      color: var(--menu-text);
      border-right: 1px solid rgba(255, 255, 255, 0.05);
      font-family: 'Outfit', sans-serif;
    }

    #layout-menu .menu-inner {
      padding: 1.25rem 0 !important;
    }

    #layout-menu .menu-item .menu-link {
      margin: 0.25rem 1rem !important;
      width: calc(100% - 2rem) !important;
      border-radius: 10px !important;
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
      padding: 0.75rem 1.25rem !important;
      color: var(--menu-text) !important;
    }

    /* Hover State */
    #layout-menu .menu-link:hover {
      background: var(--menu-hover) !important;
      color: #fff !important;
      transform: translateX(4px);
    }

    #layout-menu .menu-link:hover i {
      color: #fff !important;
    }

    /* Active Page (Leaf Node) */
    #layout-menu .menu-item.active:not(.open)>.menu-link {
      background: var(--menu-active) !important;
      color: #fff !important;
      font-weight: 600;
      box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
    }

    #layout-menu .menu-item.active:not(.open) i {
      color: #fff !important;
    }

    /* Open parent item */
    #layout-menu .menu-item.open>.menu-link {
      background: rgba(255, 255, 255, 0.03) !important;
      color: #fff !important;
    }

    #layout-menu .menu-item.open>.menu-link i {
      color: #fff !important;
    }

    /* Submenu styling */
    #layout-menu .menu-sub {
      background: transparent !important;
      padding-top: 0.25rem;
      padding-bottom: 0.5rem;
    }

    #layout-menu .menu-sub .menu-link {
      padding-left: 3.5rem !important;
      opacity: 0.8;
      font-size: 0.875rem;
    }

    #layout-menu .menu-sub .menu-link:hover {
      opacity: 1;
    }

    #layout-menu .menu-sub .menu-item.active .menu-link {
      opacity: 1;
      background: rgba(255, 255, 255, 0.05) !important;
      color: var(--menu-active) !important;
    }

    #layout-menu .menu-header {
      margin-top: 2rem !important;
      margin-bottom: 0.75rem !important;
      opacity: 0.5;
    }

    #layout-menu .menu-header::before {
      display: none !important;
    }

    #layout-menu .menu-header-text {
      color: #94a3b8 !important;
      letter-spacing: 0.15em;
      font-weight: 700;
      margin: 0 1.75rem !important;
      text-transform: uppercase;
      font-size: 0.7rem;
    }

    #layout-menu .menu-link i {
      font-size: 1.35rem !important;
      margin-right: 14px;
      color: #64748b;
      transition: color 0.2s ease;
    }

    #layout-menu .app-brand {
      padding: 1.5rem 1.25rem !important;
      justify-content: flex-start !important;
    }

    .app-brand .app-brand-text {
      font-size: 1.25rem !important;
      letter-spacing: -0.5px;
      font-weight: 800 !important;
    }

    .menu-inner-shadow {
      display: none !important;
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
  <div class="menu-inner-shadow" style="display: none !important;"></div>

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
              class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
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