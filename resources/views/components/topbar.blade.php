<header class="flex justify-between items-center py-4 px-8 bg-white border-b border-gray-100 shadow-sm">
    <div class="flex items-center">
        <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        
        <div class="ml-4 lg:ml-0">
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">{{ now()->format('l, d M Y') }}</p>
        </div>
    </div>

    <div class="flex items-center gap-6">
        <!-- User Profile Display -->
        <div class="flex items-center gap-3 py-1 px-2">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-black text-gray-900 leading-none">{{ auth()->user()->name }}</p>
                <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-tighter mt-1">{{ auth()->user()->role->name }}</p>
            </div>
            <div class="relative">
                <img class="h-10 w-10 rounded-2xl object-cover ring-2 ring-indigo-50 ring-offset-2" src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=4f46e5&color=fff&bold=true" alt="User Avatar">
                <div class="absolute bottom-0 right-0 h-3 w-3 bg-emerald-500 border-2 border-white rounded-full"></div>
            </div>
        </div>
    </div>
</header>
