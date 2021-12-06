@extends('indigo-layout::installation')

@section('meta_title', _p('allegro::pages.admin.installation.meta_title', 'Installation module Allegro') . ' - ' . config('app.name'))
@section('meta_description', _p('allegro::pages.admin.installation.meta_description', 'Installation module Allegro in application'))

@push('head')

@endpush

@section('title')
    <h2>{{ _p('allegro::pages.admin.installation.headline', 'Installation module Allegro') }}</h2>
@endsection

@section('content')
    <form-builder disabled-dialog="" url="{{ route('allegro.admin.installation.index') }}" send-text="{{ _p('allegro::pages.admin.installation.send_text', 'Install') }}"
    edited>
        <div class="section">
            <div class="section">
                {{ _p('allegro::pages.admin.installation.will_be_execute_migrations', 'Will be execute package migrations') }}
            </div>
        </div>
    </form-builder>
@endsection
