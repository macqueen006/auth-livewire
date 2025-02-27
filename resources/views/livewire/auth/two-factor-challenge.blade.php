<div>
    <x-container>
        <div x-data x-on:code-input-complete.window="console.log(event); $dispatch('submitCode', [event.detail.code])"
             class="relative w-full h-auto">
            @if(!$recovery)
                <x-heading
                    :text="($language->twoFactorChallenge->headline_auth ?? 'No Heading')"
                    :description="($language->twoFactorChallenge->subheadline_auth ?? 'No Description')"
                    :show_subheadline="($language->twoFactorChallenge->show_subheadline_auth ?? false)"></x-heading>
            @else
                <x-heading
                    :text="($language->twoFactorChallenge->headline_recovery ?? 'No Heading')"
                    :description="($language->twoFactorChallenge->subheadline_recovery ?? 'No Description')"
                    :show_subheadline="($language->twoFactorChallenge->show_subheadline_recovery ?? false)"></x-heading>
            @endif

            <div class="space-y-5">

                @if(!$recovery)
                    <div class="relative">
                        <x-input-code wire:model="auth_code" id="auth-input-code" digits="6"
                                      eventCallback="code-input-complete" type="text" label="Code"></x-input-code>
                    </div>
                    @error('auth_code')
                    <p class="my-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <x-button rounded="md" submit="true"
                              wire:click="submitCode(document.getElementById('auth-input-code').value)">Continue
                    </x-button>
                @else
                    <div class="relative">
                        <x-auth-input label="Recovery Code" type="text" wire:keydown.enter="submit_recovery_code"
                                       wire:model="recovery_code" id="auth-2fa-recovery-code" required></x-auth-input>
                    </div>
                    <x-button rounded="md" submit="true" wire:click="submit_recovery_code">Continue</x-button>
                @endif
            </div>

            <div class="mt-5 space-x-0.5 text-sm leading-5 text-left text-body">
                <span class="opacity-[47%]">or you can </span>
                <span class="font-medium underline opacity-60 cursor-pointer" wire:click="switchToRecovery" href="#_">
                        @if(!$recovery)
                        <span>login using a recovery code</span>
                    @else
                        <span>login using an authentication code</span>
                    @endif
                </span>
            </div>
        </div>
    </x-container>
</div>
