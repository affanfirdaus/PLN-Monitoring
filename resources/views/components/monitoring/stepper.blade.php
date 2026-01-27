@props(['steps', 'currentIndex'])

<div class="bg-white rounded-xl border border-slate-200 p-8">
    <h3 class="text-lg font-bold text-slate-800 mb-6">Progress Permohonan</h3>
    
    {{-- Mobile: horizontal scrollable, Desktop: full width --}}
    <div class="relative overflow-x-auto pb-4 md:overflow-x-visible md:pb-0">
        <!-- Progress Line (background) -->
        <div class="absolute top-5 left-0 right-0 h-1 bg-slate-200" style="margin: 0 5%;"></div>
        
        <!-- Active Progress Line -->
        <div class="absolute top-5 left-0 h-1 bg-[#2F5AA8] transition-all duration-500" 
             style="width: {{ ($currentIndex / (count($steps) - 1)) * 90 + 5 }}%; margin-left: 5%;"></div>
        
        <!-- Steps -->
        <div class="relative flex justify-between items-start min-w-max md:min-w-0">
            @foreach($steps as $index => $label)
                @php
                    $isDone = $index < $currentIndex;
                    $isActive = $index === $currentIndex;
                    $isPending = $index > $currentIndex;
                @endphp
                
                <div class="flex flex-col items-center" style="flex: 1; max-width: 120px;">
                    <!-- Circle -->
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition mb-2
                                {{ $isDone ? 'bg-green-500 text-white' : '' }}
                                {{ $isActive ? 'bg-[#2F5AA8] text-white ring-4 ring-blue-100' : '' }}
                                {{ $isPending ? 'bg-slate-200 text-slate-400' : '' }}">
                        @if($isDone)
                            <i class="fas fa-check"></i>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </div>
                    
                    <!-- Label -->
                    <div class="text-center text-xs {{ $isActive ? 'font-bold text-slate-800' : 'text-slate-500' }}" 
                         style="word-wrap: break-word; max-width: 100px;">
                        {{ $label }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
