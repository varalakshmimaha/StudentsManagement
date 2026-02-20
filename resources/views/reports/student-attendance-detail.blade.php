<div class="p-10">
    <div class="flex justify-between items-start mb-10">
        <div class="flex items-center">
            <div class="h-16 w-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-black text-2xl mr-6 shadow-xl shadow-indigo-100">
                {{ substr($student->name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-3xl font-black text-gray-900 leading-none mb-2">{{ $student->name }}</h2>
                <div class="flex items-center gap-3">
                    <span class="text-indigo-500 font-black uppercase text-[10px] tracking-widest bg-indigo-50 px-2 py-1 rounded-md border border-indigo-100">{{ $student->roll_number }}</span>
                    <span class="text-gray-400 font-bold text-xs">Analytics for {{ \Carbon\Carbon::parse($month)->format('F Y') }}</span>
                </div>
            </div>
        </div>
        <button onclick="closeModal()" class="h-10 w-10 bg-gray-50 rounded-full flex items-center justify-center hover:bg-gray-100 transition">
            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-4 gap-6 mb-10">
        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Academic Days</p>
            <p class="text-2xl font-black text-gray-900">{{ $workingDays }}</p>
        </div>
        <div class="bg-emerald-50 p-6 rounded-2xl border border-emerald-100">
            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-2">Days Present</p>
            <p class="text-2xl font-black text-emerald-800">{{ $presentDays }}</p>
        </div>
        <div class="bg-rose-50 p-6 rounded-2xl border border-rose-100">
            <p class="text-[10px] font-black text-rose-600 uppercase tracking-widest mb-2">Days Absent</p>
            <p class="text-2xl font-black text-rose-800">{{ $absentDays }}</p>
        </div>
        <div class="bg-indigo-900 p-6 rounded-2xl shadow-lg">
            <p class="text-[10px] font-black text-indigo-300 uppercase tracking-widest mb-2">Score %</p>
            <p class="text-2xl font-black text-white">{{ $workingDays > 0 ? round(($presentDays / $workingDays) * 100, 1) : 0 }}%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Calendar View -->
        <div class="lg:col-span-2">
            <h5 class="font-black text-gray-900 uppercase text-[10px] tracking-widest mb-6 flex items-center">
                <svg class="h-4 w-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Attendance Calendar
            </h5>
            <div class="grid grid-cols-7 gap-3">
                @php
                    $startDay = \Carbon\Carbon::parse($month)->startOfMonth()->format('w');
                @endphp
                
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $d)
                    <div class="text-center text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ $d }}</div>
                @endforeach

                @for($i = 0; $i < $startDay; $i++)
                    <div class="h-10"></div>
                @endfor

                @foreach($calendarData as $data)
                    @php
                        $colorClass = 'bg-gray-50 border-gray-100 text-gray-300 hover:border-gray-300';
                        if($data['status'] == 'present') $colorClass = 'bg-emerald-500 border-emerald-600 text-white shadow-lg shadow-emerald-100';
                        elseif($data['status'] == 'absent') $colorClass = 'bg-rose-500 border-rose-600 text-white shadow-lg shadow-rose-100';
                        elseif($data['status'] == 'sunday') $colorClass = 'bg-blue-100 border-blue-200 text-blue-500';
                        elseif($data['status'] == 'holiday') $colorClass = 'bg-amber-400 border-amber-500 text-amber-900 shadow-lg shadow-amber-100';
                    @endphp
                    <div class="h-14 flex flex-col items-center justify-center rounded-xl border-2 transition cursor-default relative group {{ $colorClass }}" title="{{ $data['title'] ?? ucfirst($data['status']) }}">
                        <span class="text-sm font-black">{{ $data['day'] }}</span>
                        @if($data['title'])
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-32 bg-gray-900 text-white text-[10px] font-bold py-1.5 px-2 rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition pointer-events-none z-10 text-center">
                                {{ $data['title'] }}
                                <div class="absolute top-full left-1/2 -translate-x-1/2 border-8 border-transparent border-t-gray-900"></div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8 flex flex-wrap gap-4">
                <div class="flex items-center gap-2"><div class="h-3 w-3 rounded-full bg-emerald-500"></div> <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Present</span></div>
                <div class="flex items-center gap-2"><div class="h-3 w-3 rounded-full bg-rose-500"></div> <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Absent</span></div>
                <div class="flex items-center gap-2"><div class="h-3 w-3 rounded-full bg-amber-400"></div> <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Holiday</span></div>
                <div class="flex items-center gap-2"><div class="h-3 w-3 rounded-full bg-blue-100 border border-blue-200"></div> <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Sunday</span></div>
                <div class="flex items-center gap-2"><div class="h-3 w-3 rounded-full bg-gray-50 border border-gray-100"></div> <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">No Session</span></div>
            </div>
        </div>

        <!-- Lists -->
        <div class="space-y-8">
            <div>
                <h5 class="font-black text-emerald-600 uppercase text-[10px] tracking-widest mb-4">Dates Present</h5>
                <div class="flex flex-wrap gap-2 max-h-40 overflow-y-auto">
                    @forelse($presentDates as $p)
                        <span class="px-2 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-md border border-emerald-100">{{ $p }}</span>
                    @empty
                        <p class="text-xs text-gray-400 italic">None recorded.</p>
                    @endforelse
                </div>
            </div>
            <div>
                <h5 class="font-black text-rose-600 uppercase text-[10px] tracking-widest mb-4">Dates Absent</h5>
                <div class="flex flex-wrap gap-2 max-h-40 overflow-y-auto">
                    @forelse($absentDates as $a)
                        <span class="px-2 py-1 bg-rose-50 text-rose-700 text-[10px] font-bold rounded-md border border-rose-100">{{ $a }}</span>
                    @empty
                        <p class="text-xs text-gray-400 italic">None recorded.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
