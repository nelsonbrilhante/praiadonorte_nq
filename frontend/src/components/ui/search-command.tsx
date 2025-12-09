'use client';

import * as React from 'react';
import { useRouter } from 'next/navigation';
import { useLocale, useTranslations } from 'next-intl';
import { Command } from 'cmdk';
import { Search, FileText, Calendar, Users, BookOpen, X } from 'lucide-react';
import { search, getLocalizedField, type SearchResult, type Locale } from '@/lib/api';
import { cn } from '@/lib/utils';

export function SearchCommand() {
  const [open, setOpen] = React.useState(false);
  const [query, setQuery] = React.useState('');
  const [results, setResults] = React.useState<SearchResult[]>([]);
  const [loading, setLoading] = React.useState(false);
  const router = useRouter();
  const locale = useLocale();
  const t = useTranslations('search');

  // Debounced search
  React.useEffect(() => {
    if (query.length < 2) {
      setResults([]);
      return;
    }

    const timer = setTimeout(async () => {
      setLoading(true);
      try {
        const response = await search(query);
        setResults(response.results);
      } catch (error) {
        console.error('Search error:', error);
        setResults([]);
      } finally {
        setLoading(false);
      }
    }, 300);

    return () => clearTimeout(timer);
  }, [query]);

  // Keyboard shortcuts (Cmd+K to open, ESC to close)
  React.useEffect(() => {
    const down = (e: KeyboardEvent) => {
      if (e.key === 'k' && (e.metaKey || e.ctrlKey)) {
        e.preventDefault();
        setOpen((open) => !open);
      }
      if (e.key === 'Escape' && open) {
        e.preventDefault();
        setOpen(false);
        setQuery('');
      }
    };

    document.addEventListener('keydown', down);
    return () => document.removeEventListener('keydown', down);
  }, [open]);

  const handleSelect = (result: SearchResult) => {
    setOpen(false);
    setQuery('');
    router.push(`/${locale}${result.url}`);
  };

  const getIcon = (type: string) => {
    switch (type) {
      case 'noticia':
        return <FileText className="h-4 w-4" />;
      case 'evento':
        return <Calendar className="h-4 w-4" />;
      case 'surfer':
        return <Users className="h-4 w-4" />;
      case 'pagina':
        return <BookOpen className="h-4 w-4" />;
      default:
        return <FileText className="h-4 w-4" />;
    }
  };

  const getTypeLabel = (type: string) => {
    switch (type) {
      case 'noticia':
        return t('types.news');
      case 'evento':
        return t('types.event');
      case 'surfer':
        return t('types.surfer');
      case 'pagina':
        return t('types.page');
      default:
        return type;
    }
  };

  const getEntityLabel = (entity: string | undefined) => {
    if (!entity) return '';
    const entityNames: Record<string, string> = {
      'praia-norte': 'Praia do Norte',
      'carsurf': 'Carsurf',
      'nazare-qualifica': 'Nazaré Qualifica',
    };
    return entityNames[entity] || entity;
  };

  const getTitle = (result: SearchResult) => {
    if (typeof result.title === 'string') {
      return result.title;
    }
    return getLocalizedField(result.title, locale as Locale);
  };

  return (
    <>
      {/* Search trigger button */}
      <button
        onClick={() => setOpen(true)}
        className="flex h-9 items-center gap-2 rounded-md border bg-muted/50 px-3 text-sm text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
      >
        <Search className="h-4 w-4" />
        <span className="hidden sm:inline">{t('placeholder')}</span>
        <kbd className="pointer-events-none ml-2 hidden h-5 select-none items-center gap-1 rounded border bg-background px-1.5 font-mono text-[10px] font-medium opacity-100 sm:flex">
          <span className="text-xs">⌘</span>K
        </kbd>
      </button>

      {/* Command palette modal */}
      {open && (
        <div className="fixed inset-0 z-50">
          {/* Backdrop */}
          <div
            className="fixed inset-0 bg-black/50"
            onClick={() => setOpen(false)}
          />

          {/* Command dialog */}
          <div className="fixed left-1/2 top-[20%] z-50 w-full max-w-lg -translate-x-1/2">
            <Command
              className="overflow-hidden rounded-lg border bg-background shadow-lg"
              shouldFilter={false}
            >
              {/* Search input */}
              <div className="flex items-center border-b px-3">
                <Search className="mr-2 h-4 w-4 shrink-0 opacity-50" />
                <Command.Input
                  value={query}
                  onValueChange={setQuery}
                  placeholder={t('placeholder')}
                  autoFocus
                  className="flex h-11 w-full rounded-md bg-transparent py-3 text-sm outline-none placeholder:text-muted-foreground disabled:cursor-not-allowed disabled:opacity-50"
                />
                <button
                  onClick={() => setOpen(false)}
                  className="ml-2 rounded p-1 hover:bg-muted"
                >
                  <X className="h-4 w-4" />
                </button>
              </div>

              {/* Results */}
              <Command.List className="max-h-[300px] overflow-y-auto p-2">
                {loading && (
                  <Command.Loading className="py-6 text-center text-sm text-muted-foreground">
                    {t('loading')}
                  </Command.Loading>
                )}

                {!loading && query.length >= 2 && results.length === 0 && (
                  <Command.Empty className="py-6 text-center text-sm text-muted-foreground">
                    {t('noResults')}
                  </Command.Empty>
                )}

                {!loading && query.length < 2 && (
                  <div className="py-6 text-center text-sm text-muted-foreground">
                    {t('minChars')}
                  </div>
                )}

                {results.length > 0 && (
                  <Command.Group>
                    {results.map((result) => (
                      <Command.Item
                        key={`${result.type}-${result.id}`}
                        value={`${result.type}-${result.id}`}
                        onSelect={() => handleSelect(result)}
                        className={cn(
                          'flex cursor-pointer items-center gap-3 rounded-md px-3 py-2 text-sm',
                          'hover:bg-accent hover:text-accent-foreground',
                          'data-[selected=true]:bg-accent data-[selected=true]:text-accent-foreground'
                        )}
                      >
                        <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-muted">
                          {getIcon(result.type)}
                        </span>
                        <div className="flex flex-col overflow-hidden">
                          <span className="truncate font-medium">
                            {getTitle(result)}
                          </span>
                          <span className="truncate text-xs text-muted-foreground">
                            {getTypeLabel(result.type)}
                            {result.entity && ` · ${getEntityLabel(result.entity)}`}
                          </span>
                        </div>
                      </Command.Item>
                    ))}
                  </Command.Group>
                )}
              </Command.List>

              {/* Footer hint */}
              <div className="border-t px-3 py-2">
                <p className="text-xs text-muted-foreground">
                  {t('hint')}
                </p>
              </div>
            </Command>
          </div>
        </div>
      )}
    </>
  );
}
