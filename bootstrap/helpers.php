<?php


//Return Agent User Flag
function getAgentUserFlag(){
    return [19,20,21,22,23,24];
}


//Return Agent User Flag
function getAgentConsultantUserFlag(){
    return [19,24];
}


//Return Role Flag for Trainer
function getAgentTrainerUserFlag(){
    return [20,25];
}


//Return Role Flag for Deployer
function getAgentDeployerUserFlag(){
    return [21,26];
}


//Return Role Flag for Project Manager
function getAgentPmanagerUserFlag(){
    return [23,27];
}


//Return Role Flag for Project Manager
function getAgentNmanagerUserFlag(){
    return [22,28];
}


//Return Agent User Flag
function getRoleUsingFlag($flag){
    switch($flag){
        case 19:
            return "Admin Consultant";
        case 24:
            return "Member Consultant";
            default:
        return "None";
    }

}



function last_modify_human_date($last_data){
    $human_date = null;
    if(!is_null($last_data)){
        $human_date = $last_data->updated_at->diffForHumans();
    }
    return $human_date;
}



function sendSms($number, $message, $is_masking = null)
{

    /*
     * api
     * Your API Key : R60013965fe95ca7418033.97301390
     * API URL (GET & POST) : http://sms.isocial.com.bd/smsapi?api_key=(APIKEY)&type=text&contacts=(NUMBER)&senderid=(Approved Sender ID)&msg=(Message Content)
     *  */

    $fields_string = null;

    // rana vai from a2i
    //$url = "https://api2.onnorokomsms.com/HttpSendSms.ashx?op=OneToOne&type=TEXT&mobile=" . $number . "&smsText=" . urlencode($message) . "&username=01612363773&password=asd12300";

    // isocial
    // $url = "http://sms.isocial.com.bd/smsapi?api_key=R60013965fe95ca7418033.97301390&type=text&contacts=" . $number . "&senderid=8809612446548&msg=" . $message;

    // iSocial

    #non masking
    #$url = "http://sms.isocial.com.bd/smsapi?api_key=R6001396603784469130a1.62764000&type=text&contacts=" . $number . "&senderid=8809612446548&msg=" . urlencode($message);

    #masking
    #$url = "http://sms.isocial.com.bd/smsapi?api_key=R6001396603784469130a1.62764000&type=text&contacts=" . $number . "&senderid=Shujog.xyz&msg=" . urlencode($message);

    #check masking or non-masking

    $sender_id = "";

    if ($is_masking == 'masking') {

        $sender_id = "Shujog.xyz";

    } else {

        $sender_id = "8809612446548";
    }

    #masking
    $url = "http://sms.isocial.com.bd/smsapi?api_key=R6001396603784469130a1.62764000&type=text&contacts=" . $number . "&senderid=" . $sender_id . "&msg=" . urlencode($message);


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_POST, 2);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);


    $result = curl_exec($ch);
    if ($result === false) {
        echo sprintf('<span>%s</span>CURL error:', curl_error($ch));
        return;
    }

    $json_result = json_decode($result);
    curl_close($ch);


}



function isJsonData($value)
{
    $json = json_decode($value);
    if (isset($json->{App::getLocale()})) {
        return $json->{App::getLocale()};
    } else {
        return $value;
    }
}





function getConsultantStatus($cond = false,$sts = array())
{
    $status = [
        '1'=>'Pending',
        '2'=>'Hold On',
        '3'=>'Complete',
        '4'=>'Reject',
    ];

    if($cond){
        foreach($sts as $s){
            unset($status[$s]);
        }
    }

    return $status;
}



function getNetworkMngStatus($cond = false,$sts = array())
{
    $status = [
        '5'=>'Active',
        '6'=>'In Active',
        '7'=>'Drop Out',
    ];

    if($cond){
        foreach($sts as $s){
            unset($status[$s]);
        }
    }

    return $status;
}


function getStatusFullForm($status)
{
    switch($status){
        case 1:
        return "<span style='color:silver'>Pending</span>";

        case 2:
            return "<span style='color:orange'>Hold On</span>";

        case 3:
            return "<span style='color:green'>Complete</span>";

        case 4:
            return "<span style='color:red'>Reject</span>";

        case 5:
            return "<span style='color:green'>Active</span>";

        case 6:
            return "<span style='color:orange'>In-active</span>";

        case 7:
            return "<span style='color:red'>Dropout</span>";

        default:
            return "Not Set";
    }
}

//5 = active, 6= inactive, 7=dropout


function getPossibleAnsSingle($data){
        // Single select
        if($data->ans_a != "" && $data->ans_b != "" && $data->ans_c != "" && $data->ans_d != ""){
            echo '<div class="col-md-12"><label><input type="radio" name="'.$data->id.'" value="a"/> '.$data->ans_a.'</label></div>';
            echo '<div class="col-md-12"><label><input type="radio" name="'.$data->id.'" value="b"/> '.$data->ans_b.'</label></div>';
            echo '<div class="col-md-12"><label><input type="radio" name="'.$data->id.'" value="c"/> '.$data->ans_c.'</label></div>';
            echo '<div class="col-md-12"><label><input type="radio" name="'.$data->id.'" value="d"/> '.$data->ans_d.'</label></div>';
        }else if($data->ans_a != "" && $data->ans_b != "" && $data->ans_c != ""){
            echo '<div class="col-md-12"><label><input type="radio" name="'.$data->id.'" value="a"/> '.$data->ans_a.'</label></div>';
            echo '<div class="col-md-12"><label><input type="radio" name="'.$data->id.'" value="b"/> '.$data->ans_b.'</label></div>';
            echo '<div class="col-md-12"><label><input type="radio" name="'.$data->id.'" value="c"/> '.$data->ans_c.'</label></div>';
        }else if($data->ans_a != "" && $data->ans_b != ""){
            echo '<div class="col-md-12"><label><input type="radio" name="'.$data->id.'" value="a" required/> '.$data->ans_a.'</label></div>';
            echo '<div class="col-md-12"><label><input type="radio" name="'.$data->id.'" value="b" required/> '.$data->ans_b.'</label></div>';
        }
}



