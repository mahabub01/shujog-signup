<div>
    <div class="mb-3 row form-row">
        <label for="inputModuleId" class="col-sm-4 col-form-label text-end">Modules<span style="color: red">*</span></label>
        <div class="col-sm-8">
            <select id="module_id_select2" name="module_id" class="form-control gray-border form-select" aria-label="Default select example" id="inputModuleId">
                <option value="">Choose</option>
                @foreach($modules as $module)
                    @if($Moduleid == $module->id)
                        <option value="{{$module->id}}" selected>{{$module->title}}</option>
                    @else
                        <option value="{{$module->id}}">{{$module->title}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>


    <div class="mb-3 row form-row">
        <label for="inputModuleId" class="col-sm-4 col-form-label text-end">Components<span style="color: red">*</span></label>
        <div class="col-sm-8">
            <select id="module_id_select1" name="submodule_id" class="form-control gray-border form-select" aria-label="Default select example" id="inputModuleId">
                <option value="">Choose</option>
                @foreach($submodules as $sub)
                    <option value="{{$sub->id}}">{{$sub->title}}</option>
                @endforeach
            </select>
        </div>


        {{-- select wire --}}
        {{-- <label class="form-label">Components</label>
        <select wire:model="submodule_id" id="module_id_select1" name="submodule_id"  class="form-select i-f-d js-example-basic-single">
            <option value="">Choose</option>
            @foreach($submodules as $sub)
               <option value="{{$sub->id}}">{{$sub->title}}</option>
            @endforeach
        </select> --}}
    </div>

    <script>
        $(document).ready(function() {
            $('#module_id_select2').select2();
            $('#module_id_select2').on('change', function (e) {
                var selectedData = $('#module_id_select2').select2("val");
            @this.set('Moduleid', selectedData);
            });
        });

        document.addEventListener("livewire:load", () => {
            Livewire.hook('message.processed', (message, component) => {
                $('#module_id_select2').select2();
                $('#module_id_select1').select2();
            });
        });
    </script>
</div>



