<button class="flex items-center mx-4 p-2 text-2xl text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300" x-on:click="theme = theme === 'dark' ? 'light' : 'dark'">
    <i class="ph-fill ph-moon-stars" x-show="theme === 'dark'"></i>
    <i class="ph-fill ph-sun" x-show="theme === 'light'"></i>
</button>