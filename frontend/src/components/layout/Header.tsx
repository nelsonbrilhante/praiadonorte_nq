'use client';

import Link from 'next/link';
import { useLocale, useTranslations } from 'next-intl';
import { LanguageSwitcher } from './LanguageSwitcher';
import { ThemeToggle } from '@/components/ui/theme-toggle';
import { SearchCommand } from '@/components/ui/search-command';
import {
  NavigationMenu,
  NavigationMenuContent,
  NavigationMenuItem,
  NavigationMenuLink,
  NavigationMenuList,
  NavigationMenuTrigger,
  navigationMenuTriggerStyle,
} from '@/components/ui/navigation-menu';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';

export function Header() {
  const t = useTranslations('navigation');
  const tEntities = useTranslations('entities');
  const locale = useLocale();

  return (
    <header className="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
      <div className="container mx-auto flex h-16 items-center justify-between px-4">
        {/* Logo */}
        <Link href={`/${locale}`} className="flex items-center gap-2">
          <div className="flex flex-col">
            <span className="text-xl font-bold text-ocean">Nazaré Qualifica</span>
            <span className="text-[10px] text-muted-foreground">Nazaré, Portugal</span>
          </div>
        </Link>

        {/* Navigation */}
        <NavigationMenu className="hidden md:flex">
          <NavigationMenuList>
            <NavigationMenuItem>
              <Link href={`/${locale}`} legacyBehavior passHref>
                <NavigationMenuLink className={navigationMenuTriggerStyle()}>
                  {t('home')}
                </NavigationMenuLink>
              </Link>
            </NavigationMenuItem>

            <NavigationMenuItem>
              <NavigationMenuTrigger>{t('about')}</NavigationMenuTrigger>
              <NavigationMenuContent>
                <ul className="grid w-[400px] gap-3 p-4 md:w-[500px] md:grid-cols-2">
                  <li>
                    <NavigationMenuLink asChild>
                      <Link
                        href={`/${locale}/sobre`}
                        className={cn(
                          'block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground'
                        )}
                      >
                        <div className="text-sm font-medium leading-none text-ocean">
                          {tEntities('praiaDoNorte')}
                        </div>
                        <p className="line-clamp-2 text-sm leading-snug text-muted-foreground">
                          Ondas gigantes e surf de elite
                        </p>
                      </Link>
                    </NavigationMenuLink>
                  </li>
                  <li>
                    <NavigationMenuLink asChild>
                      <Link
                        href={`/${locale}/carsurf/sobre`}
                        className={cn(
                          'block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground'
                        )}
                      >
                        <div className="text-sm font-medium leading-none text-performance">
                          {tEntities('carsurf')}
                        </div>
                        <p className="line-clamp-2 text-sm leading-snug text-muted-foreground">
                          Centro de alto rendimento
                        </p>
                      </Link>
                    </NavigationMenuLink>
                  </li>
                  <li>
                    <NavigationMenuLink asChild>
                      <Link
                        href={`/${locale}/nazare-qualifica/sobre`}
                        className={cn(
                          'block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground'
                        )}
                      >
                        <div className="text-sm font-medium leading-none text-institutional">
                          {tEntities('nazareQualifica')}
                        </div>
                        <p className="line-clamp-2 text-sm leading-snug text-muted-foreground">
                          Empresa municipal
                        </p>
                      </Link>
                    </NavigationMenuLink>
                  </li>
                </ul>
              </NavigationMenuContent>
            </NavigationMenuItem>

            <NavigationMenuItem>
              <Link href={`/${locale}/noticias`} legacyBehavior passHref>
                <NavigationMenuLink className={navigationMenuTriggerStyle()}>
                  {t('news')}
                </NavigationMenuLink>
              </Link>
            </NavigationMenuItem>

            <NavigationMenuItem>
              <Link href={`/${locale}/surfer-wall`} legacyBehavior passHref>
                <NavigationMenuLink className={navigationMenuTriggerStyle()}>
                  {t('surferWall')}
                </NavigationMenuLink>
              </Link>
            </NavigationMenuItem>

            <NavigationMenuItem>
              <Link href={`/${locale}/eventos`} legacyBehavior passHref>
                <NavigationMenuLink className={navigationMenuTriggerStyle()}>
                  {t('events')}
                </NavigationMenuLink>
              </Link>
            </NavigationMenuItem>

            <NavigationMenuItem>
              <Link href={`/${locale}/previsoes`} legacyBehavior passHref>
                <NavigationMenuLink className={navigationMenuTriggerStyle()}>
                  {t('forecast')}
                </NavigationMenuLink>
              </Link>
            </NavigationMenuItem>
          </NavigationMenuList>
        </NavigationMenu>

        {/* Right side */}
        <div className="flex items-center gap-2">
          <SearchCommand />
          <ThemeToggle />
          <LanguageSwitcher />
          <Button asChild className="hidden sm:inline-flex bg-ocean hover:bg-ocean-dark">
            <Link href={`/${locale}/contacto`}>{t('contact')}</Link>
          </Button>
        </div>
      </div>
    </header>
  );
}
