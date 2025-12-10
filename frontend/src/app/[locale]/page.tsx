import { useTranslations } from 'next-intl';
import { setRequestLocale } from 'next-intl/server';
import Link from 'next/link';
import Image from 'next/image';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import {
  getLatestNoticias,
  getUpcomingEventos,
  getSurfers,
  getLocalizedField,
  type Noticia,
  type Evento,
  type Surfer,
  type Locale,
} from '@/lib/api';

type Props = {
  params: Promise<{ locale: string }>;
};

async function fetchHomeData() {
  try {
    const [noticias, eventos, surfers] = await Promise.all([
      getLatestNoticias(3),
      getUpcomingEventos(2),
      getSurfers({ featured: true }),
    ]);
    return { noticias, eventos, surfers };
  } catch (error) {
    console.error('Error fetching home data:', error);
    return { noticias: [], eventos: [], surfers: [] };
  }
}

export default async function HomePage({ params }: Props) {
  const { locale } = await params;
  setRequestLocale(locale);

  const { noticias, eventos, surfers } = await fetchHomeData();

  return <HomeContent locale={locale as Locale} noticias={noticias} eventos={eventos} surfers={surfers} />;
}

interface HomeContentProps {
  locale: Locale;
  noticias: Noticia[];
  eventos: Evento[];
  surfers: Surfer[];
}

