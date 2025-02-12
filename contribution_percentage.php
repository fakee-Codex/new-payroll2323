<!-- modal.php -->
<div class="modal fade" id="modalId" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Set Contribution Percentage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="percentageForm">
                    <div class="mb-3">
                        <label for="sss_ee_modal" class="form-label">SSS EE Rate (%)</label>
                        <input type="number" class="form-control" id="sss_ee_modal" required>
                    </div>
                    <div class="mb-3">
                        <label for="sss_er_modal" class="form-label">SSS ER Rate (%)</label>
                        <input type="number" class="form-control" id="sss_er_modal" required>
                    </div>
                    <div class="mb-3">
                        <label for="philhealth_ee_modal" class="form-label">PhilHealth EE Rate (%)</label>
                        <input type="number" class="form-control" id="philhealth_ee_modal" required>
                    </div>
                    <div class="mb-3">
                        <label for="philhealth_er_modal" class="form-label">PhilHealth ER Rate (%)</label>
                        <input type="number" class="form-control" id="philhealth_er_modal" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="savePercentageBtn">Save changes</button>
            </div>
        </div>
    </div>
</div>
