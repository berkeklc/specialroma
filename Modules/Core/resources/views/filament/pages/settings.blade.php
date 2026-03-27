<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="[
                \Filament\Actions\Action::make('save')
                    ->label(__('filament-panels::pages/auth/edit-profile.form.actions.save.label'))
                    ->submit('save'),
            ]"
            :full-width="false"
        />
    </x-filament-panels::form>
</x-filament-panels::page>
