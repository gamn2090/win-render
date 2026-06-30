@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium md:text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
</label>
