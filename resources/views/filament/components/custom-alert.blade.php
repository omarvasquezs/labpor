@if (session()->has('custom_success'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 5000)"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="mb-4 flex items-center justify-between rounded-lg bg-green-500 p-4 text-white dark:bg-green-600"
        style="background-color: #22c55e; color: white;"
        role="alert"
    >
        <div class="flex items-center">
            <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="font-medium">{{ session('custom_success') }}</span>
        </div>
        <button @click="show = false" type="button" class="ml-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-green-500 text-white hover:bg-green-600 focus:ring-2 focus:ring-green-400 dark:bg-transparent dark:text-white dark:hover:bg-green-800" style="color: white;">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
@endif
