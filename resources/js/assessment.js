import { Chart, registerables } from 'chart.js';
import ChartDataLabels from 'chartjs-plugin-datalabels';

Chart.register(...registerables);
Chart.register(ChartDataLabels);

document.addEventListener('DOMContentLoaded', function () {
    var phaseNames = ['', 'Personal Details', 'Skill Profiling', 'Interest & Passion', 'Behavioral', 'Results'];

    function showPuzzle(side) {
        var piece = document.getElementById(side);
        if (!piece) return;
        piece.classList.add('showPiece');
        setTimeout(function () { piece.classList.add('highlightPiece'); }, 1000);
        setTimeout(function () { piece.classList.remove('highlightPiece'); }, 2000);
    }

    function serializeForm(form) {
        var fd = new FormData(form);
        var obj = {};
        for (var entry of fd.entries()) {
            obj[entry[0]] = entry[1];
        }
        var inputs = form.querySelectorAll('input[name^="answers["]');
        var groups = {};
        for (var i = 0; i < inputs.length; i++) {
            var card = inputs[i].closest('.question-card');
            if (card && card.getAttribute('data-group')) {
                groups[inputs[i].name] = card.getAttribute('data-group');
            }
        }
        return { data: obj, groups: groups };
    }

    function consoleReport(phase) {
        var name = phaseNames[phase] || 'Unknown';
        console.groupCollapsed(
            '%c PERFIT %c Phase ' + phase + ' — ' + name + ' ',
            'background:#8c52ff;color:#fff;font-weight:bold;padding:2px 6px;border-radius:3px 0 0 3px;',
            'background:#5a35b0;color:#fff;font-weight:bold;padding:2px 6px;border-radius:0 3px 3px 0;'
        );
        console.log('Completed: ' + new Date().toLocaleString());

        var stored = sessionStorage.getItem('perfit_phase_data_' + phase);
        if (stored) {
            sessionStorage.removeItem('perfit_phase_data_' + phase);
            var parsed = JSON.parse(stored);
            var data = parsed.data || parsed;
            var groups = parsed.groups || {};
            var keys = Object.keys(data);
            if (keys.length > 0) {
                if (phase >= 2 && phase <= 4 && Object.keys(groups).length > 0) {
                    var groupMap = {};
                    for (var key of keys) {
                        var g = groups[key] || 'Ungrouped';
                        if (!groupMap[g]) groupMap[g] = {};
                        groupMap[g][key] = data[key];
                    }
                    for (var g in groupMap) {
                        console.log(g + ':');
                        var gk = Object.keys(groupMap[g]);
                        for (var i = 0; i < gk.length; i++) {
                            console.log('  ' + gk[i] + ' = ' + groupMap[g][gk[i]]);
                        }
                    }
                } else {
                    console.log('Input:');
                    for (var key of keys) {
                        console.log('  ' + key + ' = ' + data[key]);
                    }
                }
            }
        }

        if (phase === 5 && window.phase5ChartData) {
            var items = [];
            for (var k = 0; k < window.phase5ChartData.labels.length; k++) {
                items.push({ ministry: window.phase5ChartData.labels[k], score: window.phase5ChartData.data[k] });
            }
            console.log('Rankings:', items);
        }

        console.groupEnd();
    }

    var currentPhase = parseInt(document.body.getAttribute('data-current-phase') || '1', 10);
    var puzzleOrder = ['bottom', 'left', 'right', 'top'];

    var justCompleted = sessionStorage.getItem('perfit_just_completed');
    if (justCompleted) {
        sessionStorage.removeItem('perfit_just_completed');
        consoleReport(parseInt(justCompleted, 10));
    }

    if (currentPhase === 5) {
        consoleReport(5);
    }

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

            sessionStorage.setItem('perfit_just_completed', currentPhase);
            sessionStorage.setItem('perfit_phase_data_' + currentPhase, JSON.stringify(serializeForm(form)));
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

    if (window.phase5ChartData && document.getElementById('topMinistryChart')) {
        var ctx = document.getElementById('topMinistryChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: window.phase5ChartData.labels,
                datasets: [{
                    label: 'SCORE',
                    data: window.phase5ChartData.data,
                    backgroundColor: [
                        'rgba(128, 65, 128, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    borderColor: [
                        'rgb(128, 65, 128)',
                        'rgb(75, 192, 192)',
                        'rgb(255, 206, 86)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 99, 132)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 159, 64)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle' } },
                    datalabels: {
                        color: 'white',
                        font: { weight: 'bold' },
                        formatter: function (value, context) {
                            var data = context.chart.data.datasets[0].data;
                            var total = data.reduce(function (sum, val) { return sum + val; }, 0);
                            return ((value / total) * 100).toFixed(1) + '%';
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    }
});
