<table>
    <thead>
    <tr>
        <th></th>
        <th>Date</th>
        <th>Role</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Ongoing Project</th>
        <th>Consultant</th>
        <th>Trainer</th>
        <th>Deployers</th>
        <th>Network Manager</th>
        <th>Project Manager</th>
        <th>Mood</th>
        <th>Scheduled Time</th>
        <th>User Info Status</th>
        <th>Evaluation Status</th>
        @foreach($evalu_questions as $ques)
            <th>{{$ques->question}}</th>
        @endforeach
    </tr>
    </thead>

    <tbody>

    @foreach ($stakeholders as $k => $user)
        <tr>
            <td>{{ $k+1 }}</td>
            <td>{{ date('d-m-Y h:i A',strtotime($user->created_at)) }}</td>
            <td>
                @if (!is_null($user->spatieRole))
                    {{ $user->spatieRole->name }}
                @else
                    N/A
                @endif
            </td>
            <td>{{ $user->name }}</td>
            <td> {{ $user->mobile }}</td>
            <td>
                @if(!is_null($user->assignProject))
                <ol type="1" class="no-margin no-padding" style="list-style-type: decimal">
                    @foreach($user->assignProject as $pro)
                        <li>{{ $pro->project->name }}</li>
                    @endforeach
                </ol>
                @else
                    N/A
                @endif
            </td>
            <td>
                @if(!is_null($user->stakeholderCommnet))
                    <p>{{ $user->stakeholderCommnet->user->name }}</p>
                    <p>({!! getStatusFullForm($user->stakeholderCommnet->status) !!})</p>
                @else
                    <p>Default</p>
                    <p style="color: silver">(Pending)</p>
                @endif
            </td>
            <td>
                @if(!is_null($user->stakeholderCommnetForTrainer))
                   <p>{{ $user->stakeholderCommnetForTrainer->user->name }}</p>
                   <p>({!! getStatusFullForm($user->stakeholderCommnetForTrainer->status) !!})</p>
                @else
                    <p>Default</p>
                    <p style="color: silver">(Pending)</p>
                @endif
            </td>
            <td>
            @if(!is_null($user->stakeholderCommnetForDeployer))
                <p>{{ $user->stakeholderCommnetForDeployer->user->name }}</p>
                    <p>({!! getStatusFullForm($user->stakeholderCommnetForDeployer->status) !!})</p>
            @else
                <p>Default</p>
                <p style="color: silver">(Pending)</p>
            @endif
            </td>
            <td>
                @if(!is_null($user->stakeholderCommnetForNmanager))
                <p>{{ $user->stakeholderCommnetForNmanager->user->name }}</p>
                <p>({!! getStatusFullForm($user->stakeholderCommnetForNmanager->status) !!})</p>
                @else
                    <p>Default</p>
                    <p style="color: green">(Active)</p>
                @endif
            </td>
            <td></td>
            <td></td>
            <td></td>

            <td>
                {{ getUserCollectCal($user) }} %
            </td>

            <td>
                @if(!is_null($user->stakeholderEvaluation))
                    @if($user->stakeholderEvaluation->status == 1)
                         Passed ({{ $user->stakeholderEvaluation->mark }} mark)
                    @else
                         Failed ({{ $user->stakeholderEvaluation->mark }} mark)
                    @endif
                @else
                    N/A
                @endif
            </td>

            @foreach($evalu_questions as $ques)
                @if(!is_null($user->stakeholderEvalDetails))
                    @foreach($user->stakeholderEvalDetails as $q_ans)
                        @if($q_ans->agent_ev_qus_id == $ques->id)
                            @if($ques->question_type == '1')
                                <td>{{$ques->{'ans_'.$q_ans->answer} }}</td>
                            @elseif($ques->question_type == '2')
                                @php
                                    $ex = explode(":",$q_ans->answer);
                                    $ans = "";
                                    foreach($ex as $j){
                                        $ans .= $ques->{'ans_'.$j}.', ';
                                    }
                                @endphp
                                <td>{{ $ans }}</td>
                            @else
                                <td>{{$q_ans->answer}}</td>
                            @endif
                            @php break; @endphp
                        @endif
                    @endforeach
                @else
                    <td>N/A</td>
                @endif
            @endforeach

        </tr>
    @endforeach
    </tbody>
</table>
