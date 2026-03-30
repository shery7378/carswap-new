@php
  use Illuminate\Support\Facades\Route;
@endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme shadow-lg"
  style="background: #0f172a !important; color: #94a3b8; border-right: 1px solid rgba(255,255,255,0.05);">

  <style>
    #layout-menu .menu-inner {
      padding: 1rem 0 !important;
    }

    #layout-menu .menu-item .menu-link {
      margin: 0.25rem 0.75rem !important;
      width: calc(100% - 1.5rem) !important;
      border-radius: 0.5rem !important;
      transition: all 0.2s ease;
      padding: 0.625rem 1rem !important;
    }

    /* Parent menu when open */
    #layout-menu .menu-item.open>.menu-link {
      background-color: rgba(255, 255, 255, 0.05) !important;
      color: #fff !important;
    }

    /* The actual active page (Leaf node) */
    #layout-menu .menu-item.active:not(.open)>.menu-link {
      background-color: #6366f1 !important;
      color: #fff !important;
      font-weight: 600;
      box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    #layout-menu .menu-link:hover {
      background: rgba(255, 255, 255, 0.08) !important;
      color: #fff !important;
    }

    /* Hide the submenu dot icons that overlap text */
    #layout-menu .menu-sub .menu-link::before {
      display: none !important;
    }

    #layout-menu .menu-header-text {
      color: #475569 !important;
      letter-spacing: 0.05em;
      font-weight: 700;
      margin: 1.5rem 1rem 0.5rem 1.5rem;
      display: block;
    }

    #layout-menu .menu-link i {
      font-size: 1.25rem !important;
      margin-right: 12px;
      color: #64748b;
    }

    #layout-menu .active .menu-link i,
    #layout-menu .menu-link:hover i {
      color: #fff !important;
    }

    .app-brand {
      padding: 2rem 1rem !important;
      height: auto !important;
      justify-content: center !important;
    }

    .app-brand .app-brand-text {
      color: #fff !important;
      margin-left: 0.5rem !important;
    }

    .menu-inner-shadow {
      display: none !important;
    }

    /* Submenu indentation */
    #layout-menu .menu-sub {
      background: transparent !important;
    }

    #layout-menu .menu-sub .menu-link {
      padding-left: 3rem !important;
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