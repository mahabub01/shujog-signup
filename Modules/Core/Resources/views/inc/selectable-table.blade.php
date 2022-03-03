<div class="modal fade" id="selectableModal"  aria-labelledby="selectableModalLabel" aria-hidden="true" style="overflow:hidden;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="filterModalLabel">Show/Hide Table Column</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body filter-form">
            <div class="row" id="selectable-form-input"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" onclick="sendColumnName()" class="btn btn-primary"><i class="fas fa-columns"></i> Save</button>
        </div>
      </div>
    </div>
  </div>


<script>
 let tableKey = 'stk-{{ auth()->user()->flag.'-'.auth()->user()->id }}';



function sendColumnName(){
    var selectable_tab_field = document.getElementsByClassName('selectable-tab-filed');
    var table_index = [-1];
    for (var i = 0; i < selectable_tab_field.length; i++) {
        if(selectable_tab_field[i].checked == true){
            table_index.push(selectable_tab_field[i].value);
        }
    }
     //stk-4-user_id
    localStorage.removeItem(tableKey);
    localStorage.setItem(tableKey, table_index);
    loadColumn();
}


function getSelectableField(ob){

    var selectable_form_container = document.getElementById('selectable-form-input');
    let table_local_store = localStorage.getItem('stk-{{ auth()->user()->flag.'-'.auth()->user()->id }}');

    for(var fi = 0; fi< ob.length;fi++){
        var ele_div = document.createElement("div");
        ele_div.setAttribute("class","col-md-4 selectable-margin-bottom");
        var ele_label = document.createElement("label");
        ele_label.setAttribute("for","selectable-"+ob[fi].name);

        var ele_check = document.createElement("input");
        ele_check.setAttribute("type", "checkbox");
        ele_check.setAttribute("class", "selectable-tab-filed");
        ele_check.setAttribute("id", "selectable-"+ob[fi].name);
        ele_check.setAttribute("value", ob[fi].index);
        if(table_local_store != null){
            if(table_local_store.includes(ob[fi].index)){
                ele_check.setAttribute("checked", "checked");
            }
        }else{
            ele_check.setAttribute("checked", "checked");
        }

        //var label_text = document.createElement("span");
        ele_label.textContent = `${ob[fi].name} `;
        ele_label.appendChild(ele_check);
        ele_div.appendChild(ele_label);
        selectable_form_container.appendChild(ele_div);
    }

    loadColumn();
}



function loadColumn(){
       let table_local_store = localStorage.getItem('stk-{{ auth()->user()->flag.'-'.auth()->user()->id }}');

        if(table_local_store != null){
            var header_element = document.getElementsByClassName('table-header-index');
            var header_body_index = document.getElementsByClassName('table-body-index');
            var rows = document.getElementById("selectable-table").rows;


            for (var i = 3; i < header_element.length; i++) {
                if(table_local_store.includes(i)){
                    header_element[i].hidden = false;
                }else{
                    header_element[i].hidden = true;
                }

            }


            for(var j = 1; j < rows.length; j++){
                header_element = rows[j].getElementsByClassName('table-body-index');
                    for (var i = 3; i < header_element.length; i++) {

                        if(table_local_store.includes(i)){
                            header_element[i].hidden = false;
                        }else{
                            header_element[i].hidden = true;
                        }
                    }
            }



        }

    }

</script>
