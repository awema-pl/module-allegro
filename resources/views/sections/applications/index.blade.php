@extends('indigo-layout::main')

@section('meta_title', _p('allegro::pages.application.meta_title', 'Applications') . ' - ' . config('app.name'))
@section('meta_description', _p('allegro::pages.application.meta_description', 'Applications in application'))

@push('head')

@endpush

@section('title')
    {{ _p('allegro::pages.application.headline', 'Applications') }}
@endsection

@section('create_button')
    <button class="frame__header-add" @click="AWEMA.emit('modal::create:open')" title="{{ _p('allegro::pages.application.create_application', 'Create application') }}"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('allegro::pages.application.applications', 'Application') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('allegro.application.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="applications_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="name" label="{{ _p('allegro::pages.application.name', 'Name') }}"></tb-column>
                                <tb-column name="client_id" label="{{ _p('allegro::pages.application.client_id', 'Client ID') }}"></tb-column>

                                <tb-column name="sandbox" label="{{ _p('allegro::pages.application.sandbox', 'Mode sandbox') }}">
                                    <template slot-scope="col">
                                        <span v-if="col.data.sandbox" class="cl-red">
                                            {{ _p('allegro::pages.application.yes', 'Yes') }}
                                        </span>
                                        <span v-else>
                                            {{ _p('allegro::pages.application.no', 'No') }}
                                        </span>
                                    </template>
                                </tb-column>
                                <tb-column name="created_at" label="{{ _p('allegro::pages.application.created_at', 'Created at') }}"></tb-column>
                                <tb-column name="manage" label="{{ _p('allegro::pages.application.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('allegro::pages.application.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'editApplication', data: col.data}); AWEMA.emit('modal::edit_application:open')">
                                                {{_p('allegro::pages.application.edit', 'Edit')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'deleteApplication', data: col.data}); AWEMA.emit('modal::delete_application:open')">
                                                {{_p('allegro::pages.application.delete', 'Delete')}}
                                            </cm-button>
                                        </context-menu>
                                    </template>
                                </tb-column>
                            </table-builder>

                            <paginate-builder v-if="table.data"
                                :meta="table.meta"
                            ></paginate-builder>
                        </template>
                        @include('indigo-layout::components.base.loading')
                        @include('indigo-layout::components.base.empty')
                        @include('indigo-layout::components.base.error')
                    </content-wrapper>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')

    <modal-window name="create" class="modal_formbuilder" title="{{ _p('allegro::pages.application.create_application', 'Create application') }}">
        <form-builder name="create" url="{{ route('allegro.application.store') }}" send-text="{{ _p('allegro::pages.application.create', 'Create') }}"
                      @sended="AWEMA.emit('content::applications_table:update')">
             <div v-if="AWEMA._store.state.forms['create']">
                 <fb-input name="name" label="{{ _p('allegro::pages.application.name', 'Name') }}"></fb-input>
                <fb-input name="client_id" label="{{ _p('allegro::pages.application.client_id', 'Client ID') }}"></fb-input>
                <fb-input name="client_secret" label="{{ _p('allegro::pages.application.client_secret', 'Client secret') }}"></fb-input>
                <small v-if="AWEMA._store.state.forms['create'].fields.sandbox" class="cl-caption">
                    {!! _p('allegro::pages.application.generate_client_data', 'Generate client data at <a href=":url" target="_blank">this</a> address.', ['url' =>'https://apps.developer.allegro.pl.allegrosandbox.pl/new']) !!}
                </small>
                 <small v-else class="cl-caption">
                     {!! _p('allegro::pages.application.generate_client_data', 'Generate client data at <a href=":url" target="_blank">this</a> address.', ['url' =>'https://apps.developer.allegro.pl/new']) !!}
                 </small>
                <div class="mt-20">
                    <fb-switcher name="sandbox" label="{{ _p('allegro::pages.application.sandbox', 'Mode sandbox') }}"></fb-switcher>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="edit_application" class="modal_formbuilder" title="{{ _p('allegro::pages.application.edit_application', 'Edit application') }}">
        <form-builder name="edit_application" url="{{ route('allegro.application.update') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::applications_table:update')"
                      send-text="{{ _p('allegro::pages.application.save', 'Save') }}" store-data="editApplication">
            <div v-if="AWEMA._store.state.forms['edit_application']">
                <fb-input name="name" label="{{ _p('allegro::pages.application.name', 'Name') }}"></fb-input>
                <fb-input name="client_id" label="{{ _p('allegro::pages.application.client_id', 'Client ID') }}"></fb-input>
                <fb-input name="client_secret" label="{{ _p('allegro::pages.application.client_secret', 'Client secret') }}"></fb-input>
                <small v-if="AWEMA._store.state.forms['edit_application'].fields.sandbox" class="cl-caption">
                    {!! _p('allegro::pages.application.generate_client_data', 'Generate client data at <a href=":url" target="_blank">this</a> address.', ['url' =>'https://apps.developer.allegro.pl.allegrosandbox.pl/new']) !!}
                </small>
                <small v-else class="cl-caption">
                    {!! _p('allegro::pages.application.generate_client_data', 'Generate client data at <a href=":url" target="_blank">this</a> address.', ['url' =>'https://apps.developer.allegro.pl/new']) !!}
                </small>
                <div class="mt-20">
                    <fb-switcher name="sandbox" label="{{ _p('allegro::pages.application.sandbox', 'Mode sandbox') }}"></fb-switcher>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="delete_application" class="modal_formbuilder" title="{{  _p('allegro::pages.application.are_you_sure_delete', 'Are you sure delete?') }}">
        <form-builder :edited="true" url="{{route('allegro.application.delete') }}/{id}" method="delete"
                      @sended="AWEMA.emit('content::applications_table:update')"
                      send-text="{{ _p('allegro::pages.application.confirm', 'Confirm') }}" store-data="deleteApplication"
                      disabled-dialog>

        </form-builder>
    </modal-window>
@endsection
