import Link from 'next/link';
import { useLocale, useTranslations } from 'next-intl';

export function Footer() {
  const t = useTranslations('footer');
  const tEntities = useTranslations('entities');
  const tNav = useTranslations('navigation');
  const locale = useLocale();
  const currentYear = new Date().getFullYear();

  return (
    <footer className="border-t bg-muted/50">
      <div className="container mx-auto px-4 py-12">
        <div className="grid grid-cols-1 gap-8 md:grid-cols-4">
          {/* Praia do Norte */}
          <div>
            <h3 className="mb-4 text-lg font-semibold text-ocean">
              {tEntities('praiaDoNorte')}
            </h3>
            <ul className="space-y-2 text-sm text-muted-foreground">
              <li>
                <Link href={`/${locale}/sobre`} className="hover:text-ocean transition-colors">
                  {tNav('about')}
                </Link>
              </li>
              <li>
                <Link href={`/${locale}/noticias`} className="hover:text-ocean transition-colors">
                  {tNav('news')}
                </Link>
              </li>
              <li>
                <Link href={`/${locale}/surfer-wall`} className="hover:text-ocean transition-colors">
                  {tNav('surferWall')}
                </Link>
              </li>
              <li>
                <Link href={`/${locale}/eventos`} className="hover:text-ocean transition-colors">
                  {tNav('events')}
                </Link>
              </li>
            </ul>
          </div>

          {/* Carsurf */}
          <div>
            <h3 className="mb-4 text-lg font-semibold text-performance">
              {tEntities('carsurf')}
            </h3>
            <ul className="space-y-2 text-sm text-muted-foreground">
              <li>
                <Link href={`/${locale}/carsurf`} className="hover:text-performance transition-colors">
                  {tNav('about')}
                </Link>
              </li>
              <li>
                <Link href={`/${locale}/carsurf/programas`} className="hover:text-performance transition-colors">
                  Programas
                </Link>
              </li>
            </ul>
          </div>

          {/* Nazaré Qualifica */}
          <div>
            <h3 className="mb-4 text-lg font-semibold text-institutional">
              {tEntities('nazareQualifica')}
            </h3>
            <ul className="space-y-2 text-sm text-muted-foreground">
              <li>
                <Link href={`/${locale}/nazare-qualifica/sobre`} className="hover:text-institutional transition-colors">
                  {tNav('about')}
                </Link>
              </li>
              <li>
                <Link href={`/${locale}/nazare-qualifica/servicos`} className="hover:text-institutional transition-colors">
                  Serviços
                </Link>
              </li>
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h3 className="mb-4 text-lg font-semibold">{tNav('contact')}</h3>
            <address className="not-italic text-sm text-muted-foreground space-y-2">
              <p>Nazaré Qualifica, EM</p>
              <p>Nazaré, Portugal</p>
              <p>
                <a href="mailto:geral@nazarequalifica.pt" className="hover:text-ocean transition-colors">
                  geral@nazarequalifica.pt
                </a>
              </p>
            </address>
          </div>
        </div>

        {/* Bottom */}
        <div className="mt-8 flex flex-col items-center justify-between gap-4 border-t pt-8 md:flex-row">
          <p className="text-sm text-muted-foreground">
            {t('copyright', { year: currentYear })}
          </p>
          <div className="flex gap-4 text-sm text-muted-foreground">
            <Link href={`/${locale}/privacidade`} className="hover:text-foreground transition-colors">
              {t('links.privacy')}
            </Link>
            <Link href={`/${locale}/termos`} className="hover:text-foreground transition-colors">
              {t('links.terms')}
            </Link>
            <Link href={`/${locale}/cookies`} className="hover:text-foreground transition-colors">
              {t('links.cookies')}
            </Link>
          </div>
        </div>
      </div>
    </footer>
  );
}
