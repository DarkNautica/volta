<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DocsController extends Controller
{
    private array $navigation = [
        'getting-started' => [
            'label' => 'Getting Started',
            'pages' => [
                'introduction' => 'Introduction',
                'installation' => 'Installation',
                'quick-start' => 'Quick Start',
                'configuration' => 'Configuration',
            ],
        ],
        'core-concepts' => [
            'label' => 'Core Concepts',
            'pages' => [
                'how-credits-work' => 'How Credits Work',
                'rate-limiting' => 'Rate Limiting',
                'multi-model-pricing' => 'Multi-Model Pricing',
            ],
        ],
        'sdk-reference' => [
            'label' => 'SDK Reference',
            'pages' => [
                'charge' => 'Volta::charge()',
                'has-access' => 'Volta::hasAccess()',
                'balance' => 'Volta::balance()',
                'top-up' => 'Volta::topUp()',
                'usage' => 'Volta::usage()',
                'portal-url' => 'Volta::portalUrl()',
            ],
        ],
        'features' => [
            'label' => 'Features',
            'pages' => [
                'volta-gate' => 'VoltaGate Middleware',
                'blade-directives' => 'Blade Directives',
                'embeddable-portal' => 'Embeddable Portal',
                'usage-dashboard' => 'Usage Dashboard',
            ],
        ],
        'integrations' => [
            'label' => 'Integrations',
            'pages' => [
                'stripe-setup' => 'Stripe Setup',
                'anthropic' => 'Anthropic',
                'openai' => 'OpenAI',
                'gemini' => 'Gemini',
            ],
        ],
        'deployment' => [
            'label' => 'Deployment',
            'pages' => [
                'laravel-cloud' => 'Laravel Cloud',
                'environment-variables' => 'Environment Variables',
                'going-live' => 'Going Live Checklist',
            ],
        ],
    ];

    public function show(string $section = null, string $page = null): View
    {
        $slug = $page ?? $section ?? 'introduction';

        $viewPath = "docs.pages.{$slug}";

        if (! view()->exists($viewPath)) {
            abort(404);
        }

        $flatPages = $this->getFlatPages();
        $currentIndex = array_search($slug, array_keys($flatPages));
        $keys = array_keys($flatPages);

        $prev = $currentIndex > 0
            ? ['label' => $flatPages[$keys[$currentIndex - 1]], 'href' => "/docs/{$keys[$currentIndex - 1]}"]
            : null;

        $next = $currentIndex < count($keys) - 1
            ? ['label' => $flatPages[$keys[$currentIndex + 1]], 'href' => "/docs/{$keys[$currentIndex + 1]}"]
            : null;

        $breadcrumb = $this->getBreadcrumb($slug);
        $title = $flatPages[$slug] ?? ucfirst($slug);

        return view($viewPath, [
            'title' => $title,
            'breadcrumb' => $breadcrumb,
            'prev' => $prev,
            'next' => $next,
            'navigation' => $this->navigation,
            'currentSlug' => $slug,
        ]);
    }

    private function getFlatPages(): array
    {
        $flat = [];
        foreach ($this->navigation as $section) {
            foreach ($section['pages'] as $slug => $label) {
                $flat[$slug] = $label;
            }
        }
        return $flat;
    }

    private function getBreadcrumb(string $slug): array
    {
        $crumbs = [['label' => 'Docs', 'href' => '/docs']];

        foreach ($this->navigation as $section) {
            if (isset($section['pages'][$slug])) {
                $crumbs[] = ['label' => $section['label'], 'href' => null];
                $crumbs[] = ['label' => $section['pages'][$slug], 'href' => null];
                break;
            }
        }

        return $crumbs;
    }
}
