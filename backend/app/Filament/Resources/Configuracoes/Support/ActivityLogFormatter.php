<?php

namespace App\Filament\Resources\Configuracoes\Support;

use Illuminate\Support\Str;

class ActivityLogFormatter
{
    /**
     * Map of snake_case field names (from model fillables) to Portuguese labels.
     */
    private const FIELD_LABELS = [
        // Common
        'name' => 'Nome',
        'slug' => 'Slug',
        'title' => 'Título',
        'subtitle' => 'Subtítulo',
        'description' => 'Descrição',
        'content' => 'Conteúdo',
        'excerpt' => 'Resumo',
        'order' => 'Ordem',
        'active' => 'Ativo',
        'published' => 'Publicado',
        'published_at' => 'Publicado em',
        'featured' => 'Destaque',
        'entity' => 'Entidade',
        'category' => 'Categoria',
        'tags' => 'Etiquetas',
        'icon' => 'Ícone',
        'image' => 'Imagem',
        'video_url' => 'URL do vídeo',
        'location' => 'Local',
        'type' => 'Tipo',
        'email' => 'Email',
        'phone' => 'Telefone',
        'message' => 'Mensagem',
        'key' => 'Chave',
        'value' => 'Valor',
        'password' => 'Password',
        'entities' => 'Entidades',
        'role' => 'Perfil / Cargo',

        // Noticia
        'cover_image' => 'Imagem de capa',
        'author' => 'Autor',
        'show_in_hero' => 'Mostrar no Hero',
        'seo_title' => 'Título SEO',
        'seo_description' => 'Descrição SEO',

        // Evento
        'start_date' => 'Data de início',
        'end_date' => 'Data de fim',
        'gallery' => 'Galeria',
        'ticket_url' => 'URL de bilhetes',
        'schedule' => 'Programa',
        'partners' => 'Parceiros',

        // Surfer
        'aka' => 'Também conhecido como',
        'bio' => 'Biografia',
        'quote' => 'Citação',
        'photo' => 'Foto',
        'board_image' => 'Imagem da prancha',
        'social_media' => 'Redes sociais',

        // Pagina / HeroSlide
        'is_live' => 'Em direto',
        'audio_enabled' => 'Áudio ativo',
        'hero_logo' => 'Logo hero',
        'hero_use_logo' => 'Usar logo no hero',
        'hero_logo_height' => 'Altura do logo hero',
        'slider_interval' => 'Intervalo do slider',
        'slider_autoplay' => 'Autoplay do slider',
        'hero_image' => 'Imagem hero',
        'pagina_id' => 'Página',
        'fallback_image' => 'Imagem de fallback',
        'use_logo_as_title' => 'Usar logo como título',
        'logo_height' => 'Altura do logo',
        'cta_text' => 'Texto do CTA',
        'cta_url' => 'URL do CTA',

        // Document / DocumentCategory
        'document_category_id' => 'Categoria do documento',
        'file' => 'Ficheiro',

        // CorporateBody
        'section' => 'Secção',
        'cv_file' => 'Ficheiro CV',
    ];

    public static function fieldLabel(string $field): string
    {
        if (isset(self::FIELD_LABELS[$field])) {
            return self::FIELD_LABELS[$field];
        }

        // Fallback: humanise the snake_case name
        return Str::headline($field);
    }

    /**
     * Format a value for visual display. Returns safe HTML.
     */
    public static function formatValue(mixed $value): string
    {
        if ($value === null) {
            return '<span class="italic text-gray-400 dark:text-gray-500">—</span>';
        }

        if (is_bool($value)) {
            return self::booleanBadge($value);
        }

        if (is_array($value)) {
            // Detect i18n JSON: {"pt": "...", "en": "..."}
            if (self::isI18nArray($value)) {
                return self::renderI18n($value);
            }

            // Generic array → pretty JSON
            return '<pre class="text-xs whitespace-pre-wrap break-all m-0 font-mono">'
                . self::escape(json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                . '</pre>';
        }

        $string = (string) $value;

        // Empty string
        if ($string === '') {
            return '<span class="italic text-gray-400 dark:text-gray-500">(vazio)</span>';
        }

        // URL/path: show monospace
        if (self::looksLikePath($string)) {
            return '<code class="text-xs break-all font-mono">' . self::escape($string) . '</code>';
        }

        // Long text: wrap and truncate visually with line-clamp
        if (mb_strlen($string) > 120) {
            return '<div class="line-clamp-3 break-words" title="' . self::escape($string) . '">'
                . self::escape($string) . '</div>';
        }

        return '<span class="break-words">' . self::escape($string) . '</span>';
    }

    private static function booleanBadge(bool $value): string
    {
        if ($value) {
            return '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">'
                . '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>'
                . 'Sim</span>';
        }

        return '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400">'
            . '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>'
            . 'Não</span>';
    }

    private static function isI18nArray(array $value): bool
    {
        if (empty($value)) {
            return false;
        }

        $keys = array_keys($value);
        // i18n arrays have language codes as keys (pt, en, fr, etc.)
        $langCodes = ['pt', 'en', 'es', 'fr', 'de', 'it'];

        foreach ($keys as $k) {
            if (! is_string($k) || ! in_array(strtolower($k), $langCodes, true)) {
                return false;
            }
        }

        return true;
    }

    private static function renderI18n(array $value): string
    {
        $rows = [];
        foreach ($value as $lang => $text) {
            $langLabel = strtoupper((string) $lang);
            $display = $text === null || $text === ''
                ? '<span class="italic text-gray-400">(vazio)</span>'
                : self::escape((string) $text);

            $rows[] = '<div class="flex gap-2 items-start">'
                . '<span class="inline-block w-7 shrink-0 text-[10px] font-semibold px-1 py-0.5 rounded bg-gray-200 dark:bg-gray-700 text-center uppercase">'
                . self::escape($langLabel) . '</span>'
                . '<span class="flex-1 break-words">' . $display . '</span>'
                . '</div>';
        }

        return '<div class="space-y-1">' . implode('', $rows) . '</div>';
    }

    private static function looksLikePath(string $value): bool
    {
        return preg_match('#^(https?://|/|[a-z0-9_-]+/[a-z0-9._-]+\.[a-z0-9]{2,5}$)#i', $value) === 1;
    }

    private static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
