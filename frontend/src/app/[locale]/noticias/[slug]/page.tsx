import { useTranslations } from 'next-intl';
import { setRequestLocale } from 'next-intl/server';
import Link from 'next/link';
import Image from 'next/image';
import { notFound } from 'next/navigation';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Breadcrumbs } from '@/components/ui/breadcrumbs';
import {
  getNoticiaBySlug,
  getLatestNoticias,
  getLocalizedField,
  type Noticia,
  type Locale,
} from '@/lib/api';
import { Card, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

type Props = {
  params: Promise<{ locale: string; slug: string }>;
};

async function fetchNoticia(slug: string) {
  try {
    return await getNoticiaBySlug(slug);
  } catch (error) {
    console.error('Error fetching noticia:', error);
    return null;
  }
}

async function fetchRelatedNoticias(currentSlug: string) {
  try {
    const noticias = await getLatestNoticias(4);
    return noticias.filter((n) => n.slug !== currentSlug).slice(0, 3);
  } catch (error) {
    console.error('Error fetching related noticias:', error);
    return [];
  }
}

export default async function NoticiaPage({ params }: Props) {
  const { locale, slug } = await params;
  setRequestLocale(locale);

  const [noticia, relatedNoticias] = await Promise.all([
    fetchNoticia(slug),
    fetchRelatedNoticias(slug),
  ]);

  if (!noticia) {
    notFound();
  }

  return (
    <NoticiaContent
      locale={locale as Locale}
      noticia={noticia}
      relatedNoticias={relatedNoticias}
    />
  );
}

interface NoticiaContentProps {
  locale: Locale;
  noticia: Noticia;
  relatedNoticias: Noticia[];
}

function NoticiaContent({ locale, noticia, relatedNoticias }: NoticiaContentProps) {
  const t = useTranslations('news');
  const tCommon = useTranslations('common');

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

      {/* Hero */}
      <section className="relative bg-gradient-to-br from-ocean via-ocean-dark to-ocean-light py-20 text-white">
        <div className="container mx-auto px-4">
          <div className="mb-4 flex items-center gap-2">
            <Badge className={entityColors[noticia.entity]}>
              {entityLabels[noticia.entity]}
            </Badge>
            {noticia.featured && (
              <Badge variant="secondary">{t('featured')}</Badge>
            )}
          </div>
          <h1 className="mb-4 text-3xl font-bold md:text-5xl">
            {getLocalizedField(noticia.title, locale)}
          </h1>
          <div className="flex flex-wrap items-center gap-4 text-sm opacity-90">
            <span>{formatDate(noticia.published_at)}</span>
            {noticia.author && (
              <>
                <span>•</span>
                <span>{t('by', { author: noticia.author })}</span>
              </>
            )}
          </div>
        </div>
      </section>

      {/* Content */}
      <section className="py-12">
        <div className="container mx-auto px-4">
          <div className="mx-auto max-w-3xl">
            {/* Back button */}
            <div className="mb-8">
              <Button variant="ghost" asChild>
                <Link href={`/${locale}/noticias`}>
                  ← {t('backToList')}
                </Link>
              </Button>
            </div>

            {/* Cover image */}
            {noticia.cover_image && (
              <div className="relative mb-8 h-64 overflow-hidden rounded-lg md:h-96">
                <Image
                  src={`${process.env.NEXT_PUBLIC_API_URL}/storage/${noticia.cover_image}`}
                  alt={getLocalizedField(noticia.title, locale)}
                  fill
                  className="object-cover"
                  sizes="(max-width: 768px) 100vw, 800px"
                />
              </div>
            )}

            {/* Article content */}
            <article
              className="prose prose-lg max-w-none dark:prose-invert"
              dangerouslySetInnerHTML={{
                __html: getLocalizedField(noticia.content, locale),
              }}
            />

            {/* Tags */}
            {noticia.tags && noticia.tags.length > 0 && (
              <div className="mt-8 flex flex-wrap gap-2">
                {noticia.tags.map((tag) => (
                  <Badge key={tag} variant="outline">
                    {tag}
                  </Badge>
                ))}
              </div>
            )}
          </div>
        </div>
      </section>

      {/* Related News */}
      {relatedNoticias.length > 0 && (
        <section className="border-t bg-muted/30 py-12">
          <div className="container mx-auto px-4">
            <h2 className="mb-8 text-2xl font-bold">{t('relatedNews')}</h2>
            <div className="grid grid-cols-1 gap-6 md:grid-cols-3">
              {relatedNoticias.map((related) => (
                <Link key={related.id} href={`/${locale}/noticias/${related.slug}`}>
                  <Card className="group h-full cursor-pointer overflow-hidden transition-shadow hover:shadow-lg">
                    <div className="relative h-32">
                      {related.cover_image ? (
                        <Image
                          src={`${process.env.NEXT_PUBLIC_API_URL}/storage/${related.cover_image}`}
                          alt={getLocalizedField(related.title, locale)}
                          fill
                          className="object-cover transition-transform group-hover:scale-105"
                          sizes="(max-width: 768px) 100vw, 33vw"
                        />
                      ) : (
                        <div className="h-full w-full bg-gradient-to-br from-ocean/20 to-ocean/5" />
                      )}
                    </div>
                    <CardHeader>
                      <CardTitle className="line-clamp-2 text-lg">
                        {getLocalizedField(related.title, locale)}
                      </CardTitle>
                      <CardDescription className="line-clamp-2">
                        {getLocalizedField(related.excerpt, locale)}
                      </CardDescription>
                    </CardHeader>
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
