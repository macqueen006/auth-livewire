<x-link
    {{ $attributes->except('wire:navigate') }}
    class="underline cursor-pointer opacity-[67%] hover:opacity-[80%]">
    {{ $slot }}
</x-link>
