@extends('indigo-layout::main')

@section('meta_title', _p('allegro::pages.account.meta_title', 'Accounts') . ' - ' . config('app.name'))
@section('meta_description', _p('allegro::pages.account.meta_description', 'Accounts in application'))

@push('head')

@endpush

@section('title')
    {{ _p('allegro::pages.account.headline', 'Accounts') }}
@endsection

@section('create_button')
    <button class="frame__header-add" @click="AWEMA.emit('modal::connect:open')" title="{{ _p('allegro::pages.account.connect_account', 'Create account') }}"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('allegro::pages.account.accounts', 'Account') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('allegro.account.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="accounts_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="username" label="{{ _p('allegro::pages.account.user', 'User') }}">
                                    <template slot-scope="col">
                                        <template v-if="col.data.username">
                                            @{{ col.data.username }}
                                        </template>
                                        <template v-else>
                                           <span class="cl-caption">
                                                {{ _p('allegro::pages.account.process_connecting', 'In the process of connecting') }}
                                           </span>
                                        </template>
                                    </template>
                                </tb-column>
                                <tb-column name="expires_at" label="{{ _p('allegro::pages.account.expires_at', 'Expires at') }}"></tb-column>
                                <tb-column name="created_at" label="{{ _p('allegro::pages.account.created_at', 'Created at') }}"></tb-column>
                                <tb-column name="manage" label="{{ _p('allegro::pages.account.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('allegro::pages.account.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'editAccount', data: col.data}); AWEMA.emit('modal::edit_account:open')">
                                                {{_p('allegro::pages.account.edit', 'Edit')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'reconnectAccount', data: col.data}); AWEMA.emit('modal::reconnect_account:open')">
                                                {{_p('allegro::pages.account.reconnect', 'Reconnect')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'deleteAccount', data: col.data}); AWEMA.emit('modal::delete_account:open')">
                                                {{_p('allegro::pages.account.delete', 'Delete')}}
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

    <modal-window name="connect" class="modal_formbuilder" title="{{ _p('allegro::pages.account.connect_account', 'Create account') }}">
        <form-builder name="connect"  :edited="true" url="{{ route('allegro.account.store') }}"
                      send-text="{{ _p('allegro::pages.account.connect', 'Connect') }}" disabled-dialog>
             <fb-switcher name="own_application" label="{{ _p('allegro::pages.account.own_application', 'Choose your own Allegro application..') }}"></fb-switcher>
            <div v-if="AWEMA._store.state.forms['connect'] && AWEMA._store.state.forms['connect'].fields.own_application">
               <div class="mt-20">
                   <fb-select name="application_id" :multiple="false" url="{{ route('allegro.application.scope') }}?q=%s" open-fetch options-value="id" options-name="name"
                              label="{{ _p('allegro::pages.account.select_application', 'Select application') }}">
                   </fb-select>
               </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="edit_account" class="modal_formbuilder" title="{{ _p('allegro::pages.account.edit_account', 'Edit account') }}">
        <form-builder url="{{ route('allegro.account.update') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::accounts_table:update')"
                      send-text="{{ _p('allegro::pages.account.save', 'Save') }}" store-data="editAccount">
            <div class="grid" v-if="AWEMA._store.state.editAccount">
                <div class="cell">
                    <fb-date name="expires_at" format="YYYY-MM-DD HH:mm:ss" label="{{ _p('allegro::pages.account.expiration_date', 'Expiration date') }}"></fb-date>
                    <fb-textarea name="comment" label="{{ _p('allegro::pages.account.comment', 'Comment') }}"></fb-textarea>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="reconnect_account" class="modal_formbuilder" title="{{  _p('allegro::pages.account.are_you_sure_reconnect', 'Are you sure reconnect?') }}">
        <form-builder :edited="true" url="{{route('allegro.account.reconnect') }}/{id}" method="post"
                      @sended="AWEMA.emit('content::accounts_table:update')"
                      send-text="{{ _p('allegro::pages.account.confirm', 'Confirm') }}" store-data="reconnectAccount"
                      disabled-dialog>
        </form-builder>
    </modal-window>

    <modal-window name="delete_account" class="modal_formbuilder" title="{{  _p('allegro::pages.account.are_you_sure_delete', 'Are you sure delete?') }}">
        <form-builder :edited="true" url="{{route('allegro.account.delete') }}/{id}" method="delete"
                      @sended="AWEMA.emit('content::accounts_table:update')"
                      send-text="{{ _p('allegro::pages.account.confirm', 'Confirm') }}" store-data="deleteAccount"
                      disabled-dialog>

        </form-builder>
    </modal-window>
@endsection
