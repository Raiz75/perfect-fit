document.addEventListener('DOMContentLoaded', function () {
    function showPuzzle(side) {
        var piece = document.getElementById(side);
        if (!piece) return;
        piece.classList.add('showPiece');
        setTimeout(function () { piece.classList.add('highlightPiece'); }, 1000);
        setTimeout(function () { piece.classList.remove('highlightPiece'); }, 2000);
    }

    var phase1 = document.getElementById('phase1');
    var reviewBtn = document.getElementById('reviewBtn');

    if (phase1 && phase1.style.display === 'none') {
        if (reviewBtn) reviewBtn.style.display = 'block';
        showPuzzle('bottom');
    }

    var nextBtn = document.getElementById('nextPhaseBtn');
    if (nextBtn) {
        var puzzleMap = { 1: 'bottom', 2: 'left', 3: 'right', 4: 'top' };
        var currentPhase = parseInt(document.body.getAttribute('data-current-phase') || '1', 10);

        nextBtn.addEventListener('click', function (e) {
            e.preventDefault();
            nextBtn.disabled = true;
            nextBtn.textContent = 'Please wait...';

            showPuzzle(puzzleMap[currentPhase] || 'bottom');

            var formId = nextBtn.getAttribute('form');
            var form = document.getElementById(formId);
            if (form) {
                setTimeout(function () { form.submit(); }, 3000);
            }
        });
    }
});
