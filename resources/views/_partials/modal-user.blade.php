<div class="modal-overlay" id="overlayUser">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('overlayUser')">&times;</button>
        <h3>Choose User Type</h3>
        <button class="modal-btn primary-btn-perfit" onclick="setUser('leader')">Leader</button>
        <button class="modal-btn primary-btn-perfit" onclick="setUser('volunteer')">Volunteer</button>
    </div>
</div>

@push('scripts')
<script>
    function openModal(id) { document.getElementById(id).classList.add('show'); }
    function closeModal(id) { document.getElementById(id).classList.remove('show'); }

    function setUser(type) {
        if (type === 'leader') {
            closeModal('overlayUser');
            window.location.href = '/admin/login';
        } else if (type === 'volunteer') {
            closeModal('overlayUser');
            openModal('overlayChurch');
        }
    }
</script>
@endpush
