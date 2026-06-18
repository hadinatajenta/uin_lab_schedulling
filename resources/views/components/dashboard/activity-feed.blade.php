@props(['activities'])

<div class="bg-white border border-zinc-200/80 rounded-2xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-[15px] font-bold text-zinc-900 leading-none tracking-tight">Aktivitas Terbaru</h3>
            <p class="text-xs text-zinc-500 font-medium mt-1">Jejak kegiatan operasional laboratorium terupdate.</p>
        </div>
    </div>

    <div class="flow-root">
        <ul role="list" class="-mb-8">
            @foreach($activities as $index => $activity)
                <li>
                    <div class="relative pb-8">
                        @if($index < count($activities) - 1)
                            <span class="absolute left-5 top-5 -ml-px h-full w-0.5 bg-zinc-100" aria-hidden="true"></span>
                        @endif
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-10 w-10 rounded-xl flex items-center justify-center ring-8 ring-white shadow-sm {{ $activity['color'] ?? 'text-zinc-600 bg-zinc-100' }}">
                                    <x-atoms.icon :name="$activity['icon']" class="w-5 h-5" />
                                </span>
                            </div>
                            <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                <div>
                                    <p class="text-xs font-semibold text-zinc-900">{{ $activity['title'] }}</p>
                                    <p class="text-xs text-zinc-500 font-medium mt-1 leading-normal">{{ $activity['description'] }}</p>
                                </div>
                                <div class="text-right whitespace-nowrap text-[10px] font-bold text-zinc-400 uppercase tracking-wider">
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
