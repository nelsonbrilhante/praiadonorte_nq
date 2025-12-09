import { useTranslations } from 'next-intl';
import { setRequestLocale } from 'next-intl/server';
import Link from 'next/link';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Breadcrumbs } from '@/components/ui/breadcrumbs';
import {
  getSurfers,
  getLocalizedField,
  type Surfer,
  type Locale,
} from '@/lib/api';

type Props = {
  params: Promise<{ locale: string }>;
};

async function fetchSurfers() {
  try {
    return await getSurfers();
  } catch (error) {
    console.error('Error fetching surfers:', error);
    return [];
  }
}

export default async function SurferWallPage({ params }: Props) {
  const { locale } = await params;
  setRequestLocale(locale);

  const surfers = await fetchSurfers();

  return <SurferWallContent locale={locale as Locale} surfers={surfers} />;
}

interface SurferWallContentProps {
  locale: Locale;
  surfers: Surfer[];
}

function SurferWallContent({ locale, surfers }: SurferWallContentProps) {
  const t = useTranslations('surfers');

  // Separate featured and regular surfers
  const featuredSurfers = surfers.filter((s) => s.featured);
  const regularSurfers = surfers.filter((s) => !s.featured);

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

      {/* Featured Surfers */}
      {featuredSurfers.length > 0 && (
        <section className="py-12">
          <div className="container mx-auto px-4">
            <h2 className="mb-8 text-2xl font-bold">{t('featuredSurfers')}</h2>
            <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
              {featuredSurfers.map((surfer) => (
                <SurferCard key={surfer.id} surfer={surfer} locale={locale} featured />
              ))}
            </div>
          </div>
        </section>
      )}

      {/* All Surfers */}
      <section className={`py-12 ${featuredSurfers.length > 0 ? 'bg-muted/30' : ''}`}>
        <div className="container mx-auto px-4">
          {featuredSurfers.length > 0 && regularSurfers.length > 0 && (
            <h2 className="mb-8 text-2xl font-bold">Todos os Surfers</h2>
          )}
          {surfers.length > 0 ? (
            <div className="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
              {(featuredSurfers.length > 0 ? regularSurfers : surfers).map((surfer) => (
                <SurferCard key={surfer.id} surfer={surfer} locale={locale} />
              ))}
            </div>
          ) : (
            <div className="py-12 text-center">
              <p className="text-lg text-muted-foreground">{t('noSurfers')}</p>
            </div>
          )}
        </div>
      </section>
    </div>
  );
}

interface SurferCardProps {
  surfer: Surfer;
  locale: Locale;
  featured?: boolean;
}

// Helper function to strip HTML tags from text
function stripHtml(html: string): string {
  return html.replace(/<[^>]*>/g, '').replace(/&nbsp;/g, ' ').trim();
}

function SurferCard({ surfer, locale, featured = false }: SurferCardProps) {
  const t = useTranslations('surfers');

  return (
    <Link href={`/${locale}/surfer-wall/${surfer.slug}`}>
      <Card
        className={`group h-full cursor-pointer overflow-hidden transition-all hover:shadow-lg ${
          featured ? 'border-2 border-ocean' : ''
        }`}
      >
        {/* Photo placeholder */}
        <div
          className={`relative bg-gradient-to-br from-ocean/20 to-ocean/5 ${
            featured ? 'aspect-[3/4]' : 'aspect-square'
          }`}
        >
          {featured && (
            <Badge className="absolute left-2 top-2 bg-ocean text-white">
              Destaque
            </Badge>
          )}
          {/* Gradient overlay on hover */}
          <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 transition-opacity group-hover:opacity-100" />
        </div>

        <CardContent className={`p-4 ${featured ? 'space-y-2' : ''}`}>
          <h3 className={`font-semibold ${featured ? 'text-xl' : 'text-base'}`}>
            {surfer.name}
          </h3>
          {surfer.nationality && (
            <p className="text-sm text-muted-foreground">
              🌍 {surfer.nationality}
            </p>
          )}
          {featured && surfer.bio && (
            <p className="line-clamp-2 text-sm text-muted-foreground">
              {stripHtml(getLocalizedField(surfer.bio, locale))}
            </p>
          )}
          {featured && surfer.achievements && surfer.achievements.length > 0 && (
            <div className="mt-2 flex flex-wrap gap-1">
              {surfer.achievements.slice(0, 2).map((achievement, index) => (
                <Badge key={index} variant="secondary" className="text-xs">
                  {getLocalizedField(achievement, locale)}
                </Badge>
              ))}
              {surfer.achievements.length > 2 && (
                <Badge variant="outline" className="text-xs">
                  +{surfer.achievements.length - 2}
                </Badge>
              )}
            </div>
          )}
        </CardContent>
      </Card>
    </Link>
  );
}
