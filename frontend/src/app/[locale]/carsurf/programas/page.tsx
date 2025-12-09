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

export default async function CarsurfProgramasPage({ params }: Props) {
  const { locale } = await params;
  setRequestLocale(locale);

  const pagina = await fetchPagina('carsurf', 'programas');

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
      <section className="bg-gradient-to-br from-performance via-performance/80 to-performance/60 py-20 text-white">
        <div className="container mx-auto px-4">
          <p className="mb-2 text-sm font-medium uppercase tracking-wider opacity-75">Carsurf</p>
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
              className="prose prose-lg max-w-none dark:prose-invert prose-headings:text-performance prose-a:text-performance prose-li:marker:text-performance"
              dangerouslySetInnerHTML={{
                __html: getLocalizedField(pagina.content, locale as Locale),
              }}
            />

            {/* CTA */}
            <div className="mt-12 rounded-lg bg-performance/10 p-8 text-center">
              <h3 className="mb-4 text-2xl font-bold">Pronto para começar?</h3>
              <p className="mb-6 text-muted-foreground">
                Entre em contacto para saber mais sobre os nossos programas e inscrições.
              </p>
              <Button asChild className="bg-performance hover:bg-performance/90">
                <Link href={`/${locale}/contacto`}>Contactar</Link>
              </Button>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
