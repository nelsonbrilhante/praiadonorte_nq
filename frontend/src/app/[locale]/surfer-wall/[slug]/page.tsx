import { useTranslations } from 'next-intl';
import { setRequestLocale } from 'next-intl/server';
import Link from 'next/link';
import { notFound } from 'next/navigation';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Breadcrumbs } from '@/components/ui/breadcrumbs';
import {
  getSurferBySlug,
  getSurfers,
  getLocalizedField,
  type Surfer,
  type Locale,
} from '@/lib/api';
import {
  surfboardLengthToCm,
  convertSpecToMetric,
} from '@/lib/utils/measurements';

type Props = {
  params: Promise<{ locale: string; slug: string }>;
};

async function fetchSurfer(slug: string) {
  try {
    return await getSurferBySlug(slug);
  } catch (error) {
    console.error('Error fetching surfer:', error);
    return null;
  }
}

async function fetchOtherSurfers(currentSlug: string) {
  try {
    const surfers = await getSurfers({ featured: true });
    return surfers.filter((s) => s.slug !== currentSlug).slice(0, 4);
  } catch (error) {
    console.error('Error fetching other surfers:', error);
    return [];
  }
}

export default async function SurferPage({ params }: Props) {
  const { locale, slug } = await params;
  setRequestLocale(locale);

  const [surfer, otherSurfers] = await Promise.all([
    fetchSurfer(slug),
    fetchOtherSurfers(slug),
  ]);

  if (!surfer) {
    notFound();
  }

  return (
    <SurferContent
      locale={locale as Locale}
      surfer={surfer}
      otherSurfers={otherSurfers}
    />
  );
}

interface SurferContentProps {
  locale: Locale;
  surfer: Surfer;
  otherSurfers: Surfer[];
}

