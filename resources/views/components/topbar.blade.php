<header class="flex justify-between items-center py-4 px-6 bg-white border-b-4 border-blue-900">
    <div class="flex items-center">
        <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <div class="relative mx-4 lg:mx-0">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" fill="none">
                    <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>

            <input class="form-input w-32 sm:w-64 rounded-md pl-10 pr-4 focus:border-blue-600" type="text" placeholder="Search...">
        </div>
    </div>

    <div class="flex items-center">
        <!-- Branch Switcher (Mockup) -->
        <div class="relative mr-4 hidden md:block">
            <button class="flex items-center text-gray-600 focus:outline-none">
                <span class="mr-2">Main Branch</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>

        <!-- Notifications Bell -->
        <div class="relative mr-4">
            <button class="relative text-gray-600 hover:text-gray-800 focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                @php
                    $notificationCount = 0;
                    // Count overdue followups
                    $notificationCount += \App\Models\LeadFollowup::whereDate('next_followup_date', '<', now())
                        ->whereHas('lead', function($q) {
                            $q->where('status', '!=', 'converted');
                        })->count();
                    // Add notification for students with dues (just 1 notification)
                    $studentsWithDue = \App\Models\Student::selectRaw('students.*, (final_fee - (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.student_id = students.id)) as due_amount')
                        ->having('due_amount', '>', 0)
                        ->count();
                    if ($studentsWithDue > 0) {
                        $notificationCount += 1;
                    }
                @endphp
                @if($notificationCount > 0)
                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">{{ $notificationCount }}</span>
                @endif
            </button>
        </div>

        <div class="relative">
            <button class="relative block h-8 w-8 rounded-full overflow-hidden shadow focus:outline-none">
                <img class="h-full w-full object-cover" src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=1e3a8a&color=fff" alt="Your avatar">
            </button>

            <!-- Dropdown (Simple implementation with AlpineJS or standard JS would be better, but purely CSS/HTML for now requires checkbox hack or JS) -->
            <!-- Adding a simple logout form hidden nearby to hook up logic if we had JS dropdown -->
        </div>
        
        <div class="ml-4">
             <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-blue-700 hover:text-blue-900 font-medium">Logout</button>
            </form>
        </div>
    </div>
</header>
