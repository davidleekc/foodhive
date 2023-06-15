@php
    Arr::set($attributes, 'class', Arr::get($attributes, 'class') . ' icon-select');
    Arr::set($attributes, 'data-empty', __('None'));
@endphp

{!! Form::customSelect($name, [$value => $value], $value, $attributes) !!}

@once
    @if (request()->ajax())
        <link media="all" type="text/css" rel="stylesheet" href="{{ Theme::asset()->url('css/vendors/uicons-regular-straight.css') }}">
        <script src="{{ Theme::asset()->url('js/icons-field.js') }}?v=1.0.1"></script>
    @else
        @push('header')
            <link media="all" type="text/css" rel="stylesheet" href="{{ Theme::asset()->url('css/vendors/uicons-regular-straight.css') }}">
            <script src="{{ Theme::asset()->url('js/icons-field.js') }}?v=1.0.1"></script>
        @endpush
    @endif
@endonce
