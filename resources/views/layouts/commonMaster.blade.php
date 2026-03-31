<!DOCTYPE html>
<html lang="en" class="layout-menu-fixed layout-compact" data-assets-path="{{ asset('/assets') . '/' }}" dir="ltr"
    data-skin="default" data-base-url="{{ url('/') }}" data-framework="laravel" data-bs-theme="light"
    data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>
        @yield('title') | {{ config('settings.storeName') ?? config('variables.templateName') ?? 'Template' }}
    </title>
    <meta name="description"
        content="{{ config('settings.metaDescription') ?? config('variables.templateDescription') ?? '' }}" />
    <meta name="keywords"
        content="{{ config('settings.metaKeywords') ?? config('variables.templateKeyword') ?? '' }}" />
    <meta property="og:title" content="{{ config('settings.metaTitle') ?? config('variables.ogTitle') ?? '' }}" />
    <meta property="og:type" content="{{ config('variables.ogType') ? config('variables.ogType') : '' }}" />
    <meta property="og:url" content="{{ config('variables.productPage') ? config('variables.productPage') : '' }}" />
    <meta property="og:image" content="{{ config('settings.storeLogo') ? asset('storage/' . config('settings.storeLogo')) : asset('assets/img/logo/logo.webp') }}" />
    <meta property="og:description"
        content="{{ config('settings.metaDescription') ?? config('variables.templateDescription') ?? '' }}" />
    <meta property="og:site_name"
        content="{{ config('settings.storeName') ?? config('variables.creatorName') ?? '' }}" />
    <meta name="robots" content="noindex, nofollow" />
    <!-- laravel CRUD token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Canonical SEO -->
    <link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ config('settings.storeFavicon') ? asset('storage/' . config('settings.storeFavicon')) : asset('assets/img/favicon/favicon.svg') }}" />
    <link rel="apple-touch-icon" href="{{ config('settings.storeFavicon') ? asset('storage/' . config('settings.storeFavicon')) : asset('assets/img/favicon/favicon.svg') }}" />

    <!-- Include Styles -->
    @include('layouts/sections/styles')

    <!-- Include Scripts for customizer, helper, analytics, config -->
    @include('layouts/sections/scriptsIncludes')
</head>

<body>
    <!-- Layout Content -->
    @yield('layoutContent')
    <!--/ Layout Content -->



    <!-- Include Scripts -->
    @include('layouts/sections/scripts')
</body>

</html>