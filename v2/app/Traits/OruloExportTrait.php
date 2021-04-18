<?php

namespace App\Traits;

use App\Http\Services\Orulo\Export\Buildings as Sheet;
use App\Jobs\GenerateReport;
use Storage;
use Carbon\Carbon;

trait OruloExportTrait
{
    public function sheetIndex() {
        $file = Storage::disk('public')->has('orulo/orulo_empreendimentos.xlsx');

        if($file) {
            $file = [
                'name' => 'orulo_empreendimentos.xlsx',
                'date' => Carbon::createFromTimestamp(Storage::disk('public')->lastModified('orulo/orulo_empreendimentos.xlsx'))
            ];
        }

        return view('orulo.sheet', ['file' => $file]);
    }

    public function sheetGenerate() {
        $sheet = new Sheet();

        try {
            $sheet->store('orulo/orulo_empreendimentos.xlsx');
        } catch (\Exception $e) {
            return redirect()->back()->withErros($e);
        }

        return redirect()->back()->with('success', 'Arquivo gerado');
    }

    public function sheetDownload() {
        return Storage::disk('public')->download('orulo/orulo_empreendimentos.xlsx');
    }
}
