<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Lead\Rotation;
use App\Lead\Pre;
use App\User;
use App\Campaign;

trait RotationTrait
{
    public function RotationIndex(Request $request) {
        $view = 'rotation.sale.index';
        $type = $request->route()->getName() == 'config.pre.sale.rotation.sale' ? 'presale' : 'preqa';
        $rotation = Rotation::where('type', $type);

        if($request->campaign_id) {
            $campaign = Campaign::withCount([
                                    'leads as pos_count',
                                    'pre as pre_count' => function ($query) {
                                        $query->whereNull('sent_at');
                                    }
                                ])
                                ->find($request->campaign_id);

            $campaign->leads_total = $campaign->pos_count + $campaign->pre_count;
            $campaign->pre_percentage = $campaign->pre_count !== 0 ? number_format(($campaign->pre_count / $campaign->leads_total) * 100, 2) : '0';
            $campaign->pos_percentage = $campaign->pos_count !== 0 ? number_format(($campaign->pos_count / $campaign->leads_total) * 100, 2) : '0';

            $rotation->where('campaign_id', $request->campaign_id);
            $view = 'rotation.qa.index';
        }

        return view($view, ['data' => $rotation->first(), 'campaign' => isset($campaign) ? $campaign : null]);
    }

    public function RotationStore(Request $request) {
        if(isset($request->enable)) {
            $request->request->add(['percentage' => $request->enable ? '100' : '0']);
        }

        if($request->campaign_id) {
            $campaign = Campaign::with('rotation')->find($request->campaign_id);
            if($campaign->rotation) {
                $campaign->rotation->update($request->all());
                return response('Atualizado!', 200);
            }else{
                $rotatio = $campaign->rotation()->create($request->all());
                return response('Definido!', 200);
            }
        }

        if($request->id) {
            $rotation = Rotation::find($request->id);
            $rotation->update($request->all());

            return response('Atualizado!', 200);
        }else{
            $rotation = new Rotation();
            $rotation = $rotation->fill($request->all());
            $rotation->save();

            return response('Definido!', 200);
        }
    }

    public function RotationGet($request) {
        if($request->campaign_id) {
            $rotation = Rotation::where('campaign_id', $request->campaign_id);
        } else {
            $rotation = Rotation::where('type', 'presale');
        }

        return $rotation->first();
    }

    public function RotationProbability($rotation){
        if(rand(0, 100) <= $rotation->percentage) {
            return true;
        }

        return false;
    }

    public function RotationInsert(Request $request) {
        $rotation = Self::RotationGet($request);

        if($rotation && $rotation->autoqa && $rotation->type == 'posqa') {
            if(Self::RotationProbability($rotation)) return 'pos-auto';
            return $rotation->type;
        }

        if($rotation && $rotation->autoqa && $rotation->type == 'preqa') {
            if(Self::RotationProbability($rotation)) return 'pre-auto';
            return $rotation->type;
        }

        if($rotation && $rotation->type == 'presale' && Self::RotationProbability($rotation)) {

            if($rotation->autoqa) {
                return 'auto';
            }

            $lead = Pre::find($request->lead_id);
            $user = User::where('name', 'like', 'CRM')->first();
            $qa = $lead->qa()->create()->agent()->associate($user);
            $qa->save();

            return 'presale';
        }elseif($rotation && $rotation->type == 'preqa') {
            if(Self::RotationProbability($rotation)) return 'preqa';

            return 'posqa';
        }

        return 'false';
    }
}
