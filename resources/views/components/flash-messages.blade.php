@if (session()->has('success') || session()->has('error'))
    <div class="fixed top-5 right-5 z-50 flex flex-col gap-3">
        
        @if (session()->has('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 x-transition.opacity
                 class="bg-emerald-50 text-emerald-600 border border-emerald-200 px-6 py-4 rounded-2xl shadow-lg flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="font-semibold text-sm">{{ session('success') }}</span>
                <button @click="show = false" class="ml-4 text-emerald-400 hover:text-emerald-600">&times;</button>
            </div>
        @endif

        @if (session()->has('info'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)" 
                 x-transition.opacity
                 class="bg-blue-50 text-blue-600 border border-blue-200 px-6 py-4 rounded-2xl shadow-lg flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-semibold text-sm">{{ session('info') }}</span>
                <button @click="show = false" class="ml-4 text-blue-400 hover:text-blue-600">&times;</button>
            </div>
        @endif
        
        @if (session()->has('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)" 
                 x-transition.opacity
                 class="bg-red-50 text-red-600 border border-red-200 px-6 py-4 rounded-2xl shadow-lg flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-semibold text-sm">{{ session('error') }}</span>
                <button @click="show = false" class="ml-4 text-red-400 hover:text-red-600">&times;</button>
            </div>
        @endif

    </div>
@endif