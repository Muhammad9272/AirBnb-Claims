<span {{ $attributes->merge([
    'class' => 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-medium text-sm 
                transition-all duration-300 transform hover:scale-105
                bg-gradient-to-r from-accent/10 to-accent-light/10 
                dark:from-accent/20 dark:to-accent-light/20
                text-accent dark:text-accent-light
                border border-accent/20 dark:border-accent-light/30
                shadow-sm hover:shadow-md hover:shadow-accent/25
                backdrop-blur-sm hover:bg-gradient-to-r hover:from-accent/15 hover:to-accent-light/15'
]) }}>
    <i class="fas fa-tag text-xs text-accent/70 dark:text-accent-light/70"></i>
    {{ $slot }}
</span>