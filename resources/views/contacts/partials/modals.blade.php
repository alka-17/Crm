<!-- Merge Modal -->
<div class="modal fade" id="mergeModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Merge Contacts</h5><button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="mergeForm">@csrf
          <input type="hidden" id="secondary_id" name="secondary_id">
          <div class="mb-3">
            <label>Select master contact (will remain)</label>
            <select id="master_id" name="master_id" class="form-control"></select>
          </div>
          <div id="mergePreviewArea"></div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmMerge">Confirm Merge</button>
      </div>
    </div>
  </div>
</div>