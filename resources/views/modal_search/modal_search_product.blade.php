<!-- The Modal -->
<div class="modal modal_search" id="modalSearchProduct" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header flex-wrap p-3">
                <h4 class="modal-title ">{{ trans('product.filter') }}</h4>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="clearAllPro">
                    <i class="fa fa-times"></i>
                </button>
                <x-form::open method="GET" class="enter-index-form w-100 form-search-modal-product" action="{{ route(PRODUCT_MODAL_SEARCH_AJAX_ROUTE) }}" >
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <x-form::group :label="trans('product.code')">
                                <x-form::input name="code" :value="request('code')" />
                            </x-form::group>
                        </div>
                        <div class="col-md-4">
                            <x-form::group :label="trans('product.name')">
                                <x-form::input name="name" :value="request('name')" />
                            </x-form::group>
                        </div>
                        <div class="col-md-auto">
                            <x-form::group label="&nbsp;">
                                <button type="submit" class="btn btn-primary btn-icon">
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
                <x-table.table class="align-middle table-bordered mb-0" id="data-table-search-product">
                    <thead>
                        <th class="w-5"></th>
                        <x-table.head :name="trans('product.code')" class="w-10 text-center" />
                        <x-table.head :name="trans('product.name')" class=" text-center"/>
                        <x-table.head :name="trans('product.unit')" class=" text-center"/>
                        <x-table.head :name="trans('product.quantity')" class="w-10 text-center" />
                        <x-table.head :name="trans('product.price')" class="text-center" />
                    </thead>
                    <tbody>

                    </tbody>
                </x-table.table>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer d-flex justify-content-between p-3">
                <button type="button" class="btn btn-primary float-end" id="btn-choose-product"
                    data-bs-dismiss="modal">選択する</button>
            </div>
        </div>
    </div>
</div>
