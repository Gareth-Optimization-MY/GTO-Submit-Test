<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport"  content="width=device-width, initial-scale=1">
    <title>Home Page</title>
    {{ vite_assets() }}
</head>
<body>
<div id="root"></div>
        {{-- @include('shopify.tabs') --}}
        {{-- @include('shopify.table') --}}
        {{-- @include('shopify.polaris_page') --}}
        {{-- <script src="{{asset('js/app.js')}}"></script>
         --}}
         {{-- @vite('resources/js/app.js') --}}
</body>
@if(env('SHOPIFY_APPBRIDGE_ENABLED',false))
<script src="https://unpkg.com/@shopify/app-bridge{{ env('SHOPIFY_APPBRIDGE_VERSION','latest') ? '@'.env('SHOPIFY_APPBRIDGE_VERSION','latest') : '' }}"></script>
<script>
    console.log('testsolcoders');
    var AppBridge = window['app-bridge'];
    var createApp = AppBridge.default;
    var app = createApp({
        apiKey: '{{ env("SHOPIFY_API_KEY") }}',
        host: '{{ $host }}',
        forceRedirect: true,
    });

</script>
@endif
</html>
