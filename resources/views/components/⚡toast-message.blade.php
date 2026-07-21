<?php

use Livewire\Component;

new class extends Component
{
    public function render()
    {
        return view('components.⚡toast-message');
    }
};
?>

<div id="toast-alert" class="toast-alert" style="display: none;">
    <span id="toast-text"></span>
    <button id="toast-close" class="toast-close">&times;</button>
</div>

<script>
    function hideToast() {
        var el = document.getElementById('toast-alert');
        el.style.opacity = '0';
        clearTimeout(el._timer);
    }

    document.getElementById('toast-close').addEventListener('click', hideToast);

    window.addEventListener('notify', function (e) {
        var el = document.getElementById('toast-alert');
        var txt = document.getElementById('toast-text');
        txt.textContent = e.detail.text;
        el.className = 'toast-alert toast-' + (e.detail.type || 'success');
        el.style.display = 'block';
        el.style.opacity = '1';
        clearTimeout(el._timer);
        el._timer = setTimeout(hideToast, 4000);
    });
</script>

<style>
    .toast-alert {
        position: fixed;
        top: 30px;
        right: 30px;
        min-width: 320px;
        max-width: 480px;
        padding: 14px 48px 14px 20px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        font-family: 'Poppins', Arial, sans-serif;
        z-index: 999999 !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(4px);
        transition: opacity 0.4s ease-in-out;
        isolation: isolate;
        line-height: 1.5;
    }

    .toast-close {
        position: absolute;
        top: 50%;
        right: 12px;
        transform: translateY(-50%);
        background: none;
        border: none;
        font-size: 22px;
        cursor: pointer;
        padding: 0 4px;
        line-height: 1;
        opacity: 0.6;
        transition: opacity 0.2s;
        pointer-events: auto;
        color: inherit;
    }

    .toast-close:hover {
        opacity: 1;
    }

    .toast-success {
        background: #e8f5e9;
        color: #1b5e20;
        border-left: 5px solid #28a745;
    }

    .toast-error {
        background: #fce4ec;
        color: #b71c1c;
        border-left: 5px solid #dc3545;
    }

    .toast-info {
        background: #e3f2fd;
        color: #0d47a1;
        border-left: 5px solid #2196f3;
    }

    .toast-warning {
        background: #fff3e0;
        color: #e65100;
        border-left: 5px solid #ff9800;
    }
</style>
