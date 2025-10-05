<?php

namespace App\Http\Controllers;

use App\Enums\PaginationPage;
use App\Http\Requests\ProductStoreRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;
use App\General\Functions;
use App\Services\ProductAgentService;
use App\Services\Traits\OptionExport;
use App\Services\UnitService;

class Product extends Controller
{
    use OptionExport;

    /**
     * @var \App\Services\ProductService
     */
    protected $productService;
    /*
     * @var \App\Services\UnitService
     * */
    protected $unitService;
    /*
     * @var \App\Services\ProductAgentService
     * */
    protected $productAgentService;

    public function __construct(
        ProductService $productService,
        UnitService $unitService,
        ProductAgentService $productAgentService
    ){
        $this->productService = $productService;
        $this->unitService = $unitService;
        $this->productAgentService = $productAgentService;
    }

    /**
     * Show list products
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Remove flash session fields before from visited
        if (!empty(request()->old())) {
            $this->flashReset();
        }

        $this->getDataExportCsv(
            [
                'url' => route(PRODUCT_EXPORT_CSV_ROUTE),
                'btn' => 'product-export-csv'
            ]
        );
        $this->getDataSelectLimit(route(PRODUCT_ROUTE));
        $dataOptions = $this->getDataOptionExport();

        $assign = [];
        $assign['dataOptions'] = $dataOptions;

        $assign['products'] = $this->productService->search();

        return view('product.index', $assign);
    }

    /**
     * Search ajax product
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function searchAjax(Request $request)
    {
        $results = $this->productService->searchAjax();
        if ($results) {
            return response()->json(['results' => $results]);
        }

        return response()->json(["message" => "Product not found"], 404);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $assign = [
            'units' => $this->unitService->search(['all' => true]),
        ];
        return view('product.create', $assign);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $product = $this->productService->find($id);
        $assign = [
            'units' => $this->unitService->search(['all' => true]),
        ];
        // Save detail to session
        if (empty(request()->old()) || old('id') != $id) {
            $this->flashSession($product);
        }

        return view('product.edit', $assign);
    }

    /**
     * Store form
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException|\Prettus\Repository\Exceptions\RepositoryException
     */
    public function store(ProductStoreRequest $request)
    {
        $this->productService->store($request);

        return redirect()->route(PRODUCT_ROUTE);
    }

    /**
     * Delete a product
     *
     * @param \Illuminate\Http\Request $request
     * @param id $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $this->productService->delete($id);

        return redirect()->route(PRODUCT_ROUTE)->with(['message' => trans('product.deleted')]);
    }

    /**
     * Export list order
     * @param \Illuminate\Http\Request $request
     *
     * * @return \Illuminate\Http\RedirectResponse
    */
    public function exportCSV(Request $request)
    {
        app()->setLocale('ja');
        $url = $this->productService->exportCSV($request);

        return response()->json(['result' => $url], 200);
    }

    /**
     * Search product Agent
     * @param \Illuminate\Http\Request $request
     *
     * * @return \Illuminate\Http\RedirectResponse
    */
    public function searchProductAgent(Request $request)
    {
        $results = [
            'id' => 0,
            'name' => '',
            'code' => '',
            'unit' => [
                'id' => '',
                'name' => ''
            ],
            'price' => '',
            'unit_id' => 0
        ];
        $result =  $this->productAgentService->search();

        if (!$result) {
            $conditions = [
                'code' => $request->input('product_code'),
                'name' => $request->input('product_name'),
            ];
            $result = $this->productService->searchAjax($conditions);
        }

        // dd($result);

        if ($result) {
            $results = [];
            $results['id'] = $result->product->id ?? $result->id;
            $results['name'] = $result->product->name ?? $result->name;
            $results['code'] = $result->product->code ?? $result->code;
            $results['unit']['id'] = $result->unit_id ? $result->unit->id : null;
            $results['unit']['name'] = $result->unit_id ? $result->unit->name : null;
            $results['price'] = $result->price;
            $results['unit_id'] = $result->unit_id ?? $result->unit_id;
        }

        return response()->json(['result' => $results], 200);
    }

    /**
     * Search modal agent
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchModal(Request $request)
    {
        $results = $this->productService->search();

        return response()->json(['results' => $results]);
    }
}
