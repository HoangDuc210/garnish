<?php

namespace App\Services\Export;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;

abstract class Exportable {
    /**
     * @var string
    */
    protected $savePath = 'file';

    /**
     * @var array []
    */
    protected $params = [];

    /**
     * Path save file
     */
    public function setConfig($params = [])
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Path save file
     */
    public function changePath($path)
    {
        $this->savePath = $path;
        return $this;
    }

    /**
     * Save pdf file to storage
     *
     * @return string Path file
     */
    public function savePdf($fileName = null)
    {
        if (is_null($fileName)) {
            $fileName = date('YmdHis') . Str::random(15) . '.pdf';
        }
        Storage::append($this->savePath . $fileName, $this->pdfHandle());
        return $this->savePath . $fileName;
    }

    /**
     * Download pdf file
     *
     * @return string Path file
     */
    public function downloadPdf()
    {
        try {
            $fileName = date('YmdHis') . Str::random(15) . '.pdf';
            $pdf = new Mpdf($this->configPdf());
            $pdf->AddPageByArray($this->params);
            $pdf->WriteHTML($this->view()->render());
            return $pdf->Output($fileName, 'D');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return string|void
     */
    protected function pdfHandle()
    {
        $configMarginDefaults = [
            'margin_left'   => 8,
            'margin_right'  => 8,
            'margin_top'    => 10,
            'margin_bottom' => 10,
        ];
        $configs = array_merge($this->configPdf(), $this->params ?? $configMarginDefaults);
        try {
            $pdf = new Mpdf($configs);
            $pdf->AddPageByArray($this->params);
            $pdf->WriteHTML($this->view()->render());
            return $pdf->Output('', 'S');
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * @return array
     */
    protected function configPdf()
    {
        $defaultConfig     = (new ConfigVariables())->getDefaults();
        $fontDirs          = $defaultConfig['fontDir'];
        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData          = $defaultFontConfig['fontdata'];

        return [
            'fontDir'       => array_merge($fontDirs, [
                storage_path('fonts'),
            ]),
            'fontdata'      => $fontData + [
                'mincho' => [
                    'R' => 'yugothib.ttf',
                ],
            ],
            'format'        => 'A4-P', //A4-L
            'default_font'  => 'mincho',
            'tempDir'       => storage_path(''),
        ];
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    abstract protected function view();
}
