<?php

declare(strict_types=1);

namespace Modules\Core\App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Core\App\Enums\PageStatus;
use Modules\Core\App\Models\Page;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Layout('layouts.app')]
final class PublicPage extends Component
{
    public string $slug = '';

    public function mount(string $slug = ''): void
    {
        $this->slug = $slug;
    }

    #[Computed]
    public function page(): Page
    {
        if ($this->slug === '') {
            $page = Page::query()
                ->where('is_home', true)
                ->where('status', PageStatus::Published->value)
                ->first();
        } else {
            $page = Page::query()
                ->where('slug', $this->slug)
                ->where('status', PageStatus::Published->value)
                ->first();
        }

        if (! $page) {
            abort(404, 'Page not found.');
        }

        return $page;
    }

    public function render(): \Illuminate\View\View
    {
        $page = $this->page;

        // Pass SEO data to the layout via shared view data
        view()->share('seoTitle', $page->getTranslation('meta_title', app()->getLocale()) ?: $page->getTranslation('title', app()->getLocale()));
        view()->share('seoDescription', $page->getTranslation('meta_description', app()->getLocale()));

        return view('core::livewire.public-page', compact('page'));
    }
}
