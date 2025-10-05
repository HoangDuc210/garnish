<!-- The Modal -->
<div class="modal modal_search" id="modalSearchAgent" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header flex-wrap p-3">
                <h4 class="modal-title ">{{ trans('agent.filter') }}</h4>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="clearAgent">
                    <i class="fa fa-times"></i>
                </button>

                <x-form::open method="GET" class="enter-index-form form-search-modal-agent w-100"
                    action="{{ route(AGENT_MODAL_SEARCH_AJAX_ROUTE) }}">
                    <div class="row align-items-center col">
                        <div class="col-md-2">
                            <x-form::group :label="trans('agent.filters.code')">
                                <x-form::input name="code" :value="request('code')" />
                            </x-form::group>
                        </div>
                        <div class="col-md-5">
                            <x-form::group :label="trans('agent.filters.name')">
                                <x-form::input name="name" :value="request('name')" />
                            </x-form::group>
                        </div>

                        <div class="col-md-7">
                            <x-form::group :label="trans('agent.filters.parent')">
                                <x-form::input name="parent" :value="request('parent')" />
                            </x-form::group>
                        </div>
                        <div class="col-md-5">
                            <x-form::group label="&nbsp;">
                                <button type="submit" class="btn btn-primary btn-icon ">
                                    <i data-acorn-icon="search"></i>
                                    {{ trans('app.search') }}
                                </button>
                            </x-form::group>
                        </div>
                    </div>
                </x-form::open>
            </div>
            <!-- Modal body -->
            <div class="modal-body  p-3">
                <x-table.table class="align-middle table-bordered mb-0" id="data-table-search-agent">
                    <thead>
                        <th class="w-5"></th>
                        <x-table.head :name="trans('agent.code')" class="w-10 text-center" />
                        <x-table.head :name="trans('agent.name')" class="text-center" />
                        <x-table.head :name="trans('agent.zip_code')" class="w-10 text-center" />
                        <x-table.head :name="trans('agent.address')" class="w-20" />
                        <x-table.head :name="trans('agent.address_more')" class="text-center" />
                        <x-table.head :name="trans('agent.tel')" class="w-10 text-center" />
                        <x-table.head :name="trans('agent.fax')" class="w-10 text-center" />
                    </thead>
                    <tbody>

                    </tbody>
                </x-table.table>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer d-flex justify-content-between p-3">
                <button type="button" class="btn btn-primary float-end" id="btn-choose-agent"
                    data-bs-dismiss="modal">選択する</button>
            </div>
        </div>
    </div>
</div>
