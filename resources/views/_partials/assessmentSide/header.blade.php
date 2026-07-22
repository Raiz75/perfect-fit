@php
    $cp = $currentPhase ?? 1;
    $steps = [
        1 => ['label' => 'Personal', 'icon' => 'ti ti-user'],
        2 => ['label' => 'Skill', 'icon' => 'ti ti-clipboard-text'],
        3 => ['label' => 'Interest', 'icon' => 'ti ti-heart'],
        4 => ['label' => 'Behavioral', 'icon' => 'ti ti-brain'],
        5 => ['label' => 'Result', 'icon' => 'ti ti-chart-bar'],
    ];
@endphp

<div class="assessment-stepper">
    @foreach($steps as $num => $step)
        @php
            $state = $cp > $num ? 'completed' : ($cp == $num ? 'active' : '');
        @endphp
        <div class="assessment-step {{ $state }}">
            <div class="assessment-step-circle">
                @if($cp > $num)
                    <i class="ti ti-check"></i>
                @else
                    <i class="{{ $step['icon'] }}"></i>
                @endif
            </div>
            <span class="assessment-step-label">{{ $step['label'] }}</span>
        </div>
        @if(!$loop->last)
            <div class="assessment-step-line {{ $cp > $num ? 'completed' : '' }}"></div>
        @endif
    @endforeach
</div>
