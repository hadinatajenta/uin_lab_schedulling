@props(['activities'])

<div class="ui-surface border border-default/80 rounded-2xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-[15px] font-bold text-foreground leading-none tracking-tight">Aktivitas Terbaru</h3>
            <p class="text-xs text-foreground-muted font-medium mt-1">Jejak kegiatan operasional laboratorium terupdate.</p>
        </div>
    </div>

    <div class="flow-root">
        <ul role="list" class="-mb-8">
            @foreach($activities as $index => $activity)
                <li>
                    <div class="relative pb-8">
                        @if($index < count($activities) - 1)
                            <span class="absolute left-5 top-5 -ml-px h-full w-0.5 bg-default/50" aria-hidden="true"></span>
                        @endif
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-10 w-10 rounded-xl flex items-center justify-center ring-8 ring-surface shadow-sm {{ $activity['color'] ?? 'text-foreground-muted bg-surface-muted' }}">
                                    <x-atoms.icon :name="$activity['icon']" class="w-5 h-5" />
                                </span>
                            </div>
                            <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                <div>
                                    <p class="text-xs font-semibold text-foreground">{{ $activity['title'] }}</p>
                                    <p class="text-xs text-foreground-muted font-medium mt-1 leading-normal">{{ $activity['description'] }}</p>
                                </div>
                                <div class="text-right whitespace-nowrap text-[10px] font-bold text-foreground-muted/60 uppercase tracking-wider">
                                    <time datetime="{{ $activity['time']->toIso8601String() }}">{{ $activity['time']->diffForHumans() }}</time>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
