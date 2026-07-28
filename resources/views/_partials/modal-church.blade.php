<div class="modal-overlay" id="overlayChurch">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('overlayChurch')">&times;</button>
        <h3>Enter Church Code</h3>
        <p>Enter your church code to apply your pastor's settings.</p>
        <input type="text" class="modal-input" id="inputedChurchCode" placeholder="Code" maxlength="9">
        <button class="modal-btn primary-btn-perfit" onclick="selectLang()">Next</button>
    </div>
</div>

@push('scripts')
<script>
    function selectLang() {
        const code = document.getElementById('inputedChurchCode').value.trim();
        if (!code) {
            alert('Please enter a church code.');
            return;
        }
        fetch('/admin/validate-church-code', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ church_code: code })
        })
        .then(r => r.json())
        .then(function(data) {
            if (data.exists) {
                fetch('/assessment/set-church-code', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ church_code: code })
                })
                .then(function() {
                    closeModal('overlayChurch');
                    openModal('overlayLang');
                    document.getElementById('inputedChurchCode').value = '';
                });
            } else {
                alert('Invalid church code.');
            }
        })
        .catch(function() { alert('Error validating church code.'); });
    }
</script>
@endpush
