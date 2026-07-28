<div class="modal-overlay" id="overlayLang">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('overlayLang')">&times;</button>
        <h3>Choose Language</h3>
        <button class="modal-btn primary-btn-perfit" onclick="setLang('en')">English</button>
        <button class="modal-btn primary-btn-perfit" onclick="setLang('tl')">Tagalog</button>
        <div class="privacy-row">
            <input type="checkbox" id="privacyPolicy">
            <label for="privacyPolicy">I accept the <a href="{{ route('privacy-policy') }}">Privacy Policy</a></label>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function setLang(lang) {
        if (!document.getElementById('privacyPolicy').checked) {
            document.querySelector('.privacy-row').style.color = 'red';
            setTimeout(function() { document.querySelector('.privacy-row').style.color = '#999'; }, 2000);
            return;
        }
        closeModal('overlayLang');
        window.location.href = '/assessment';
    }
</script>
@endpush
