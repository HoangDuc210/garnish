<?php

namespace App\Services\Export;

use Illuminate\Support\Carbon;
use Webklex\PDFMerger\Facades\PDFMergerFacade;

class MergePdf {
    /**
     * @var string
    */
    protected $savePath;

    /**
     * Save path
    */
    public function savePath($filename, $path)
    {
        $date = Carbon::now()->format('Ymd');
        $this->savePath = $path . $filename .  '_' . $date . '.pdf';
        return $this;
    }

    /**
     * Save filename
    */
    public function save($files, $download = false)
    {
        $oMerger = PDFMergerFacade::init();

        foreach($files as $file) {
            $oMerger->addPDF(public_path($file), 'all');
        }

        $oMerger->merge();

        //Download file
        if ($download) {
            return $oMerger->download();
        }
        //Save file
        $oMerger->save(public_path($this->savePath));
        return url($this->savePath);
    }
}