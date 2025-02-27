<div class="w-full">
    <section class="flex @container justify-center items-center">

        <div x-data x-on:code-input-complete.window="$dispatch('submitCode', [event.detail.code])" class="flex flex-col w-full max-w-sm mx-auto text-sm">
            @if($confirmed)
                <div class="flex flex-col space-y-5">
                    <h2 class="text-xl">You have enabled two factor authentication.</h2>
                    <p>When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.</p>
                    @if($showRecoveryCodes)
                        <div class="relative">
                            <p class="font-medium">Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.</p>
                            <div class="grid max-w-xl gap-1 px-4 py-4 mt-4 font-mono text-sm bg-gray-100 rounded-lg dark:bg-gray-900 dark:text-gray-100">

                                @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                                    <div>{{ $code }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div class="flex items-center space-x-5">
                        <x-button type="primary" wire:click="regenerateCodes" rounded="md" size="md">Regenerate Codes</x-button>
                        <x-button type="danger" wire:click="disable" size="md" rounded="md">Disable 2FA</x-button>
                    </div>
                </div>

            @else
                @if(!$enabled)
                    <div class="relative flex flex-col items-start justify-start space-y-5">
                        <h2 class="text-lg font-semibold">Two factor authentication disabled.</h2>
                        <p class="-translate-y-1">When you enabled 2FA, you will be prompted for a secure code during authentication. This code can be retrieved from your phone's Google Authenticator application.</p>
                        <div class="relative w-auto">
                            <x-button type="primary" data-auth="enable-button" rounded="md" size="md" wire:click="enable" wire:target="enable">Enable</x-button>
                        </div>
                    </div>
                @else
                    <div  class="relative w-full space-y-5">
                        <div class="space-y-5">
                            <h2 class="text-lg font-semibold">Finish enabling two factor authentication.</h2>
                            <p>Enable two-factor authentication to receive a secure token from your phone's Google Authenticator during login.</p>
                            <p class="font-bold">To enable two-factor authentication, scan the QR code or enter the setup key using your phone's authenticator app and provide the OTP code.</p>
                        </div>

                        <div class="relative max-w-full mx-auto overflow-hidden border rounded-lg border-zinc-200">
                            <img src="data:image/png;base64, {{ $qr }}" style="width:400px; height:auto" />
                        </div>

                        <p class="font-semibold text-center">
                            {{ __('Setup Key') }}: {{ $secret }}
                        </p>

                        <x-input-code id="auth-input-code" digits="6" eventCallback="code-input-complete" type="text" label="Code" />
                        @error('auth_code')
                        <p class="my-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="flex items-center space-x-5">
                            <x-button type="secondary" size="md" rounded="md" wire:click="cancelTwoFactor" wire:target="cancelTwoFactor">Cancel</x-button>
                            <x-button type="primary" size="md" wire:click="submitCode(document.getElementById('auth-input-code').value)" wire:target="submitCode" rounded="md">Confirm</x-button>
                        </div>

                    </div>
                @endif
            @endif
        </div>
    </section>
</div>
