<?php

namespace Modules\Agent\Exports\Nmanager;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Modules\Agent\Entities\Evaluation\EvaluationQuestion;
use Modules\Agent\Entities\Stakeholder;

class NmanagerRejSelectableExport implements FromView{

    public $request;

    public function __construct($_request)
    {
        $this->request = $_request;
    }


    public function view(): View
    {
        $stakeholders_query = User::with(['stakeholder','stakeholderEvaluation','stakeholderCommnet','signupReference','stakeholderCommnetForTrainer','stakeholderEvalDetails','stakeholderCommnetForDeployer','stakeholderCommnetForNmanager','assignProject'=>function($q){
            $q->select('stakeholder_id','project_id');
        },'assignProject.project'])
            ->where(['spatie_role_id'=>$this->request->role_id])
            ->whereIn('id',$this->request->ids)
            ->orderBy(Stakeholder::select('deployer_status')->whereColumn('agent_stakeholders.user_id','sujog_users.id'))
            ->get();

        $evalu_questions = EvaluationQuestion::get();

        return view('agent::export.nmanagers.selectable-stakeholder',[
            'stakeholders'=>$stakeholders_query,
            'evalu_questions'=>$evalu_questions
        ]);
    }

}
