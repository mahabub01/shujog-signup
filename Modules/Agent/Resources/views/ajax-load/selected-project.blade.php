<div class="row">
    <input type="hidden" id="user_id" name="user_id" value="{{ $user_id }}"/>
    @foreach ($projects as $item)
        @if(in_array($item->id,$user_projects))
            <div class="col-md-6">
                <label><input type="checkbox" name="projects[]" value="{{ $item->id }}" checked> {{ $item->name }}</label>
            </div>
        @else
            <div class="col-md-6">
                <label><input type="checkbox" name="projects[]" value="{{ $item->id }}"> {{ $item->name }}</label>
            </div>
        @endif
    @endforeach
</div>
