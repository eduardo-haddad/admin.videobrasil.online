<?php

namespace App\Traits;

use App\Report;
use App\Jobs\GenerateReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


trait ReportTrait
{
    /**
     * Display a listing of the resource.
     */
    public function indexReport(Request $request)
    {
        $reports = Report::where('subject_type', $this->subjectType);
        $reports->orderBy('created_at', 'DESC');

        return view('reports.index', [
            'reports' => $reports->paginate(15),
            'subject' => $this->subjectType
        ] + $this->reportContext);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeReport(Request $request)
    {
        $type = $request->get('type');
        $namespace = explode('.', $type);
        $baseName = studly_case(array_pop($namespace));

        $namespace = array_map(function ($n) {
            return studly_case($n);
        }, $namespace);

        $namespace = implode('\\', $namespace);
        $reflection = new \ReflectionClass("App\Http\Services\Reports\\$namespace\\$baseName");
        $report = $reflection->newInstanceArgs([$request->except(['_token', 'type'])]);

        $model = Report::create([
            'name' => $request->get('name'),
            'type' => $type,
            'subject_id' => $request->get('subject_id'),
            'subject_type' => $this->subjectType,
        ]);

        GenerateReport::dispatch($report, $model);
        return redirect()->back()->with('success', 'O relatório está sendo processado, aguarde.');
    }
}
