import { useTranslations } from 'next-intl';
import { setRequestLocale } from 'next-intl/server';
import Link from 'next/link';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Breadcrumbs } from '@/components/ui/breadcrumbs';
import {
  getNoticias,
  getLocalizedField,
  type Noticia,
  type Locale,
} from '@/lib/api';

type Props = {
  params: Promise<{ locale: string }>;
  searchParams: Promise<{ entity?: string; page?: string }>;
};

async function fetchNoticias(entity?: string, page?: number) {
  try {
    const response = await getNoticias({
      entity,
      page: page || 1,
      per_page: 12,
    });
    return response;
  } catch (error) {
    console.error('Error fetching noticias:', error);
    return null;
  }
}

export default async function NoticiasPage({ params, searchParams }: Props) {
  const { locale } = await params;
  const { entity, page } = await searchParams;
  setRequestLocale(locale);

  const response = await fetchNoticias(entity, page ? parseInt(page) : 1);
  const noticias = response?.data || [];

  return (
    <NoticiasContent
      locale={locale as Locale}
      noticias={noticias}
      currentEntity={entity}
      currentPage={response?.current_page || 1}
      lastPage={response?.last_page || 1}
    />
  );
}

interface NoticiasContentProps {
  locale: Locale;
  noticias: Noticia[];
  currentEntity?: string;
  currentPage: number;
  lastPage: number;
}

function NoticiasContent({ locale, noticias, currentEntity, currentPage, lastPage }: NoticiasContentProps) {
  const t = useTranslations('news');

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString(locale === 'pt' ? 'pt-PT' : 'en-US', {
      day: 'numeric',
      month: 'long',
      year: 'numeric',
    });
  };

  const entityColors: Record<string, string> = {
    'praia-norte': 'bg-ocean text-white',
    'carsurf': 'bg-performance text-white',
    'nazare-qualifica': 'bg-institutional text-white',
  };

  const entityLabels: Record<string, string> = {
    'praia-norte': t('categories.praiaDoNorte'),
    'carsurf': t('categories.carsurf'),
    'nazare-qualifica': t('categories.nazareQualifica'),
  };

  return (
    <div className="flex flex-col">
      {/* Breadcrumbs */}
      <div className="border-b bg-muted/30">
        <div className="container mx-auto px-4">
          <Breadcrumbs />
        </div>
      </div>

      {/* Header */}
      <section className="bg-gradient-to-br from-ocean via-ocean-dark to-ocean-light py-16 text-white">
        <div className="container mx-auto px-4">
          <h1 className="mb-4 text-4xl font-bold md:text-5xl">{t('title')}</h1>
          <p className="text-xl opacity-90">{t('subtitle')}</p>
        </div>
      </section>

      {/* Filters */}
      <section className="border-b bg-muted/30 py-4">
        <div className="container mx-auto px-4">
          <div className="flex flex-wrap gap-2">
            <Link
              href={`/${locale}/noticias`}
              className={`rounded-full px-4 py-2 text-sm font-medium transition-colors ${
                !currentEntity
                  ? 'bg-ocean text-white'
                  : 'bg-muted hover:bg-muted/80'
              }`}
            >
              {t('categories.all')}
            </Link>
            {['praia-norte', 'carsurf', 'nazare-qualifica'].map((entity) => (
              <Link
                key={entity}
                href={`/${locale}/noticias?entity=${entity}`}
                className={`rounded-full px-4 py-2 text-sm font-medium transition-colors ${
                  currentEntity === entity
                    ? entityColors[entity]
                    : 'bg-muted hover:bg-muted/80'
                }`}
              >
                {entityLabels[entity]}
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* News Grid */}
      <section className="py-12">
        <div className="container mx-auto px-4">
          {noticias.length > 0 ? (
            <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
              {noticias.map((noticia) => (
                <Link key={noticia.id} href={`/${locale}/noticias/${noticia.slug}`}>
                  <Card className="h-full cursor-pointer overflow-hidden transition-shadow hover:shadow-lg">
                    <div className="h-48 bg-gradient-to-br from-ocean/20 to-ocean/5" />
                    <CardHeader>
                      <div className="mb-2 flex items-center gap-2">
                        <Badge className={entityColors[noticia.entity]}>
                          {entityLabels[noticia.entity]}
                        </Badge>
                        {noticia.featured && (
                          <Badge variant="secondary">{t('featured')}</Badge>
                        )}
                      </div>
                      <CardTitle className="line-clamp-2">
                        {getLocalizedField(noticia.title, locale)}
                      </CardTitle>
                      <CardDescription className="line-clamp-2">
                        {getLocalizedField(noticia.excerpt, locale)}
                      </CardDescription>
                    </CardHeader>
                    <CardContent>
                      <p className="text-sm text-muted-foreground">
                        {formatDate(noticia.published_at)}
                      </p>
                    </CardContent>
                  </Card>
                </Link>
              ))}
            </div>
          ) : (
            <div className="py-12 text-center">
              <p className="text-lg text-muted-foreground">{t('noNews')}</p>
            </div>
          )}

          {/* Pagination */}
          {lastPage > 1 && (
            <div className="mt-8 flex justify-center gap-2">
              {Array.from({ length: lastPage }, (_, i) => i + 1).map((page) => (
                <Link
                  key={page}
                  href={`/${locale}/noticias?${currentEntity ? `entity=${currentEntity}&` : ''}page=${page}`}
                  className={`rounded px-4 py-2 text-sm font-medium transition-colors ${
                    currentPage === page
                      ? 'bg-ocean text-white'
                      : 'bg-muted hover:bg-muted/80'
                  }`}
                >
                  {page}
                </Link>
              ))}
            </div>
          )}
        </div>
      </section>
    </div>
  );
}
