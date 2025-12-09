import { setRequestLocale } from 'next-intl/server';
import { notFound } from 'next/navigation';
import {
  getPagina,
  getLocalizedField,
  type Locale,
} from '@/lib/api';
import { Breadcrumbs } from '@/components/ui/breadcrumbs';

type Props = {
  params: Promise<{ locale: string }>;
};

async function fetchPagina(entity: string, slug: string) {
  try {
    return await getPagina(entity, slug);
  } catch (error) {
    console.error('Error fetching pagina:', error);
    return null;
  }
}

export default async function SobrePage({ params }: Props) {
  const { locale } = await params;
  setRequestLocale(locale);

  const pagina = await fetchPagina('praia-norte', 'sobre');

  if (!pagina) {
    notFound();
  }

  return (
    <div className="flex flex-col">
      {/* Breadcrumbs */}
      <div className="border-b bg-muted/30">
        <div className="container mx-auto px-4">
          <Breadcrumbs />
        </div>
      </div>

      {/* Hero */}
      <section className="bg-gradient-to-br from-ocean via-ocean-dark to-ocean-light py-20 text-white">
        <div className="container mx-auto px-4">
          <h1 className="mb-4 text-4xl font-bold md:text-5xl">
            {getLocalizedField(pagina.title, locale as Locale)}
          </h1>
        </div>
      </section>

      {/* Content */}
      <section className="py-12">
        <div className="container mx-auto px-4">
          <div className="mx-auto max-w-4xl">
            <article
              className="prose prose-lg max-w-none dark:prose-invert prose-headings:text-ocean prose-a:text-ocean"
              dangerouslySetInnerHTML={{
                __html: getLocalizedField(pagina.content, locale as Locale),
              }}
            />
          </div>
        </div>
      </section>
    </div>
  );
}
