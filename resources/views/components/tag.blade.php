<span {{ $attributes->merge([
    'class' => 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-medium text-sm 
                transition-all duration-300 transform hover:scale-105
                bg-gradient-to-r from-purple-50 to-purple-100 
                dark:from-purple-900/50 dark:to-purple-800/50
                text-purple-700 dark:text-purple-300
                border border-purple-200/50 dark:border-purple-700/50
                shadow-sm hover:shadow
                backdrop-blur-sm'
]) }}>
    <i class="fas fa-tag text-xs text-purple-500"></i>
    {{ $slot }}
</span>
