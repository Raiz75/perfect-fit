<div id="phase5" class="phase-section">
    <div class="wizard-phase-header">
        <h2><i class="ti ti-certificate"></i> Calling and Service Profile</h2>
        <p>Your assessment results are ready.</p>
    </div>

    @if($phase5Error)
        <div class="alert alert-warning">
            <i class="ti ti-alert-triangle"></i> {{ $phase5Error }}
        </div>
    @endif

    @if($phase5Data && !empty($phase5Data['aiInterpretation']))
        @php
            $ai = $phase5Data['aiInterpretation'];
            $tiers = $phase5Data['tiers'] ?? [];
            $tierLabels = ['Best Fit', 'Top 2', 'Top 3'];
            $tierClasses = ['best-fit', 'top-2', 'top-3'];
        @endphp

        @foreach($tiers as $i => $tier)
            @if($i > 2) @break @endif
            <div class="result-section {{ $tierClasses[$i] ?? '' }}">
                <h3>{{ $tierLabels[$i] ?? 'Tier ' . ($i + 1) }} {{ count($tier['titles']) > 1 ? 'Ministries' : 'Ministry' }}</h3>
                <div class="ministry-result-display">
                    @foreach($tier['titles'] as $title)
                        <span class="ministry-item">{{ $title }} Ministry</span>
                    @endforeach
                </div>
                @if(!empty($ai['tierAdvice'][$i]))
                    <p>{{ $ai['tierAdvice'][$i] }}</p>
                @endif
            </div>
        @endforeach

        @if(count($tiers) > 3)
            <div class="result-section">
                <h3>Other Ministries (Ranked)</h3>
                <ul>
                    @foreach(array_slice($tiers, 3) as $tier)
                        @foreach($tier['titles'] as $title)
                            <li>{{ $title }} Ministry (Score: {{ $tier['score'] }})</li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
        @endif

        @if(!empty($ai['leastAligned']))
            <div class="result-section">
                <h3>Least Aligned Ministr{{ count($ai['leastAligned']['titles']) > 1 ? 'ies' : 'y' }}</h3>
                <div class="ministry-result-display">
                    @foreach($ai['leastAligned']['titles'] as $title)
                        <span class="ministry-item">{{ $title }} Ministry</span>
                    @endforeach
                </div>
                <p>{{ $ai['leastAligned']['advice'] }}</p>
            </div>
        @endif

        <div class="chart-container" style="width:50%;margin:30px auto;">
            <canvas id="topMinistryChart"></canvas>
        </div>

        @if(!empty($ai['spiritualRelationship']))
            <div class="result-section">
                <h3>Spiritual Relationship Between Top Ministries</h3>
                <p>{{ $ai['spiritualRelationship'] }}</p>
            </div>
        @endif

        @if(!empty($ai['growthOpportunities']))
            <div class="result-section">
                <h3>Growth Opportunities</h3>
                <p>{{ $ai['growthOpportunities'] }}</p>
            </div>
        @endif

        @if(!empty($ai['ministryPathway']))
            <div class="result-section">
                <h3>Ministry Pathway / Journey Map</h3>
                <p>{{ $ai['ministryPathway'] }}</p>
            </div>
        @endif
    @elseif($phase5Data && !$phase5Data['aiInterpretation'])
        <div class="result-section">
            <h3>Your Ministry Rankings</h3>
            <ul>
                @foreach($phase5Data['ranked'] as $ranked)
                    <li>Top {{ $ranked['rank'] }}: {{ $ranked['ministry'] }} Ministry (Score: {{ $ranked['score'] }})</li>
                @endforeach
            </ul>
        </div>
    @endif

</div>

@if($phase5Data && !empty($phase5Data['scoresByMinistryId']))
    @php
        $chartLabels = [];
        $chartData = [];
        foreach ($phase5Data['ranked'] as $r) {
            $chartLabels[] = $r['ministry'];
            $chartData[] = $r['score'];
        }
    @endphp
    <script>
        window.phase5ChartData = {
            labels: @json($chartLabels),
            data: @json($chartData),
        };
    </script>
@endif
