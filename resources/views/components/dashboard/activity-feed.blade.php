@props(['activities'])

<div class="ui-surface border border-default/80 rounded-3xl shadow-sm overflow-hidden h-full flex flex-col">
    <div class="p-5 border-b border-default/50">
        <h2 class="text-[13px] font-bold text-foreground uppercase tracking-wider">Recent Activity</h2>
    </div>
    <div class="flex-1 overflow-y-auto p-6">
        @if($activities->isEmpty())
            <div class="h-full flex flex-col items-center justify-center py-10 text-center">
                <div class="w-12 h-12 rounded-full ui-surface-muted flex items-center justify-center mx-auto mb-3">
                    <x-atoms.icon name="history" class="w-6 h-6 text-foreground-muted/60" />
                </div>
                <h3 class="text-sm font-semibold text-foreground">Belum ada aktivitas</h3>
                <p class="text-xs text-foreground-muted mt-1">Sistem belum mencatat aktivitas operasional terbaru.</p>
            </div>
        @else
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
                                            <time datetime="{{ \Carbon\Carbon::parse($activity['time'])->toIso8601String() }}">{{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
