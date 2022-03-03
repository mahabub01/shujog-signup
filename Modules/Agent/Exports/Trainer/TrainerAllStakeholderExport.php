<?php

namespace Modules\Agent\Exports\Trainer;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Modules\Agent\Entities\Evaluation\EvaluationQuestion;


class TrainerAllStakeholderExport implements FromView{

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
            ->where(['spatie_role_id'=>$this->request->role]);

        if($this->request->search != ""){
            $stakeholders_query->where('name','like','%'.$this->request->search.'%')
                ->orWhere(['mobile'=>$this->request->search]);
        }

        if($this->request->start_date != "" && $this->request->end_date != ""){
            $stakeholders_query->whereBetween('created_at',[$this->request->start_date.'00.00.00',$this->request->end_date.'23.59.59']);
        }


        if($this->request->division_id != ""){
            $stakeholders_query->where(['division_id'=>$this->request->division_id]);
        }

        if($this->request->district_id != ""){
            $stakeholders_query->where(['district_id'=>$this->request->district_id]);
        }

        if($this->request->upazila_id != ""){
            $stakeholders_query->where(['upazila_id'=>$this->request->upazila_id]);
        }

        if($this->request->reference_id != ""){
            $stakeholders_query->where(['signup_reference_id'=>$this->request->reference_id]);
        }

        if($this->request->status != ""){
            $stakeholders_query->whereHas('stakeholder',function($query){
                $query->where('consultant_status','3')->where('trainer_status',$this->request->status);
            });
        }else{
            $stakeholders_query->whereHas('stakeholder',function($query){
                $query->whereIn('trainer_status',[1,2,3])->where('consultant_status','3');
            });
        }

        $data =  $stakeholders_query->orderBy('id','asc')->get();
        $evalu_questions = EvaluationQuestion::get();

        return view('agent::export.trainers.all-stakeholders',[
            'stakeholders'=>$data,
            'evalu_questions'=>$evalu_questions
        ]);
    }

}
