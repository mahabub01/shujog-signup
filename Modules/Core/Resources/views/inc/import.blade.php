{{ $import_route }}
<div class="modal fade" id="ImportModal"  aria-labelledby="ImportModalLabel" aria-hidden="true" style="overflow:hidden;">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ImportModalLabel">{{ $import_title }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body filter-form">
            {{ $import_body }}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-design"><i class="fas fa-filter"></i> Import</button>
        </div>
      </div>
    </div>
  </div>
  {{ Form::close() }}








