document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('demographicForm');
    const nextBtn = document.getElementById('nextPhaseBtn');
    const overlayLoad = document.getElementById('overlayLoad');
    const demoWizard = document.getElementById('demographicWizard');
    const steps = document.querySelectorAll('.stepCounter .step');
    const lines = document.querySelectorAll('.stepCounter .line');
    const phase1 = document.getElementById('phase1');
    const phase2 = document.getElementById('phase2');

    if (!form || !nextBtn) return;

    const fieldTitleMap = {
        name: 'personalInfottl',
        email: 'personalInfottl',
        contact: 'personalInfottl',
        gender: 'genderBoxttl',
        age: 'ageBoxttl',
        status: 'statusBoxttl',
        baptized: 'baptizedBoxttl',
        timeInFaith: 'timeInFaithBoxttl',
    };

    function blinkInput(elementId) {
        const el = document.getElementById(elementId);
        if (!el) return;
        let blink = 0;
        const blinkInterval = setInterval(function () {
            el.style.background = blink % 2 === 0
                ? 'radial-gradient(circle, red 0%, white 100%)'
                : 'radial-gradient(circle, rgb(255, 223, 255) 0%, white 100%)';
            blink++;
            if (blink > 5) clearInterval(blinkInterval);
        }, 300);
    }

    function scrollTo(elementId) {
        const el = document.getElementById(elementId);
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function showErrors(errors) {
        const errorContainer = document.getElementById('errorContainer');
        if (errorContainer) errorContainer.innerHTML = '';

        const firstField = Object.keys(errors)[0];
        if (firstField) {
            const titleId = fieldTitleMap[firstField];
            if (titleId) {
                scrollTo(titleId.replace('ttl', 'Box'));
                blinkInput(titleId);
            }
        }

        Object.keys(errors).forEach(function (field) {
            const titleId = fieldTitleMap[field];
            if (titleId) blinkInput(titleId);
        });
    }

    function showPuzzle(side) {
        const piece = document.getElementById(side);
        if (!piece) return;
        piece.classList.add('showPiece');
        piece.scrollIntoView({ behavior: 'smooth' });
        setTimeout(function () { piece.classList.add('highlightPiece'); }, 1000);
        setTimeout(function () { piece.classList.remove('highlightPiece'); }, 2000);
    }

    function advanceToPhase2() {
        showPuzzle('bottom');

        setTimeout(function () {
            if (steps.length >= 2) {
                steps[0].classList.remove('currentStep');
                steps[1].classList.add('currentStep');
            }
            if (lines.length >= 1) {
                lines[0].classList.add('currentStep');
            }

            if (phase1) phase1.style.display = 'none';
            if (phase2) phase2.style.display = 'block';

            if (nextBtn) nextBtn.style.display = 'none';
            const reviewBtn = document.getElementById('reviewBtn');
            if (reviewBtn) reviewBtn.style.display = 'block';
        }, 3000);
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function (e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData,
            })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { status: response.status, data: data };
                    });
                })
                .then(function (result) {
                    if (result.status === 200 && result.data.success) {
                        advanceToPhase2();
                    } else if (result.status === 422 && result.data.errors) {
                        showErrors(result.data.errors);
                    } else if (result.data.errors) {
                        showErrors(result.data.errors);
                    }
                })
                .catch(function () {
                    form.submit();
                });
        });
    }

    if (overlayLoad) {
        overlayLoad.addEventListener('click', function () {
            this.style.display = 'none';
        });
    }
});