function getPossibleAnsMulti($data){
    //multi select
    if($data->ans_a != "" && $data->ans_b != "" && $data->ans_c != "" && $data->ans_d != "" && $data->ans_e != ""){
        echo '<div class="col-md-12"><label><input type="checkbox" name="'.$data->id.'[]" value="a" requried/> '.$data->ans_a.'</label></div>';
        echo '<div class="col-md-12"><label><input type="checkbox" name="'.$data->id.'[]" value="b" requried/> '.$data->ans_b.'</label></div>';
        echo '<div class="col-md-12"><label><input type="checkbox" name="'.$data->id.'[]" value="c" requried/> '.$data->ans_c.'</label></div>';
        echo '<div class="col-md-12"><label><input type="checkbox" name="'.$data->id.'[]" value="d" requried/> '.$data->ans_d.'</label></div>';
        echo '<div class="col-md-12"><label><input type="checkbox" name="'.$data->id.'[]" value="e" requried/> '.$data->ans_e.'</label></div>';
    }else if($data->ans_a != "" && $data->ans_b != "" && $data->ans_c != "" && $data->ans_d != ""){
        echo '<div class="col-md-12"><label><input type="checkbox" name="'.$data->id.'[]" value="a" requried/> '.$data->ans_a.'</label></div>';
        echo '<div class="col-md-12"><label><input type="checkbox" name="'.$data->id.'[]" value="b" requried/> '.$data->ans_b.'</label></div>';
        echo '<div class="col-md-12"><label><input type="checkbox" name="'.$data->id.'[]" value="c" requried/> '.$data->ans_c.'</label></div>';
        echo '<div class="col-md-12"><label><input type="checkbox" name="'.$data->id.'[]" value="d" requried/> '.$data->ans_d.'</label></div>';
    }else if($data->ans_a != "" && $data->ans_b != "" && $data->ans_c != ""){
        echo '<div class="col-md-12"><label><input type="checkbox" name="'.$data->id.'[]" value="a" requried/> '.$data->ans_a.'</label></div>';
        echo '<div class="col-md-12"><label><input type="checkbox" name="'.$data->id.'[]" value="b" requried/> '.$data->ans_b.'</label></div>';
        echo '<div class="col-md-12"><label><input type="checkbox" name="'.$data->id.'[]" value="c" requried/> '.$data->ans_c.'</label></div>';
    }else if($data->ans_a != "" && $data->ans_b != ""){
        echo '<div class="col-md-12"><label><input type="checkbox" name="'.$data->id.'[]" value="a" requried/> '.$data->ans_a.'</label></div>';
        echo '<div class="col-md-12"><label><input type="checkbox" name="'.$data->id.'[]" value="b" requried/> '.$data->ans_b.'</label></div>';
    }
}





function getUserCollectCal($user){ //user->education $user->investment $user->asset $user->self_mfs
    $array = array('name'=>true,'spatie_role_id'=>true,'mobile'=>true,'self_nid_number'=>true,'date_of_birth'=>true,'gender'=>true,
    'email'=>true,'self_nid_present_address'=>true,'self_permenant_address'=>true,'self_picture'=>true,'institute_name'=>true,'division_id'=>true,'district_id'=>true,
    'upazila_id'=>true,'union_id'=>true,'village_id'=>true,'mouza'=>true,'trade_license_number'=>true,'self_bank_asia_account'=>true,'guardian_relation'=>true,
'guardian_name'=>true,'guardian_phone'=>true,'guardian_nid_number'=>true,'education'=>true,'investment'=>true,'self_mfs'=>true);

    $total = 0;
    $per_col_value = 3.846153846153846;
    foreach($array as $item => $v){
        if($user->{$item} != ""){
            $total += $per_col_value;
        }
    }
    return number_format($total,00);
}





//Return Role Name using flag
function getRoleName($flag){
    switch($flag){
        case 4:
            return "kollany";
        case 5:
            return "shukormi";
        case 29:
            return "freelancer";
        case 30:
            return "shujog-sohojogi";
        default:
            return "not-found";
    }
}




//Return Role Name using flag
function getPanelHeaderName($flag){
    $arr = array(
        '19'=>'Consultants',
        '20'=>'Trainers',
        '21'=>'Deployers',
        '22'=>'Network Manager',
        '23'=>'Project Manager',
        '24'=>'Consultants',
        '25'=>'Trainers',
        '26'=>'Deployers',
        '27'=>'Project Manager',
        '28'=>'Network Manager',
    );

    foreach($arr as $k => $gav){
        if($k == $flag){
            return $gav;
            break;
        }

    }
}


//Return Role Name using flag
function getPanelPageTitle($flag){
    $arr = array(
        '19'=>'Admin consultant',
        '20'=>'Admin trainer',
        '21'=>'Admin deployer',
        '22'=>'Admin network manager',
        '23'=>'Admin project manager',
        '24'=>'Member consultant',
        '25'=>'Member trainer',
        '26'=>'Member deployer',
        '27'=>'Member project manager',
        '28'=>'Member network manager',
    );

    foreach($arr as $k => $gav){
        if($k == $flag){
            return $gav;
            break;
        }

    }
}





function getRoleId($key){
    switch($key){
        case "kallyani":
            return 17;
        case "sukormi":
            return 18;
        case "shujog-shohojogi":
            return 19;
        case "freelancer":
            return 20; 
        default: 
            return "not Set";           
    }
}



