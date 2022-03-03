<?php

namespace Modules\Agent\Exports\ProManager;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Modules\Agent\Entities\AgentAssignProjectStakeholder;
use Modules\Agent\Entities\Evaluation\EvaluationQuestion;

class ManagerProjectStkExport implements FromView{

    public $request;


    public function __construct($_request)
    {
        $this->request = $_request;
    }

    public function view(): View
    {

        $stakeholders_query = User::with(['stakeholder','stakeholderEvaluation','stakeholderCommnet','stakeholderCommnetForTrainer','stakeholderCommnetForDeployer','stakeholderCommnetForNmanager',
        'assignProject'=>function($q){
            $q->select('stakeholder_id','project_id');
        },'assignProject.project'])
            ->whereIn('id',$this->request->ids)
            ->get();

        $evalu_questions = EvaluationQuestion::get();

        return view('agent::export.pmanagers.selectable-stakeholder',[
            'stakeholders'=>$stakeholders_query,
            'evalu_questions'=>$evalu_questions
        ]);

    }

}
