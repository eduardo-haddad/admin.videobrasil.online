<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Storage;

trait PreSaleTextTrait
{
    /**
     *
     */
    public function TextIndex() {

        $file = Storage::disk('public')->has('config/qa/sale-text.txt');

        $files['ca'] = Storage::disk('public')->has('config/pre/sale-ca-text.txt');
        $files['va'] = Storage::disk('public')->has('config/pre/sale-va-text.txt');
        $files['si'] = Storage::disk('public')->has('config/pre/sale-si-text.txt');

        return view('text.pre.index', ['files' => $files]);
    }

    public function TextEdit (Request $request) {
        $file = Storage::disk('public')->get('config/pre/sale-'.$request->presale_type.'-text.txt');

        return view('text.pre.edit', ['file' => $file, 'presale_type' => $request->presale_type]);
    }

    public function TextCreate (Request $request) {
        return view('text.pre.edit', ['file' => false, 'presale_type' => $request->get('presale_type')]);
    }

    public function TextStore(Request $request) {
        Storage::put('config/pre/sale-'.$request->presale_type.'-text.txt', $request->content);

        return redirect()->back()->withInput();
    }
}
