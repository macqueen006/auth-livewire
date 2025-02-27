<div class="w-full">
    <x-container>
        <x-heading
            :text="($language->passwordReset->headline ?? 'No Heading')"
            :description="($language->passwordReset->subheadline ?? 'No Description')"
            :show_subheadline="($language->passwordReset->show_subheadline ?? false)"></x-heading>

        <form wire:submit="resetPassword" class="space-y-5">
            <x-auth-input :label="config('language.passwordReset.email')" type="email" id="email" name="email" data-auth="email-input" wire:model="email" autofocus="true" />
            <x-auth-input :label="config('language.passwordReset.password')" type="password" id="password" name="password" data-auth="password-input" wire:model="password" autocomplete="new-password" />
            <x-auth-input :label="config('language.passwordReset.password_confirm')" type="password" id="password_confirmation" name="password_confirmation" data-auth="password-confirm-input" wire:model="passwordConfirmation" autocomplete="new-password" />
            <x-auth-button type="primary" data-auth="submit-button" rounded="md" submit="true">{{config('language.passwordReset.button')}}</x-auth-button>
        </form>
    </x-container>
</div>
