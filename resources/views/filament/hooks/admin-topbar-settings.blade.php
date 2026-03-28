@php
    use Modules\Core\App\Filament\Pages\GeneralSettingsPage;
    use Modules\Core\App\Filament\Pages\MailSettingsPage;
    use Modules\Core\App\Filament\Pages\SeoSettingsPage;
@endphp

<div class="me-2 hidden sm:block">
    <x-filament::dropdown placement="bottom-end" width="xs" teleport>
        <x-slot name="trigger">
            <x-filament::icon-button
                color="gray"
                icon="heroicon-o-cog-6-tooth"
                :label="__('Settings')"
                tooltip="{{ __('Site settings') }}"
            />
        </x-slot>

        <x-filament::dropdown.list>
            <x-filament::dropdown.list.item
                :href="GeneralSettingsPage::getUrl()"
                icon="heroicon-m-cog-6-tooth"
                tag="a"
            >
                {{ __('General') }}
            </x-filament::dropdown.list.item>

            <x-filament::dropdown.list.item
                :href="SeoSettingsPage::getUrl()"
                icon="heroicon-m-magnifying-glass-circle"
                tag="a"
            >
                {{ __('SEO & Analytics') }}
            </x-filament::dropdown.list.item>

            <x-filament::dropdown.list.item
                :href="MailSettingsPage::getUrl()"
                icon="heroicon-m-envelope"
                tag="a"
            >
                {{ __('Mail / SMTP') }}
            </x-filament::dropdown.list.item>
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>
