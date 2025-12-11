import { useTranslations } from 'next-intl';
import { setRequestLocale } from 'next-intl/server';
import Link from 'next/link';
import Image from 'next/image';
import { notFound } from 'next/navigation';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Breadcrumbs } from '@/components/ui/breadcrumbs';
import {
  getEventoBySlug,
  getUpcomingEventos,
  getLocalizedField,
  type Evento,
  type Locale,
} from '@/lib/api';

type Props = {
  params: Promise<{ locale: string; slug: string }>;
};

async function fetchEvento(slug: string) {
  try {
    return await getEventoBySlug(slug);
  } catch (error) {
    console.error('Error fetching evento:', error);
    return null;
  }
}

async function fetchRelatedEventos(currentSlug: string) {
  try {
    const eventos = await getUpcomingEventos(4);
    return eventos.filter((e) => e.slug !== currentSlug).slice(0, 3);
  } catch (error) {
    console.error('Error fetching related eventos:', error);
    return [];
  }
}

export default async function EventoPage({ params }: Props) {
  const { locale, slug } = await params;
  setRequestLocale(locale);

  const [evento, relatedEventos] = await Promise.all([
    fetchEvento(slug),
    fetchRelatedEventos(slug),
  ]);

  if (!evento) {
    notFound();
  }

  return (
    <EventoContent
      locale={locale as Locale}
      evento={evento}
      relatedEventos={relatedEventos}
    />
  );
}

interface EventoContentProps {
  locale: Locale;
  evento: Evento;
  relatedEventos: Evento[];
}

function EventoContent({ locale, evento, relatedEventos }: EventoContentProps) {
  const t = useTranslations('events');

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return {
      day: date.getDate().toString(),
      month: date.toLocaleDateString(locale === 'pt' ? 'pt-PT' : 'en-US', { month: 'short' }),
      full: date.toLocaleDateString(locale === 'pt' ? 'pt-PT' : 'en-US', {
        weekday: 'long',
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

  const startDate = formatDate(evento.start_date);
  const endDate = evento.end_date ? formatDate(evento.end_date) : null;

  return (
    <div className="flex flex-col">
      {/* Breadcrumbs */}
      <div className="border-b bg-muted/30">
        <div className="container mx-auto px-4">
          <Breadcrumbs />
        </div>
      </div>

      {/* Hero */}
      <section className="relative bg-gradient-to-br from-ocean via-ocean-dark to-ocean-light py-20 text-white">
        <div className="container mx-auto px-4">
          <div className="mb-4 flex items-center gap-2">
            <Badge className={entityColors[evento.entity]}>
              {entityLabels[evento.entity]}
            </Badge>
            {evento.featured && (
              <Badge variant="secondary">Destaque</Badge>
            )}
          </div>
          <h1 className="mb-4 text-3xl font-bold md:text-5xl">
            {getLocalizedField(evento.title, locale)}
          </h1>
          <div className="flex flex-wrap items-center gap-4 text-lg">
            <span className="flex items-center gap-2">
              📅 {startDate.full}
              {endDate && ` - ${endDate.full}`}
            </span>
            {evento.location && (
              <span className="flex items-center gap-2">
                📍 {evento.location}
              </span>
            )}
          </div>
        </div>
      </section>

      {/* Content */}
      <section className="py-12">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-1 gap-8 lg:grid-cols-3">
            {/* Main content */}
            <div className="lg:col-span-2">
              {/* Back button */}
              <div className="mb-8">
                <Button variant="ghost" asChild>
                  <Link href={`/${locale}/eventos`}>
                    ← {t('backToList')}
                  </Link>
                </Button>
              </div>

              {/* Event image */}
              {evento.image && (
                <div className="relative mb-8 h-64 overflow-hidden rounded-lg md:h-96">
                  <Image
                    src={`${process.env.NEXT_PUBLIC_API_URL}/storage/${evento.image}`}
                    alt={getLocalizedField(evento.title, locale)}
                    fill
                    className="object-cover"
                    sizes="(max-width: 768px) 100vw, 800px"
                  />
                </div>
              )}

              {/* Description */}
              {evento.description && (
                <article
                  className="prose prose-lg max-w-none dark:prose-invert"
                  dangerouslySetInnerHTML={{
                    __html: getLocalizedField(evento.description, locale),
                  }}
                />
              )}
            </div>

            {/* Sidebar */}
            <div className="lg:col-span-1">
              <Card className="sticky top-24">
                <CardHeader>
                  <CardTitle>{t('moreInfo')}</CardTitle>
                </CardHeader>
                <CardContent className="space-y-4">
                  {/* Date info */}
                  <div>
                    <p className="text-sm font-medium text-muted-foreground">{t('date')}</p>
                    <p className="text-lg capitalize">{startDate.full}</p>
                    {endDate && (
                      <p className="text-sm text-muted-foreground">
                        {t('endDate')}: {endDate.full}
                      </p>
                    )}
                  </div>

                  {/* Location */}
                  {evento.location && (
                    <div>
                      <p className="text-sm font-medium text-muted-foreground">{t('location')}</p>
                      <p className="text-lg">{evento.location}</p>
                    </div>
                  )}

                  {/* Tickets button */}
                  {evento.ticket_url && (
                    <Button className="w-full" asChild>
                      <a href={evento.ticket_url} target="_blank" rel="noopener noreferrer">
                        {t('tickets')}
                      </a>
                    </Button>
                  )}
                </CardContent>
              </Card>
            </div>
          </div>
        </div>
      </section>

      {/* Related Events */}
      {relatedEventos.length > 0 && (
        <section className="border-t bg-muted/30 py-12">
          <div className="container mx-auto px-4">
            <h2 className="mb-8 text-2xl font-bold">{t('upcoming')}</h2>
            <div className="grid grid-cols-1 gap-6 md:grid-cols-3">
              {relatedEventos.map((related) => {
                const date = formatDate(related.start_date);
                return (
                  <Link key={related.id} href={`/${locale}/eventos/${related.slug}`}>
                    <Card className="flex h-full cursor-pointer overflow-hidden transition-shadow hover:shadow-lg">
                      <div className="flex w-20 flex-shrink-0 flex-col items-center justify-center bg-gradient-to-br from-ocean to-ocean-dark p-2 text-white">
                        <span className="text-xl font-bold">{date.day}</span>
                        <span className="text-xs uppercase">{date.month}</span>
                      </div>
                      <CardHeader className="flex-1">
                        <CardTitle className="line-clamp-2 text-base">
                          {getLocalizedField(related.title, locale)}
                        </CardTitle>
                      </CardHeader>
                    </Card>
                  </Link>
                );
              })}
            </div>
          </div>
        </section>
      )}
    </div>
  );
}
