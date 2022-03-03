{{ $filter_route }}
<div class="modal fade" id="filterModal"  aria-labelledby="filterModalLabel" aria-hidden="true" style="overflow:hidden;">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="filterModalLabel">{{ $filter_title }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body filter-form">
            {{ $filter_body }}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
        </div>
      </div>
    </div>
  </div>
  {{ Form::close() }}
