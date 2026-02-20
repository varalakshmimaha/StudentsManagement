<!-- Sidebar Overlay for Mobile -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black opacity-50 transition-opacity lg:hidden"></div>

<!-- Sidebar -->
<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-950 border-r border-white/5 transition duration-300 transform lg:translate-x-0 lg:static lg:inset-0 flex flex-col h-full overflow-y-auto">
    <div class="flex items-center justify-center h-16 border-b border-white/5 bg-black">
        <span class="text-xl font-black text-white uppercase tracking-widest">Student Admin</span>
    </div>
    
    <div class="flex flex-col flex-1 overflow-y-auto custom-scrollbar">
        <nav class="flex-1 px-4 py-6 space-y-1">
            
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-white text-black shadow-lg shadow-white/5' : 'text-zinc-300 hover:bg-white/10 hover:text-white' }}">
                <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Dashboard
            </a>

            <div class="pt-6 pb-2 px-4">
                <p class="text-[10px] font-black text-white uppercase tracking-widest">General Management</p>
            </div>

            @if(auth()->user()->role->name === 'Super Admin' || auth()->user()->hasPermissionTo('view branches'))
                <a href="{{ route('branches.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition {{ request()->routeIs('branches.*') ? 'bg-white text-black shadow-lg shadow-white/5' : 'text-zinc-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Branches
                </a>
            @endif

            @if(auth()->user()->hasPermissionTo('view courses'))
            <a href="{{ route('courses.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition {{ request()->routeIs('courses.*') ? 'bg-white text-black shadow-lg shadow-white/5' : 'text-zinc-300 hover:bg-white/10 hover:text-white' }}">
                <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Courses
            </a>
            @endif

            @if(auth()->user()->hasPermissionTo('view batches'))
            <a href="{{ route('batches.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition {{ request()->routeIs('batches.*') ? 'bg-white text-black shadow-lg shadow-white/5' : 'text-zinc-300 hover:bg-white/10 hover:text-white' }}">
                <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Batches
            </a>
            @endif

            @if(auth()->user()->hasPermissionTo('view students'))
            <a href="{{ route('students.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition {{ request()->routeIs('students.*') ? 'bg-white text-black shadow-lg shadow-white/5' : 'text-zinc-300 hover:bg-white/10 hover:text-white' }}">
                <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                </svg>
                Students
            </a>
            @endif

            <div class="pt-6 pb-2 px-4">
                <p class="text-[10px] font-black text-white uppercase tracking-widest">CRM & Leads</p>
            </div>

            @if(auth()->user()->hasPermissionTo('view leads'))
            <a href="{{ route('leads.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition {{ (request()->routeIs('leads.index') || request()->routeIs('leads.show') || request()->routeIs('leads.edit')) ? 'bg-white text-black shadow-lg shadow-white/5' : 'text-zinc-300 hover:bg-white/10 hover:text-white' }}">
                <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Leads Master
            </a>
            @endif

            @if(auth()->user()->role->name === 'Super Admin' || auth()->user()->hasPermissionTo('view lead statuses'))
            <a href="{{ route('lead_statuses.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition {{ request()->routeIs('lead_statuses.*') ? 'bg-white text-black shadow-lg shadow-white/5' : 'text-zinc-300 hover:bg-white/10 hover:text-white' }}">
                <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                Lead Statuses
            </a>
            @endif

            @if(auth()->user()->hasPermissionTo('view followups'))
            <a href="{{ route('leads.followups-board') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition {{ request()->routeIs('leads.followups-board') ? 'bg-white text-black shadow-lg shadow-white/5' : 'text-zinc-300 hover:bg-white/10 hover:text-white' }}">
                <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Follow-up Board
            </a>
            @endif

            <div class="pt-6 pb-2 px-4">
                <p class="text-[10px] font-black text-white uppercase tracking-widest">Financials & Attendance</p>
            </div>

            @if(auth()->user()->hasPermissionTo('view fees'))
            <a href="{{ route('payments.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition {{ request()->routeIs('payments.*') ? 'bg-white text-black shadow-lg shadow-white/5' : 'text-zinc-300 hover:bg-white/10 hover:text-white' }}">
                <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Fee Records
            </a>
            @endif

            @if(auth()->user()->hasPermissionTo('view attendance'))
            <a href="{{ route('attendances.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition {{ request()->routeIs('attendances.*') ? 'bg-white text-black shadow-lg shadow-white/5' : 'text-zinc-300 hover:bg-white/10 hover:text-white' }}">
                <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                Mark Attendance
            </a>
            @endif
            
            @if(auth()->user()->hasPermissionTo('view reports'))
            <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition {{ request()->routeIs('reports.*') ? 'bg-white text-black shadow-lg shadow-white/5' : 'text-zinc-300 hover:bg-white/10 hover:text-white' }}">
                <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2 2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Reports Hub
            </a>
            @endif

            <div class="pt-6 pb-2 px-4">
                <p class="text-[10px] font-black text-white uppercase tracking-widest">System & Users</p>
            </div>

            @if(auth()->user()->role->name === 'Super Admin')
                <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition {{ request()->routeIs('users.*') ? 'bg-white text-black shadow-lg shadow-white/5' : 'text-zinc-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Staff Accounts
                </a>
                <a href="{{ route('roles.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition {{ request()->routeIs('roles.*') ? 'bg-white text-black shadow-lg shadow-white/5' : 'text-zinc-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-9.618 3.04A11.952 11.952 0 0012 21.23a11.952 11.952 0 009.618-15.246z" />
                    </svg>
                    Access Control
                </a>
                <a href="{{ route('holidays.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition {{ request()->routeIs('holidays.*') ? 'bg-white text-black shadow-lg shadow-white/5' : 'text-zinc-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Holiday Config
                </a>
            @endif
        </nav>
        
        <div class="p-4 border-t border-white/5">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center px-4 py-3 text-sm font-bold rounded-xl text-zinc-300 hover:bg-red-900/20 hover:text-red-400 transition">
                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Sign Out
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.05); border-radius: 20px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.1); }
</style>