function HomeContent({ locale, noticias, eventos, surfers }: HomeContentProps) {
  const t = useTranslations('home');
  const tEntities = useTranslations('entities');

  // Format date for events
  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return {
      day: date.getDate().toString(),
      month: date.toLocaleDateString(locale === 'pt' ? 'pt-PT' : 'en-US', { month: 'short' }),
    };
  };

  return (
    <div className="flex flex-col">
      {/* Hero Section */}
      <section className="relative flex min-h-[70vh] items-center justify-center text-white">
        <Image
          src="/pn-ai-wave-hero.png"
          alt="Giant wave at Praia do Norte, Nazaré"
          fill
          priority
          className="object-cover"
          sizes="100vw"
        />
        <div className="absolute inset-0 bg-black/40" />
        <div className="container relative z-10 mx-auto px-4 text-center">
          <h1 className="mb-4 text-5xl font-bold md:text-7xl">
            {t('hero.title')}
          </h1>
          <p className="mb-8 text-xl md:text-2xl opacity-90">
            {t('hero.subtitle')}
          </p>
          <Button asChild size="lg" className="bg-white text-ocean hover:bg-white/90">
            <Link href={`/${locale}/sobre`}>{t('hero.cta')}</Link>
          </Button>
        </div>
      </section>

      {/* News Section */}
      <section className="py-16 bg-muted/30">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-between mb-8">
            <h2 className="text-3xl font-bold">{t('news.title')}</h2>
            <Button variant="outline" asChild>
              <Link href={`/${locale}/noticias`}>{t('news.viewAll')}</Link>
            </Button>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {noticias.length > 0 ? (
              noticias.map((noticia) => (
                <Link key={noticia.id} href={`/${locale}/noticias/${noticia.slug}`}>
                  <Card className="overflow-hidden h-full hover:shadow-lg transition-shadow cursor-pointer">
                    <div className="h-48 bg-gradient-to-br from-ocean/20 to-ocean/5" />
                    <CardHeader>
                      <CardTitle className="line-clamp-2">
                        {getLocalizedField(noticia.title, locale)}
                      </CardTitle>
                      <CardDescription className="line-clamp-2">
                        {getLocalizedField(noticia.excerpt, locale)}
                      </CardDescription>
                    </CardHeader>
                  </Card>
                </Link>
              ))
            ) : (
              // Fallback placeholder
              [1, 2, 3].map((i) => (
                <Card key={i} className="overflow-hidden">
                  <div className="h-48 bg-gradient-to-br from-ocean/20 to-ocean/5" />
                  <CardHeader>
                    <CardTitle className="line-clamp-2">Título da notícia {i}</CardTitle>
                    <CardDescription>
                      Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    </CardDescription>
                  </CardHeader>
                </Card>
              ))
            )}
          </div>
        </div>
      </section>

      {/* Surfer Wall Section */}
      <section className="py-16">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-between mb-8">
            <div>
              <h2 className="text-3xl font-bold">{t('surfers.title')}</h2>
              <p className="text-muted-foreground">{t('surfers.subtitle')}</p>
            </div>
            <Button variant="outline" asChild>
              <Link href={`/${locale}/surfer-wall`}>{t('surfers.viewAll')}</Link>
            </Button>
          </div>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
            {surfers.length > 0 ? (
              surfers.slice(0, 4).map((surfer) => (
                <Link key={surfer.id} href={`/${locale}/surfer-wall/${surfer.slug}`}>
                  <Card className="overflow-hidden hover:shadow-lg transition-shadow cursor-pointer">
                    <div className="aspect-square bg-gradient-to-br from-ocean/20 to-ocean/5" />
                    <CardContent className="p-4">
                      <p className="font-semibold">{surfer.name}</p>
                      <p className="text-sm text-muted-foreground">{surfer.nationality}</p>
                    </CardContent>
                  </Card>
                </Link>
              ))
            ) : (
              // Fallback placeholder
              [1, 2, 3, 4].map((i) => (
                <Card key={i} className="overflow-hidden">
                  <div className="aspect-square bg-gradient-to-br from-ocean/20 to-ocean/5" />
                  <CardContent className="p-4">
                    <p className="font-semibold">Surfer {i}</p>
                    <p className="text-sm text-muted-foreground">Portugal</p>
                  </CardContent>
                </Card>
              ))
            )}
          </div>
        </div>
      </section>

      {/* Entities Section */}
      <section className="py-16 bg-muted/30">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {/* Praia do Norte */}
            <Card className="border-t-4 border-t-ocean">
              <CardHeader>
                <CardTitle className="text-ocean">{tEntities('praiaDoNorte')}</CardTitle>
                <CardDescription>
                  O lar das ondas gigantes mais famosas do mundo
                </CardDescription>
              </CardHeader>
              <CardContent>
                <Button variant="outline" asChild className="w-full">
                  <Link href={`/${locale}/sobre`}>Saber mais</Link>
                </Button>
              </CardContent>
            </Card>

            {/* Carsurf */}
            <Card className="border-t-4 border-t-performance">
              <CardHeader>
                <CardTitle className="text-performance">{tEntities('carsurf')}</CardTitle>
                <CardDescription>
                  Centro de alto rendimento para atletas de surf
                </CardDescription>
              </CardHeader>
              <CardContent>
                <Button variant="outline" asChild className="w-full">
                  <Link href={`/${locale}/carsurf/sobre`}>Saber mais</Link>
                </Button>
              </CardContent>
            </Card>

            {/* Nazaré Qualifica */}
            <Card className="border-t-4 border-t-institutional">
              <CardHeader>
                <CardTitle className="text-institutional">{tEntities('nazareQualifica')}</CardTitle>
                <CardDescription>
                  Empresa municipal gestora das infraestruturas
                </CardDescription>
              </CardHeader>
              <CardContent>
                <Button variant="outline" asChild className="w-full">
                  <Link href={`/${locale}/nazare-qualifica/sobre`}>Saber mais</Link>
                </Button>
              </CardContent>
            </Card>
          </div>
        </div>
      </section>

      {/* Events Section */}
      <section className="py-16">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-between mb-8">
            <h2 className="text-3xl font-bold">{t('events.title')}</h2>
            <Button variant="outline" asChild>
              <Link href={`/${locale}/eventos`}>{t('events.viewAll')}</Link>
            </Button>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {eventos.length > 0 ? (
              eventos.map((evento) => {
                const date = formatDate(evento.start_date);
                return (
                  <Link key={evento.id} href={`/${locale}/eventos/${evento.slug}`}>
                    <Card className="flex overflow-hidden hover:shadow-lg transition-shadow cursor-pointer">
                      <div className="w-32 bg-gradient-to-br from-ocean/20 to-ocean/5 flex items-center justify-center">
                        <div className="text-center">
                          <p className="text-2xl font-bold">{date.day}</p>
                          <p className="text-sm capitalize">{date.month}</p>
                        </div>
                      </div>
                      <div className="flex-1">
                        <CardHeader>
                          <CardTitle className="text-lg">
                            {getLocalizedField(evento.title, locale)}
                          </CardTitle>
                          <CardDescription>
                            {evento.location}
                          </CardDescription>
                        </CardHeader>
                      </div>
                    </Card>
                  </Link>
                );
              })
            ) : (
              // Fallback placeholder
              [1, 2].map((i) => (
                <Card key={i} className="flex overflow-hidden">
                  <div className="w-32 bg-gradient-to-br from-ocean/20 to-ocean/5 flex items-center justify-center">
                    <div className="text-center">
                      <p className="text-2xl font-bold">15</p>
                      <p className="text-sm">Jan</p>
                    </div>
                  </div>
                  <div className="flex-1">
                    <CardHeader>
                      <CardTitle className="text-lg">Evento {i}</CardTitle>
                      <CardDescription>
                        Praia do Norte, Nazaré
                      </CardDescription>
                    </CardHeader>
                  </div>
                </Card>
              ))
            )}
          </div>
        </div>
      </section>
    </div>
  );
}
