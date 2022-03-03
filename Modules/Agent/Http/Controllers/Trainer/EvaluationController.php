<?php

namespace Modules\Agent\Http\Controllers\Trainer;

use App\Events\DataInsertedEvent;
use App\Events\ErrorEvent;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Agent\Entities\Evaluation\EvaluationQuestion;
use Modules\Agent\Entities\Evaluation\StakeholderEvaluation;
use Modules\Agent\Entities\Evaluation\StakeholderEvaluationDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class EvaluationController extends Controller
{

    public function evaluationFrom($module,$user_id)
    {
        $user_evaluation = StakeholderEvaluation::with('evaluationDetails')->where(['user_id'=>$user_id])->first();
        $questions = EvaluationQuestion::all();
        return view("agent::stakeholders.evaluations.question",[
            'user_id'=>$user_id,
            'module'=>$module,
            'questions'=>$questions,
            'user_evaluation'=>$user_evaluation
        ]);

    }


    public function evaluationFromSubmit(Request $request,$module,$user_id)
    {

        try{

            DB::beginTransaction();

            $inputs = $request->all();
            unset($inputs['_token']);

            $stakeholder_evalu = StakeholderEvaluation::create([
                'user_id'=>$user_id,
                'agent_id'=>auth()->user()->id,
                'mark'=>0,
                'status'=>0
            ]);

            $total_mark = 0;
            $pass_mark = 50;

            foreach($inputs as $k => $input){
                $evaluation_ques = EvaluationQuestion::where(['id'=>$k])->first();

                if(is_array($input)){
                    $correct_ans = explode(":",$evaluation_ques->correct_answer);
                    $single_answer_status = 0;
                    $submitted_answer = implode(":",$input);
                    $result = array_diff($correct_ans, $input);
                    $mark = 0;

                    if(count($result) == 0){
                        $single_answer_status = 1;
                        $total_mark += $evaluation_ques->mark;
                        $mark = $evaluation_ques->mark;
                    }

                    StakeholderEvaluationDetails::create([
                        'agent_stkholder_evalu_id'=>$stakeholder_evalu->id,
                        'agent_ev_qus_id'=>$evaluation_ques->id,
                        'answer'=>$submitted_answer,
                        'status'=>$single_answer_status,
                        'mark'=>$mark,
                        'user_id'=>$user_id,
                    ]);

                   // dd($input);
                }else if(is_string($input)){

                    $single_answer_status = 0;
                    $mark = 0;
                    if($evaluation_ques->question_type == '3'){
                        $single_answer_status = 1;
                        $total_mark += $evaluation_ques->mark;
                        $mark = $evaluation_ques->mark;
                    }else if($input == $evaluation_ques->correct_answer){
                        $single_answer_status = 1;
                        $total_mark += $evaluation_ques->mark;
                        $mark = $evaluation_ques->mark;
                    }

                    StakeholderEvaluationDetails::create([
                        'agent_stkholder_evalu_id'=>$stakeholder_evalu->id,
                        'agent_ev_qus_id'=>$evaluation_ques->id,
                        'answer'=>$input,
                        'status'=>$single_answer_status,
                        'mark'=>$mark,
                        'user_id'=>$user_id,
                    ]);

                }

            }

            $pass_or_fail = 0;
            if($total_mark >= $pass_mark){
                $pass_or_fail = 1;
            }

            StakeholderEvaluation::where(['id'=>$stakeholder_evalu->id])->update([
                'mark'=>$total_mark,
                'status'=>$pass_or_fail
            ]);


            DB::commit();
            Session::flash('success',"Submit your evaluation successfully.");
            return redirect()->back();

        }catch(Exception $ex){
            Session::flash('error',$ex->getMessage());
            return redirect()->back();
        }

    }



    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('agent::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('agent::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('agent::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('agent::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
