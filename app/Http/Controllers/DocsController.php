<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DocsController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('docs.show', ['section' => 'getting-started', 'page' => 'introduction']);
    }

    public function show(string $section, string $page): View
    {
        $sections = config('docs.sections');
        $standalone = config('docs.standalone', []);

        $isStandalone = $section === $page && isset($standalone[$page]);
        if (! $isStandalone && ! isset($sections[$section]['pages'][$page])) {
            abort(404);
        }

        // Flat reading order for previous / next links.
        $order = [];
        foreach ($sections as $sSlug => $data) {
            foreach ($data['pages'] as $pSlug => $title) {
                $order[] = ['section' => $sSlug, 'page' => $pSlug, 'title' => $title];
            }
        }
        foreach ($standalone as $slug => $label) {
            $order[] = ['section' => $slug, 'page' => $slug, 'title' => $label];
        }

        $index = collect($order)->search(fn ($p) => $p['section'] === $section && $p['page'] === $page);
        $current = $order[$index];

        return view('docs.shell', [
            'title' => $current['title'],
            'section' => $section,
            'page' => $page,
            'contentView' => $isStandalone ? "docs.pages.$page" : "docs.pages.$section.$page",
            'prev' => $order[$index - 1] ?? null,
            'next' => $order[$index + 1] ?? null,
        ]);
    }
}
