@props([
'socialProviders' => \App\helpers\Helper::activeProviders(),
'separator' => true,
'separator_text' => 'or'
])

@if(count($socialProviders))
    @if($separator)
        <x-separator class="my-6">{{ $separator_text }}</x-separator>
    @endif
    <div class="relative space-y-2 w-full mt-3">
        @foreach($socialProviders as $slug => $provider)
            <x-social-button :$slug :$provider />
        @endforeach
    </div>
@endif
