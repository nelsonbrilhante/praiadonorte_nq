import { useTranslations } from 'next-intl';
import { setRequestLocale } from 'next-intl/server';
import Link from 'next/link';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Breadcrumbs } from '@/components/ui/breadcrumbs';
import {
  getEventos,
  getLocalizedField,
  type Evento,
  type Locale,
} from '@/lib/api';

type Props = {
  params: Promise<{ locale: string }>;
  searchParams: Promise<{ entity?: string; upcoming?: string; page?: string }>;
};

async function fetchEventos(entity?: string, upcoming?: boolean, page?: number) {
  try {
    const response = await getEventos({
      entity,
      upcoming,
      page: page || 1,
      per_page: 12,
    });
    return response;
  } catch (error) {
    console.error('Error fetching eventos:', error);
    return null;
  }
}

export default async function EventosPage({ params, searchParams }: Props) {
  const { locale } = await params;
  const { entity, upcoming, page } = await searchParams;
  setRequestLocale(locale);

  const isUpcoming = upcoming !== '0';
  const response = await fetchEventos(entity, isUpcoming, page ? parseInt(page) : 1);
  const eventos = response?.data || [];

  return (
    <EventosContent
      locale={locale as Locale}
      eventos={eventos}
      currentEntity={entity}
      isUpcoming={isUpcoming}
      currentPage={response?.current_page || 1}
      lastPage={response?.last_page || 1}
    />
  );
}

interface EventosContentProps {
  locale: Locale;
  eventos: Evento[];
  currentEntity?: string;
  isUpcoming: boolean;
  currentPage: number;
  lastPage: number;
}

function EventosContent({
  locale,
  eventos,
  currentEntity,
  isUpcoming,
  currentPage,
  lastPage,
}: EventosContentProps) {
  const t = useTranslations('events');

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return {
      day: date.getDate().toString(),
      month: date.toLocaleDateString(locale === 'pt' ? 'pt-PT' : 'en-US', { month: 'short' }),
      year: date.getFullYear().toString(),
      full: date.toLocaleDateString(locale === 'pt' ? 'pt-PT' : 'en-US', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
      }),
    };
  };

  const entityColors: Record<string, string> = {
    'praia-norte': 'bg-ocean text-white',
    'carsurf': 'bg-performance text-white',
    'nazare-qualifica': 'bg-institutional text-white',
  };

  const entityLabels: Record<string, string> = {
    'praia-norte': 'Praia do Norte',
    'carsurf': 'Carsurf',
    'nazare-qualifica': 'Nazaré Qualifica',
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
          <div className="flex flex-wrap items-center gap-4">
            {/* Upcoming/Past filter */}
            <div className="flex gap-2">
              <Link
                href={`/${locale}/eventos`}
                className={`rounded-full px-4 py-2 text-sm font-medium transition-colors ${
                  isUpcoming
                    ? 'bg-ocean text-white'
                    : 'bg-muted hover:bg-muted/80'
                }`}
              >
                {t('upcoming')}
              </Link>
              <Link
                href={`/${locale}/eventos?upcoming=0`}
                className={`rounded-full px-4 py-2 text-sm font-medium transition-colors ${
                  !isUpcoming
                    ? 'bg-ocean text-white'
                    : 'bg-muted hover:bg-muted/80'
                }`}
              >
                {t('past')}
              </Link>
            </div>

            <div className="h-6 w-px bg-border" />

            {/* Entity filter */}
            <div className="flex flex-wrap gap-2">
              <Link
                href={`/${locale}/eventos${!isUpcoming ? '?upcoming=0' : ''}`}
                className={`rounded-full px-4 py-2 text-sm font-medium transition-colors ${
                  !currentEntity
                    ? 'bg-ocean text-white'
                    : 'bg-muted hover:bg-muted/80'
                }`}
              >
                {t('all')}
              </Link>
              {['praia-norte', 'carsurf', 'nazare-qualifica'].map((entity) => (
                <Link
                  key={entity}
                  href={`/${locale}/eventos?entity=${entity}${!isUpcoming ? '&upcoming=0' : ''}`}
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
        </div>
      </section>

      {/* Events List */}
      <section className="py-12">
        <div className="container mx-auto px-4">
          {eventos.length > 0 ? (
            <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
              {eventos.map((evento) => {
                const startDate = formatDate(evento.start_date);
                const endDate = evento.end_date ? formatDate(evento.end_date) : null;

                return (
                  <Link key={evento.id} href={`/${locale}/eventos/${evento.slug}`}>
                    <Card className="flex h-full cursor-pointer overflow-hidden transition-shadow hover:shadow-lg">
                      {/* Date badge */}
                      <div className="flex w-32 flex-shrink-0 flex-col items-center justify-center bg-gradient-to-br from-ocean to-ocean-dark p-4 text-white">
                        <span className="text-3xl font-bold">{startDate.day}</span>
                        <span className="text-sm uppercase">{startDate.month}</span>
                        <span className="text-xs opacity-75">{startDate.year}</span>
                      </div>

                      <div className="flex flex-1 flex-col">
                        <CardHeader>
                          <div className="mb-2 flex items-center gap-2">
                            <Badge className={entityColors[evento.entity]}>
                              {entityLabels[evento.entity]}
                            </Badge>
                            {evento.featured && (
                              <Badge variant="secondary">Destaque</Badge>
                            )}
                          </div>
                          <CardTitle className="line-clamp-2">
                            {getLocalizedField(evento.title, locale)}
                          </CardTitle>
                          {evento.location && (
                            <CardDescription className="flex items-center gap-1">
                              <span>📍</span> {evento.location}
                            </CardDescription>
                          )}
                        </CardHeader>
                        <CardContent className="mt-auto">
                          {endDate && (
                            <p className="text-sm text-muted-foreground">
                              {t('startDate')}: {startDate.full}
                              <br />
                              {t('endDate')}: {endDate.full}
                            </p>
                          )}
                          {evento.ticket_url && (
                            <Button variant="outline" size="sm" className="mt-2" asChild>
                              <span>{t('tickets')}</span>
                            </Button>
                          )}
                        </CardContent>
                      </div>
                    </Card>
                  </Link>
                );
              })}
            </div>
          ) : (
            <div className="py-12 text-center">
              <p className="text-lg text-muted-foreground">{t('noEvents')}</p>
            </div>
          )}

          {/* Pagination */}
          {lastPage > 1 && (
            <div className="mt-8 flex justify-center gap-2">
              {Array.from({ length: lastPage }, (_, i) => i + 1).map((page) => (
                <Link
                  key={page}
                  href={`/${locale}/eventos?${currentEntity ? `entity=${currentEntity}&` : ''}${!isUpcoming ? 'upcoming=0&' : ''}page=${page}`}
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
