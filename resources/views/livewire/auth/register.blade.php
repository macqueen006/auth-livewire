<div class="w-full">
    <x-container>

        <x-heading :text="($language->register->headline ?? 'No Heading')" :description="($language->register->subheadline ?? 'No Description')" :show_subheadline="($language->register->show_subheadline ?? false)" />
        <x-session-message />

        @if(config('settings.social_providers_location') == 'top')
            <x-social-providers :separator="$showEmailRegistration" />
        @endif

        @if($showEmailRegistration)
            <form wire:submit="register" class="space-y-5">

                @if($showNameField)
                    <x-auth-input :label="config('language.register.name')" type="text" wire:model="name" autofocus="true" required />
                @endif

                @if($showEmailField)
                    @php
                        $autofocusEmail = ($showNameField) ? false : true;
                    @endphp
                    <x-auth-input :label="config('language.register.email_address')" id="email" name="email" type="email" wire:model="email" data-auth="email-input" :autofocus="$autofocusEmail" autocomplete="email" required />
                @endif

                @if($showPasswordField)
                    <x-auth-input :label="config('language.register.password')" type="password" wire:model="password" id="password" name="password" data-auth="password-input" autocomplete="new-password" required />
                @endif

                @if($showPasswordConfirmationField)
                    <x-auth-input :label="config('language.register.password_confirmation')" type="password" wire:model="password_confirmation" id="password_confirmation" name="password_confirmation" data-auth="password-confirmation-input" autocomplete="new-password" required />
                @endif

                <x-button data-auth="submit-button" rounded="md" submit="true">{{config('language.register.button')}}</x-button>
            </form>
        @endif

        <div class="@if(config('settings.social_providers_location') != 'top' && $showEmailRegistration){{ 'mt-3' }}@endif space-x-0.5 text-sm leading-5 @if(config('settings.center_align_text')){{ 'text-center' }}@else{{ 'text-left' }}@endif" style="color:{{ config('appearance.color.text') }}">
            <span class="opacity-[47%]">{{config('language.register.already_have_an_account')}}</span>
            <x-text-link data-auth="login-link" href="{{ route('auth.login') }}">{{config('language.register.sign_in')}}</x-text-link>
        </div>

        @if(config('settings.social_providers_location') != 'top')
            <x-social-providers :separator="$showEmailRegistration" />
        @endif
    </x-container>
</div>