function SurferContent({ locale, surfer, otherSurfers }: SurferContentProps) {
  const t = useTranslations('surfers');

  const socialMediaIcons: Record<string, string> = {
    instagram: '📸',
    facebook: '👤',
    twitter: '🐦',
    youtube: '📺',
    tiktok: '🎵',
    website: '🌐',
  };

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
          <div className="grid grid-cols-1 items-center gap-8 md:grid-cols-3">
            {/* Photo placeholder */}
            <div className="aspect-square rounded-full bg-white/10 md:aspect-[3/4] md:rounded-lg" />

            {/* Info */}
            <div className="md:col-span-2">
              {surfer.featured && (
                <Badge className="mb-4 bg-white/20">Destaque</Badge>
              )}
              <h1 className="mb-2 text-4xl font-bold md:text-5xl">{surfer.name}</h1>
              {surfer.nationality && (
                <p className="mb-4 flex items-center gap-2 text-xl opacity-90">
                  🌍 {surfer.nationality}
                </p>
              )}

              {/* Social media */}
              {surfer.social_media && Object.keys(surfer.social_media).length > 0 && (
                <div className="mt-6 flex flex-wrap gap-2">
                  {Object.entries(surfer.social_media).map(([platform, url]) => (
                    <a
                      key={platform}
                      href={url as string}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="flex items-center gap-1 rounded-full bg-white/10 px-3 py-1 text-sm transition-colors hover:bg-white/20"
                    >
                      {socialMediaIcons[platform.toLowerCase()] || '🔗'}
                      <span className="capitalize">{platform}</span>
                    </a>
                  ))}
                </div>
              )}
            </div>
          </div>
        </div>
      </section>

      {/* Content */}
      <section className="py-12">
        <div className="container mx-auto px-4">
          {/* Back button */}
          <div className="mb-8">
            <Button variant="ghost" asChild>
              <Link href={`/${locale}/surfer-wall`}>
                ← {t('backToList')}
              </Link>
            </Button>
          </div>

          <div className="grid grid-cols-1 gap-8 lg:grid-cols-3">
            {/* Main content */}
            <div className="space-y-8 lg:col-span-2">
              {/* Biography */}
              {surfer.bio && (
                <Card>
                  <CardHeader>
                    <CardTitle>{t('bio')}</CardTitle>
                  </CardHeader>
                  <CardContent>
                    <div
                      className="prose prose-lg max-w-none dark:prose-invert"
                      dangerouslySetInnerHTML={{
                        __html: getLocalizedField(surfer.bio, locale),
                      }}
                    />
                  </CardContent>
                </Card>
              )}

              {/* Surfboards */}
              {surfer.surfboards && surfer.surfboards.length > 0 && (
                <Card>
                  <CardHeader>
                    <CardTitle>{t('surfboards')}</CardTitle>
                  </CardHeader>
                  <CardContent>
                    <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                      {surfer.surfboards.map((board) => (
                        <div
                          key={board.id}
                          className="flex gap-4 rounded-lg border p-4"
                        >
                          {/* Board image placeholder */}
                          <div className="h-24 w-16 flex-shrink-0 rounded bg-gradient-to-br from-ocean/20 to-ocean/5" />
                          <div>
                            <h4 className="font-semibold">{board.brand}</h4>
                            {board.model && (
                              <p className="text-sm text-muted-foreground">
                                {board.model}
                              </p>
                            )}
                            {board.length && (
                              <p className="text-sm">📏 {surfboardLengthToCm(board.length)}</p>
                            )}
                            {board.specs && Object.keys(board.specs).length > 0 && (
                              <div className="mt-2 flex flex-wrap gap-1">
                                {Object.entries(board.specs).map(([key, value]) => {
                                  // Try to get translated spec name
                                  const specTranslations: Record<string, string> = {
                                    width: t('specs.width'),
                                    thickness: t('specs.thickness'),
                                    volume: t('specs.volume'),
                                    tail: t('specs.tail'),
                                    fins: t('specs.fins'),
                                    material: t('specs.material'),
                                  };
                                  const translatedKey = specTranslations[key.toLowerCase()] || key;
                                  // Convert imperial measurements to metric
                                  const convertedValue = convertSpecToMetric(key, String(value));
                                  return (
                                    <Badge key={key} variant="outline" className="text-xs">
                                      {translatedKey}: {convertedValue}
                                    </Badge>
                                  );
                                })}
                              </div>
                            )}
                          </div>
                        </div>
                      ))}
                    </div>
                  </CardContent>
                </Card>
              )}
            </div>

            {/* Sidebar - Achievements */}
            <div className="lg:col-span-1">
              {surfer.achievements && surfer.achievements.length > 0 && (
                <Card className="sticky top-24">
                  <CardHeader>
                    <CardTitle>{t('achievements')}</CardTitle>
                  </CardHeader>
                  <CardContent>
                    <ul className="space-y-3">
                      {surfer.achievements.map((achievement, index) => (
                        <li key={index} className="flex items-start gap-2">
                          <span className="text-ocean">🏆</span>
                          <span>{getLocalizedField(achievement, locale)}</span>
                        </li>
                      ))}
                    </ul>
                  </CardContent>
                </Card>
              )}
            </div>
          </div>
        </div>
      </section>

      {/* Other Surfers */}
      {otherSurfers.length > 0 && (
        <section className="border-t bg-muted/30 py-12">
          <div className="container mx-auto px-4">
            <h2 className="mb-8 text-2xl font-bold">{t('featuredSurfers')}</h2>
            <div className="grid grid-cols-2 gap-4 md:grid-cols-4">
              {otherSurfers.map((other) => (
                <Link key={other.id} href={`/${locale}/surfer-wall/${other.slug}`}>
                  <Card className="h-full cursor-pointer overflow-hidden transition-shadow hover:shadow-lg">
                    <div className="aspect-square bg-gradient-to-br from-ocean/20 to-ocean/5" />
                    <CardContent className="p-4">
                      <h3 className="font-semibold">{other.name}</h3>
                      {other.nationality && (
                        <p className="text-sm text-muted-foreground">
                          {other.nationality}
                        </p>
                      )}
                    </CardContent>
                  </Card>
                </Link>
              ))}
            </div>
          </div>
        </section>
      )}
    </div>
  );
}
