<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-3 py-1 bg-win-purple rounded-lg text-white']) }}>
    {{ $slot }}
</button>
