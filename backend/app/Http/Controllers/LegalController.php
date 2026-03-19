<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;

class LegalController extends Controller
{
    public function privacy()
    {
        $locale = app()->getLocale();
        $data = SiteSetting::getJson('legal_privacy');

        if ($data && ! empty($data[$locale]['content'])) {
            $title = $data[$locale]['title'] ?? $data['pt']['title'] ?? __('legal.privacy.title');
            $subtitle = __('legal.privacy.subtitle');
            $content = $data[$locale]['content'] ?? $data['pt']['content'] ?? '';
            $lastUpdated = $data['last_updated'] ?? null;
        } else {
            return $this->privacyFallback();
        }

        return view('pages.privacidade', compact('title', 'subtitle', 'content', 'lastUpdated'));
    }

    public function terms()
    {
        $locale = app()->getLocale();
        $data = SiteSetting::getJson('legal_terms');

        if ($data && ! empty($data[$locale]['content'])) {
            $title = $data[$locale]['title'] ?? $data['pt']['title'] ?? __('legal.terms.title');
            $subtitle = __('legal.terms.subtitle');
            $content = $data[$locale]['content'] ?? $data['pt']['content'] ?? '';
            $lastUpdated = $data['last_updated'] ?? null;
        } else {
            return $this->termsFallback();
        }

        // Inject disputes section with #litigios anchor
        $disputes = SiteSetting::getJson('legal_disputes');
        if ($disputes && ! empty($disputes[$locale]['content'])) {
            $disputesTitle = $disputes[$locale]['title'] ?? $disputes['pt']['title'] ?? '';
            $disputesContent = $disputes[$locale]['content'] ?? $disputes['pt']['content'] ?? '';
            if ($disputesTitle || $disputesContent) {
                $content .= '<h2 id="litigios">'.e($disputesTitle).'</h2>';
                $content .= $disputesContent;
            }
        } elseif (! $disputes) {
            // Fallback: build disputes from lang files
            $sections = __('legal.terms.sections');
            if (is_array($sections)) {
                $disputesHtml = '';
                foreach (['disputes', 'law'] as $key) {
                    if (isset($sections[$key])) {
                        $id = $key === 'disputes' ? ' id="litigios"' : '';
                        $disputesHtml .= '<h2'.$id.'>'.e($sections[$key]['title']).'</h2>';
                        $disputesHtml .= '<p>'.e($sections[$key]['content']).'</p>';
                        if (isset($sections[$key]['odrLink'])) {
                            $disputesHtml .= '<p><a href="'.e($sections[$key]['odrLink']).'" target="_blank" rel="noopener noreferrer">'.e($sections[$key]['odrLink']).'</a></p>';
                        }
                        if (isset($sections[$key]['extra'])) {
                            $disputesHtml .= '<p>'.e($sections[$key]['extra']).'</p>';
                        }
                    }
                }
                $content .= $disputesHtml;
            }
        }

        return view('pages.termos', compact('title', 'subtitle', 'content', 'lastUpdated'));
    }

    public function cookies()
    {
        $locale = app()->getLocale();
        $data = SiteSetting::getJson('legal_cookies');

        if ($data && ! empty($data[$locale]['content'])) {
            $title = $data[$locale]['title'] ?? $data['pt']['title'] ?? __('legal.cookies.title');
            $subtitle = __('legal.cookies.subtitle');
            $content = $data[$locale]['content'] ?? $data['pt']['content'] ?? '';
            $lastUpdated = $data['last_updated'] ?? null;
        } else {
            return $this->cookiesFallback();
        }

        return view('pages.cookies', compact('title', 'subtitle', 'content', 'lastUpdated'));
    }

    private function privacyFallback()
    {
        $title = __('legal.privacy.title');
        $subtitle = __('legal.privacy.subtitle');
        $content = $this->convertSectionsToHtml(__('legal.privacy.sections'), __('legal.privacy.intro'));
        $lastUpdated = null;

        return view('pages.privacidade', compact('title', 'subtitle', 'content', 'lastUpdated'));
    }

    private function termsFallback()
    {
        $title = __('legal.terms.title');
        $subtitle = __('legal.terms.subtitle');
        $content = $this->convertTermsSectionsToHtml(__('legal.terms.sections'), __('legal.terms.intro'));
        $lastUpdated = null;

        return view('pages.termos', compact('title', 'subtitle', 'content', 'lastUpdated'));
    }

    private function cookiesFallback()
    {
        $title = __('legal.cookies.title');
        $subtitle = __('legal.cookies.subtitle');
        $content = $this->convertCookiesSectionsToHtml(__('legal.cookies.sections'), __('legal.cookies.intro'));
        $lastUpdated = null;

        return view('pages.cookies', compact('title', 'subtitle', 'content', 'lastUpdated'));
    }

    private function convertSectionsToHtml(array $sections, string $intro = ''): string
    {
        $html = '';
        if ($intro) {
            $html .= '<p>'.e($intro).'</p>';
        }

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

    private function convertTermsSectionsToHtml(array $sections, string $intro = ''): string
    {
        $html = '';
        if ($intro) {
            $html .= '<p>'.e($intro).'</p>';
        }

        foreach ($sections as $key => $section) {
            // Skip disputes/law — injected separately from legal_disputes
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
                $html .= '<h4>'.e($section['formTitle']).'</h4>';
                $html .= '<p>'.e($section['formContent']).'</p>';
            }
        }

        return $html;
    }

    private function convertCookiesSectionsToHtml(array $sections, string $intro = ''): string
    {
        $html = '';
        if ($intro) {
            $html .= '<p>'.e($intro).'</p>';
        }

        foreach ($sections as $key => $section) {
            $html .= '<h2>'.e($section['title']).'</h2>';

            if (isset($section['content'])) {
                $html .= '<p>'.e($section['content']).'</p>';
            }

            // Handle nested sub-sections (typesOfCookies)
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
