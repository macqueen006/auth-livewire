<div class="w-full">
    <x-container>
        <x-heading
            :text="($language->passwordConfirm->headline ?? 'No Heading')"
            :description="($language->passwordConfirm->subheadline ?? 'No Description')"
            :show_subheadline="($language->passwordConfirm->show_subheadline ?? false)"></x-heading>
        <form wire:submit="confirm" class="space-y-5">
            <x-auth-input :label="config('language.passwordConfirm.password')" type="password" id="password" name="password" data-auth="password-input" autofocus="true" wire:model="password" autocomplete="current-password"></x-auth-input>
            <x-button type="primary" rounded="md" data-auth="submit-button" submit="true">{{config('language.passwordConfirm.button')}}</x-button>
        </form>
    </x-container>
</div>
