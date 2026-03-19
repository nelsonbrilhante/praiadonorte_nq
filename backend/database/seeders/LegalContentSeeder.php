<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class LegalContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPrivacy();
        $this->seedTerms();
        $this->seedCookies();
        $this->seedDisputes();
    }

    private function seedPrivacy(): void
    {
        if (SiteSetting::get('legal_privacy')) {
            $this->command?->info('Legal privacy content already exists, skipping.');

            return;
        }

        $ptSections = __('legal.privacy.sections', [], 'pt');
        $enSections = __('legal.privacy.sections', [], 'en');

        $ptHtml = '<p>'.e(__('legal.privacy.intro', [], 'pt')).'</p>';
        $ptHtml .= $this->sectionsToHtml($ptSections);

        $enHtml = '<p>'.e(__('legal.privacy.intro', [], 'en')).'</p>';
        $enHtml .= $this->sectionsToHtml($enSections);

        SiteSetting::set('legal_privacy', json_encode([
            'pt' => [
                'title' => __('legal.privacy.title', [], 'pt'),
                'content' => $ptHtml,
            ],
            'en' => [
                'title' => __('legal.privacy.title', [], 'en'),
                'content' => $enHtml,
            ],
            'last_updated' => now()->format('Y-m-d H:i:s'),
        ]));

        $this->command?->info('Legal privacy content seeded from lang files.');
    }

    private function seedTerms(): void
    {
        if (SiteSetting::get('legal_terms')) {
            $this->command?->info('Legal terms content already exists, skipping.');

            return;
        }

        $ptSections = __('legal.terms.sections', [], 'pt');
        $enSections = __('legal.terms.sections', [], 'en');

        $ptHtml = '<p>'.e(__('legal.terms.intro', [], 'pt')).'</p>';
        $ptHtml .= $this->termsSectionsToHtml($ptSections);

        $enHtml = '<p>'.e(__('legal.terms.intro', [], 'en')).'</p>';
        $enHtml .= $this->termsSectionsToHtml($enSections);

        SiteSetting::set('legal_terms', json_encode([
            'pt' => [
                'title' => __('legal.terms.title', [], 'pt'),
                'content' => $ptHtml,
            ],
            'en' => [
                'title' => __('legal.terms.title', [], 'en'),
                'content' => $enHtml,
            ],
            'last_updated' => now()->format('Y-m-d H:i:s'),
        ]));

        $this->command?->info('Legal terms content seeded from lang files.');
    }

    private function seedCookies(): void
    {
        if (SiteSetting::get('legal_cookies')) {
            $this->command?->info('Legal cookies content already exists, skipping.');

            return;
        }

        $ptSections = __('legal.cookies.sections', [], 'pt');
        $enSections = __('legal.cookies.sections', [], 'en');

        $ptHtml = '<p>'.e(__('legal.cookies.intro', [], 'pt')).'</p>';
        $ptHtml .= $this->cookiesSectionsToHtml($ptSections);

        $enHtml = '<p>'.e(__('legal.cookies.intro', [], 'en')).'</p>';
        $enHtml .= $this->cookiesSectionsToHtml($enSections);

        SiteSetting::set('legal_cookies', json_encode([
            'pt' => [
                'title' => __('legal.cookies.title', [], 'pt'),
                'content' => $ptHtml,
            ],
            'en' => [
                'title' => __('legal.cookies.title', [], 'en'),
                'content' => $enHtml,
            ],
            'last_updated' => now()->format('Y-m-d H:i:s'),
        ]));

        $this->command?->info('Legal cookies content seeded from lang files.');
    }

    private function seedDisputes(): void
    {
        if (SiteSetting::get('legal_disputes')) {
            $this->command?->info('Legal disputes content already exists, skipping.');

            return;
        }

        $ptHtml = $this->disputesSectionsToHtml(__('legal.terms.sections', [], 'pt'));
        $enHtml = $this->disputesSectionsToHtml(__('legal.terms.sections', [], 'en'));

        SiteSetting::set('legal_disputes', json_encode([
            'pt' => [
                'title' => __('legal.terms.sections.disputes.title', [], 'pt'),
                'content' => $ptHtml,
            ],
            'en' => [
                'title' => __('legal.terms.sections.disputes.title', [], 'en'),
                'content' => $enHtml,
            ],
            'last_updated' => now()->format('Y-m-d H:i:s'),
        ]));

        $this->command?->info('Legal disputes content seeded from lang files.');
    }

    private function disputesSectionsToHtml(array $sections): string
    {
        $html = '';

        // Disputes section content (title is stored separately as the top-level title)
        if (isset($sections['disputes'])) {
            $d = $sections['disputes'];
            $html .= '<p>'.e($d['content']).'</p>';
            if (isset($d['odrLink'])) {
                $html .= '<p><a href="'.e($d['odrLink']).'" target="_blank" rel="noopener noreferrer">'.e($d['odrLink']).'</a></p>';
            }
            if (isset($d['extra'])) {
                $html .= '<p>'.e($d['extra']).'</p>';
            }
        }

        // Law section as a subsection
        if (isset($sections['law'])) {
            $l = $sections['law'];
            $html .= '<h3>'.e($l['title']).'</h3>';
            $html .= '<p>'.e($l['content']).'</p>';
        }

        return $html;
    }

    private function sectionsToHtml(array $sections): string
    {
        $html = '';

        foreach ($sections as $section) {
            $html .= '<h2>'.e($section['title']).'</h2>';
            $html .= '<p>'.e($section['content']).'</p>';

            if (isset($section['items'])) {
                $html .= '<ul>';
                foreach ($section['items'] as $item) {
                    $html .= '<li>'.e($item).'</li>';
                }
                $html .= '</ul>';
            }

            if (isset($section['extra'])) {
                $html .= '<p>'.e($section['extra']).'</p>';
            }
        }

        return $html;
    }

    private function termsSectionsToHtml(array $sections): string
    {
        $html = '';

        foreach ($sections as $key => $section) {
            // Skip disputes/law — now seeded separately via seedDisputes()
            if (in_array($key, ['disputes', 'law'])) {
                continue;
            }
            $html .= '<h2>'.e($section['title']).'</h2>';
            $html .= '<p>'.e($section['content']).'</p>';

            if (isset($section['items'])) {
                $html .= '<ul>';
                foreach ($section['items'] as $item) {
                    $html .= '<li>'.e($item).'</li>';
                }
                $html .= '</ul>';
            }

            if (isset($section['extra'])) {
                $html .= '<p>'.e($section['extra']).'</p>';
            }

            if (isset($section['odrLink'])) {
                $html .= '<p><a href="'.e($section['odrLink']).'" target="_blank" rel="noopener noreferrer">'.e($section['odrLink']).'</a></p>';
            }

            if (isset($section['formTitle'])) {
                $html .= '<div class="rounded-lg border border-gray-200 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800">';
                $html .= '<h4>'.e($section['formTitle']).'</h4>';
                $html .= '<p>'.e($section['formContent']).'</p>';
                $html .= '</div>';
            }
        }

        return $html;
    }

    private function cookiesSectionsToHtml(array $sections): string
    {
        $html = '';

        foreach ($sections as $key => $section) {
            $html .= '<h2>'.e($section['title']).'</h2>';

            if (isset($section['content'])) {
                $html .= '<p>'.e($section['content']).'</p>';
            }

            foreach (['essential', 'analytics', 'functional'] as $sub) {
                if (isset($section[$sub])) {
                    $html .= '<h3>'.e($section[$sub]['title']).'</h3>';
                    $html .= '<p>'.e($section[$sub]['content']).'</p>';
                }
            }

            if (isset($section['items'])) {
                $html .= '<ul>';
                foreach ($section['items'] as $item) {
                    $html .= '<li>'.e($item).'</li>';
                }
                $html .= '</ul>';
            }
        }

        return $html;
    }
}
