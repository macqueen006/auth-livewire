<div class="w-full">
    <x-container>
        <x-heading
            :text="($language->login->headline ?? 'No Heading')"
            :description="($language->login->subheadline ?? 'No Description')"
            :show_subheadline="($language->login->show_subheadline ?? false)"/>

        <x-session-message/>

        @if(config('settings.login_show_social_providers') && config('settings.social_providers_location') == 'top')
            <x-social-providers/>
        @endif

        <form wire:submit="authenticate" class="space-y-5">

            @if($showPasswordField)
                <x-input-placeholder value="{{ $email }}">
                    <button type="button" data-auth="edit-email-button" wire:click="editIdentity"
                            class="font-medium text-blue-500">{{ config('language.login.edit') }}</button>
                </x-input-placeholder>
            @else
                @if($showIdentifierInput)
                    <x-auth-input :label="config('language.login.email_address')" type="email" wire:model="email"
                                  autofocus="true" data-auth="email-input" id="email" name="email" autocomplete="email"
                                  required/>
                @endif
            @endif

            @if($showSocialProviderInfo)
                <div class="p-4 text-sm border rounded-md bg-zinc-50 border-zinc-200">
                    <span>{{ str_replace('__social_providers_list__', implode(', ', $userSocialProviders), config('language.login.social_auth_authenticated_message')) }}</span>
                    <button wire:click="editIdentity" type="button"
                            class="underline translate-x-0.5">{{ config('language.login.change_email') }}</button>
                </div>

                @if(!config('settings.login_show_social_providers'))
                    <x-social-providers
                        :socialProviders="\App\helpers\Helper::getProvidersFromArray($userSocialProviders)"
                        :separator="false"
                    />
                @endif
            @endif

            @php
                $passwordFieldClasses = $showPasswordField ? 'flex flex-col gap-6' : 'hidden';
            @endphp

            <div class="{{ $passwordFieldClasses }}">
                <x-auth-input :label="config('language.login.password')" type="password" wire:model="password"
                              data-auth="password-input" id="password" name="password" autocomplete="current-password"/>
                <x-checkbox :label="config('language.login.remember_me')" wire:model="rememberMe" id="remember-me"
                            data-auth="remember-me-input"/>
                <div class="flex items-center justify-between text-sm leading-5">
                    <x-text-link href="{{ route('auth.password.request') }}"
                                 data-auth="forgot-password-link">{{ config('language.login.forget_password') }}</x-text-link>
                </div>
            </div>

            <x-button type="primary" data-auth="submit-button" rounded="md" size="md" submit="true">
                {{ config('language.login.button') }}
            </x-button>
        </form>

        @if(config('settings.registration_enabled', true))
            <div
                class="mt-3 space-x-0.5 text-sm leading-5 @if(config('settings.center_align_text')){{ 'text-center' }}@else{{ 'text-left' }}@endif"
                style="color:{{ config('appearance.color.text') }}">
                <span class="opacity-[47%]"> {{ config('language.login.dont_have_an_account') }} </span>
                <x-text-link data-auth="register-link"
                             href="{{ route('auth.register') }}">{{ config('language.login.sign_up') }}</x-text-link>
            </div>
        @endif

        @if(config('settings.login_show_social_providers') && config('settings.social_providers_location') != 'top')
            <x-social-providers/>
        @endif

    </x-container>
</div>
