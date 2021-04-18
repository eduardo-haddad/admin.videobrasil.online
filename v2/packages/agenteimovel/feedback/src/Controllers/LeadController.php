<?php

namespace Feedback\Controllers;

use Validator;
use Carbon\Carbon;
use App\Lead\Lead;
use Feedback\Lead\Access;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeadController extends Controller
{
    /**
     *
     */
    public function index(Request $request)
    {
        if($request->has('id')){
            $id = $request->get('id');

            if(Lead::where('lead_id', $id)->exists()){
                return redirect(_route('feedback.leads.show', ['lead' => $id]));
            } else {
                $validator = Validator::make($request->all(), []);
                $validator->errors()->add('id', 'Código inválido.');

                return back()->withErrors($validator);
            }
        }

        return view('feedback::leads.index');
    }

    /**
     * Create temporary access for the user.
     */
    public function questionnaire(Request $request, $id)
    {
        $request->validate([
            'answer' => 'required|string|in:' . implode(',', array_keys(Access::getAnswers()))
        ]);

        $request->user()->accesses()->create([
            'lead_id' => $id,
            'answer' => $request->get('answer'),
            'expired_at' => Carbon::now()->addMinutes(10)
        ]);

        return back();
    }

    /**
     * Show the given resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $access = $request->user()->accesses()->notExpired($id)->first();
        $lead = Lead::findOrFail($id);
        $lead->load('qa.contact_attempts', 'listing.newconst', 'accesses');
        $lead->accesses = $lead->accesses->reverse();

        $attempts = $lead->accesses->filter(function($access){
            return $access->answer != 'checking_info';
        });

        $status = $attempts->search(function($attempt){
            return in_array($attempt->answer, ['lead_interested', 'future_potential', 'talked_n_discarted']);
        }) ? 'Confirmado' : 'Sem confirmação';

        return view('feedback::leads.show', [
            'lead' => $lead,
            'access' => $access,
            'feedback' => [
                'first_visit' => $lead->accesses->last(),
                'first_contact_attempt' => $attempts->last(),
                'attempts_count' => $attempts->count(),
                'status' => $status
            ]
        ]);
    }
}
