<?php

namespace App\Services\Traits;

use App\Enums\PaginationPage;

trait OptionExport
{
    /**
     * Setup data
     * @return void array
     */
    protected $preview = [];
    protected $exportCsv = [];
    protected $print = [];
    protected $printN335 = [];
    protected $prevPage = null;
    protected $nextPage = null;
    protected $selectLimit = [];
    protected $checkboxNextPrevPage = [];
    protected $removesItem = [];
    protected $btnEdit = [];
    protected $btnCreate = [];

    /**
     * Get data export preview
     *
     * @return mixed
     */
    public function getDataOptionExport()
    {
        $dataOptionsExport = [];
        $dataOptionsExport['preview'] = $this->preview;
        $dataOptionsExport['exportCsv'] = $this->exportCsv;
        $dataOptionsExport['print'] = $this->print;
        $dataOptionsExport['printN335'] = $this->printN335;
        $dataOptionsExport['prevPage'] = $this->prevPage;
        $dataOptionsExport['nextPage'] = $this->nextPage;
        $dataOptionsExport['checkboxNextPrevPage'] = $this->checkboxNextPrevPage;
        $dataOptionsExport['selectLimit'] = $this->selectLimit;
        $dataOptionsExport['removesItem'] = $this->removesItem;
        $dataOptionsExport['btnEdit'] = $this->btnEdit;
        $dataOptionsExport['btnCreate'] = $this->btnCreate;

        return$dataOptionsExport;
    }

    /**
     * Get data export preview
     *
     * @return mixed
     */
    public function getDataPreview($data = [], $options = [])
    {
        $data = $this->exceptKey($data);
        foreach ($data as $key => $value)
        {
            if (is_array($value)) {
                foreach ($value as $k => $v)
                {
                    $this->preview[$key. '_' .$k] = $v;
                }
            }else{
                $this->preview[$key] = $value;
            }
        }

        foreach ($options as $key => $value)
        {
            $this->preview[$key] = $value;
        }

        return $this->preview;
    }

    /**
     * Get data export csv
     *
     * @return mixed
     */
    public function getDataExportCsv($data = [], $options = [])
    {
        $data = $this->exceptKey($data);
        foreach ($data as $key => $value)
        {
            if (is_array($value)) {
                foreach ($value as $k => $v)
                {
                    $this->exportCsv[$key. '_' .$k] = $v;
                }
            }else{
                $this->exportCsv[$key] = $value;
            }
        }

        foreach ($options as $key => $value)
        {
            $this->exportCsv[$key] = $value;
        }

        return $this->exportCsv;
    }

    /**
     * Get data export print
     *
     * @return mixed
     */
    public function getDataPrint($data = [], $options = [])
    {
        $data = $this->exceptKey($data);
        foreach ($data as $key => $value)
        {
            if (is_array($value)) {
                foreach ($value as $k => $v)
                {
                    $this->print[$key. '_' .$k] = $v;
                }
            }else{
                $this->print[$key] = $value;
            }
        }

        foreach ($options as $key => $value)
        {
            $this->print[$key] = $value;
        }

        return $this->print;
    }

    /**
     * Get data export printN335
     *
     * @return mixed
     */
    public function getDataPrintN335($data = [], $options = [])
    {
        $data = $this->exceptKey($data);
        foreach ($data as $key => $value)
        {
            if (is_array($value)) {
                foreach ($value as $k => $v)
                {
                    $this->printN335[$key. '_' .$k] = $v;
                }
            }else{
                $this->printN335[$key] = $value;
            }
        }

        foreach ($options as $key => $value)
        {
            $this->printN335[$key] = $value;
        }

        return $this->printN335;
    }

    /**
     * Get data export prevPage
     *
     * @return mixed
     */
    public function getDataPrevPage()
    {
        return $this->prevPage = true;
    }

    /**
     * Get data export nextPage
     *
     * @return mixed
     */
    public function getDataNextPage()
    {
        return $this->nextPage = true;
    }

    /**
     * Get data selectLimit
     *
     * @return mixed
     */
    public function getDataSelectLimit($url=null)
    {
        $this->selectLimit['url'] = $url;
        $this->selectLimit['limits'] = PaginationPage::options();

        return $this->selectLimit;
    }

    /**
     * Checkbox next prev page
     *
     * @return mixed
     */
    public function getCheckboxNextPrevPage($id, $url)
    {
        $this->checkboxNextPrevPage = [
            'id' => $id,
            'url' => $url,
        ];

        return $this->checkboxNextPrevPage;
    }

    /**
     * Removes item
    */
    public function removesItem($url, $message = null)
    {
        $this->removesItem['url'] = $url;
        $this->removesItem['message'] = !is_null($message) ? $message : trans('app.alert_delete');
        return $this->removesItem;
    }

    /**
     * Btn detail
    */
    public function btnEdit($url)
    {
        $this->btnEdit['url'] = $url;
        return $this->btnEdit;
    }

    /**
     * Btn detail
    */
    public function btnCreate($url)
    {
        $this->btnCreate['url'] = $url;
        return $this->btnCreate;
    }

    /**
     * Except key in data array
     * @param array $data
     * @return array
     */
    protected function exceptKey($data)
    {
        unset($data['limit']);
        unset($data['page']);
        unset($data['sort']);

        return $data;
    }
}
