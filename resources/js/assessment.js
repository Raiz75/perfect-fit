document.addEventListener('DOMContentLoaded', function () {
    function showPuzzle(side) {
        var piece = document.getElementById(side);
        if (!piece) return;
        piece.classList.add('showPiece');
        setTimeout(function () { piece.classList.add('highlightPiece'); }, 1000);
        setTimeout(function () { piece.classList.remove('highlightPiece'); }, 2000);
    }

    var currentPhase = parseInt(document.body.getAttribute('data-current-phase') || '1', 10);
    var puzzleOrder = ['bottom', 'left', 'right', 'top'];

    if (currentPhase > 1) {
        for (var i = 0; i < currentPhase - 1; i++) {
            showPuzzle(puzzleOrder[i]);
        }
    }

    var nextBtn = document.getElementById('nextPhaseBtn');
    if (nextBtn) {
        var puzzleMap = { 1: 'bottom', 2: 'left', 3: 'right', 4: 'top' };

        nextBtn.addEventListener('click', function (e) {
            var formId = nextBtn.getAttribute('form');
            var form = document.getElementById(formId);
            if (!form) return;

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            e.preventDefault();
            nextBtn.disabled = true;
            nextBtn.innerHTML = '<i class="ti ti-loader" style="margin-right:6px;"></i> Please wait...';

            showPuzzle(puzzleMap[currentPhase] || 'bottom');

            setTimeout(function () {
                if (form.reportValidity()) {
                    form.submit();
                } else {
                    nextBtn.disabled = false;
                    nextBtn.innerHTML = '<i class="ti ti-arrow-right" style="margin-right:6px;"></i> Next Phase';
                }
            }, 3000);
        });
    }
});
