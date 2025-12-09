import { setRequestLocale } from 'next-intl/server';
import { notFound } from 'next/navigation';
import Link from 'next/link';
import { Button } from '@/components/ui/button';
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

export default async function NazareQualificaServicosPage({ params }: Props) {
  const { locale } = await params;
  setRequestLocale(locale);

  const pagina = await fetchPagina('nazare-qualifica', 'servicos');

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
      <section className="bg-gradient-to-br from-institutional via-institutional/80 to-institutional/60 py-20 text-white">
        <div className="container mx-auto px-4">
          <p className="mb-2 text-sm font-medium uppercase tracking-wider opacity-75">Nazaré Qualifica</p>
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
              className="prose prose-lg max-w-none dark:prose-invert prose-headings:text-institutional prose-a:text-institutional"
              dangerouslySetInnerHTML={{
                __html: getLocalizedField(pagina.content, locale as Locale),
              }}
            />

            {/* CTA */}
            <div className="mt-12 rounded-lg bg-institutional/10 p-8 text-center">
              <h3 className="mb-4 text-2xl font-bold">Precisa de mais informações?</h3>
              <p className="mb-6 text-muted-foreground">
                A nossa equipa está disponível para esclarecer qualquer dúvida.
              </p>
              <Button asChild className="bg-institutional hover:bg-institutional/90">
                <Link href={`/${locale}/contacto`}>Contactar</Link>
              </Button>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
