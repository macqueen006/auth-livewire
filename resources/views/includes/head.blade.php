<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $title ?? 'Auth' }}</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
@php
    $buttonRGBColor = \App\helpers\Helper::convertHexToRGBString(config('appearance.color.button'));
    $inputBorderRGBColor = \App\helpers\Helper::convertHexToRGBString(config('appearance.color.input_border'));
@endphp
<style>
    .auth-component-button:focus {
        --tw-ring-opacity: 1;
        --tw-ring-color: rgb({{ $buttonRGBColor }} / var(--tw-ring-opacity));
    }

    .auth-component-input {
        color: {{ config('appearance.color.input_text') }}

    }

    .auth-component-input:focus, .auth-component-code-input:focus {
        --tw-ring-color: rgb({{ $inputBorderRGBColor }} / var(--tw-ring-opacity));
        border-color: rgb({{ $inputBorderRGBColor }} / var(--tw-border-opacity));
    }

    .auth-component-input-label-focused {
        color: {{ config('appearance.color.input_border') }}
    }
</style>

<link href="{{ url(config('appearance.favicon.light')) }}" rel="icon" media="(prefers-color-scheme: light)"/>
<link href="{{ url(config('appearance.favicon.dark')) }}" rel="icon" media="(prefers-color-scheme: dark)"/>
@livewireStyles
@stack('auth-head-scripts')
