'use client';

import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { useLocale, useTranslations } from 'next-intl';
import { ChevronRight, Home } from 'lucide-react';
import { cn } from '@/lib/utils';

interface BreadcrumbItem {
  label: string;
  href: string;
  current?: boolean;
}

interface BreadcrumbsProps {
  items?: BreadcrumbItem[];
  className?: string;
  showHome?: boolean;
}

export function Breadcrumbs({ items, className, showHome = true }: BreadcrumbsProps) {
  const pathname = usePathname();
  const locale = useLocale();
  const t = useTranslations('breadcrumbs');

  // Auto-generate breadcrumbs from pathname if no items provided
  const breadcrumbItems = items || generateBreadcrumbs(pathname, locale, t);

  if (breadcrumbItems.length === 0) {
    return null;
  }

  return (
    <nav aria-label="Breadcrumb" className={cn('py-3', className)}>
      <ol className="flex flex-wrap items-center gap-1.5 text-sm text-muted-foreground">
        {showHome && (
          <li className="flex items-center gap-1.5">
            <Link
              href={`/${locale}`}
              className="flex items-center gap-1 hover:text-foreground transition-colors"
            >
              <Home className="h-4 w-4" />
              <span className="sr-only">{t('home')}</span>
            </Link>
            {breadcrumbItems.length > 0 && (
              <ChevronRight className="h-4 w-4 text-muted-foreground/50" />
            )}
          </li>
        )}
        {breadcrumbItems.map((item, index) => (
          <li key={item.href} className="flex items-center gap-1.5">
            {item.current ? (
              <span
                className="font-medium text-foreground"
                aria-current="page"
              >
                {item.label}
              </span>
            ) : (
              <>
                <Link
                  href={item.href}
                  className="hover:text-foreground transition-colors"
                >
                  {item.label}
                </Link>
                <ChevronRight className="h-4 w-4 text-muted-foreground/50" />
              </>
            )}
          </li>
        ))}
      </ol>
    </nav>
  );
}

// Define translations map for known segments
const segmentTranslations: Record<string, Record<string, string>> = {
  pt: {
    'sobre': 'Sobre',
    'contacto': 'Contacto',
    'noticias': 'Notícias',
    'eventos': 'Eventos',
    'surfer-wall': 'Surfer Wall',
    'carsurf': 'Carsurf',
    'nazare-qualifica': 'Nazaré Qualifica',
    'programas': 'Programas',
    'servicos': 'Serviços',
  },
  en: {
    'sobre': 'About',
    'contacto': 'Contact',
    'noticias': 'News',
    'eventos': 'Events',
    'surfer-wall': 'Surfer Wall',
    'carsurf': 'Carsurf',
    'nazare-qualifica': 'Nazaré Qualifica',
    'programas': 'Programs',
    'servicos': 'Services',
  },
};

function generateBreadcrumbs(
  pathname: string,
  locale: string,
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  _t: (key: string) => string
): BreadcrumbItem[] {
  // Remove locale prefix and split path
  const pathWithoutLocale = pathname.replace(`/${locale}`, '') || '/';

  if (pathWithoutLocale === '/') {
    return [];
  }

  const segments = pathWithoutLocale.split('/').filter(Boolean);
  const items: BreadcrumbItem[] = [];

  let currentPath = `/${locale}`;
  const translations = segmentTranslations[locale] || segmentTranslations['pt'];

  for (let i = 0; i < segments.length; i++) {
    const segment = segments[i];
    currentPath += `/${segment}`;

    // Get translation from map, fallback to formatted segment
    const label = translations[segment] || formatSegment(segment);

    items.push({
      label,
      href: currentPath,
      current: i === segments.length - 1,
    });
  }

  return items;
}

function formatSegment(segment: string): string {
  // Handle dynamic segments (slugs) - capitalize first letter of each word
  return segment
    .split('-')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ');
}

// Export a simple wrapper for pages that need custom breadcrumbs
export function BreadcrumbsContainer({
  children,
  className,
}: {
  children: React.ReactNode;
  className?: string;
}) {
  return (
    <div className={cn('container mx-auto px-4', className)}>
      {children}
    </div>
  );
}
