'use client';

import { useTranslations } from 'next-intl';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Breadcrumbs } from '@/components/ui/breadcrumbs';

export default function ContactoPage() {
  const t = useTranslations('contact');

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
          <h1 className="mb-4 text-4xl font-bold md:text-5xl">{t('title')}</h1>
          <p className="text-xl opacity-90">{t('subtitle')}</p>
        </div>
      </section>

      {/* Content */}
      <section className="py-12">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-1 gap-8 lg:grid-cols-2">
            {/* Contact Form */}
            <Card>
              <CardHeader>
                <CardTitle>{t('title')}</CardTitle>
              </CardHeader>
              <CardContent>
                <form className="space-y-4">
                  <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                      <label htmlFor="name" className="mb-2 block text-sm font-medium">
                        {t('form.name')}
                      </label>
                      <Input id="name" name="name" required />
                    </div>
                    <div>
                      <label htmlFor="email" className="mb-2 block text-sm font-medium">
                        {t('form.email')}
                      </label>
                      <Input id="email" name="email" type="email" required />
                    </div>
                  </div>
                  <div>
                    <label htmlFor="subject" className="mb-2 block text-sm font-medium">
                      {t('form.subject')}
                    </label>
                    <Input id="subject" name="subject" required />
                  </div>
                  <div>
                    <label htmlFor="message" className="mb-2 block text-sm font-medium">
                      {t('form.message')}
                    </label>
                    <textarea
                      id="message"
                      name="message"
                      rows={5}
                      required
                      className="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                    />
                  </div>
                  <Button type="submit" className="w-full">
                    {t('form.submit')}
                  </Button>
                </form>
              </CardContent>
            </Card>

            {/* Contact Info */}
            <div className="space-y-6">
              <Card>
                <CardHeader>
                  <CardTitle>{t('info.title')}</CardTitle>
                </CardHeader>
                <CardContent className="space-y-4">
                  <div>
                    <h4 className="font-medium text-muted-foreground">{t('info.address')}</h4>
                    <p>Nazaré Qualifica, EM</p>
                    <p>Avenida da República</p>
                    <p>2450-065 Nazaré, Portugal</p>
                  </div>
                  <div>
                    <h4 className="font-medium text-muted-foreground">{t('info.phone')}</h4>
                    <p>+351 262 000 000</p>
                  </div>
                  <div>
                    <h4 className="font-medium text-muted-foreground">{t('info.email')}</h4>
                    <a href="mailto:geral@nazarequalifica.pt" className="text-ocean hover:underline">
                      geral@nazarequalifica.pt
                    </a>
                  </div>
                  <div>
                    <h4 className="font-medium text-muted-foreground">{t('info.hours')}</h4>
                    <p>Segunda a Sexta: 9h00 - 17h30</p>
                    <p>Sábado e Domingo: Encerrado</p>
                  </div>
                </CardContent>
              </Card>

              {/* Map placeholder */}
              <Card>
                <CardContent className="p-0">
                  <div className="flex h-64 items-center justify-center bg-gradient-to-br from-ocean/20 to-ocean/5">
                    <p className="text-muted-foreground">Mapa em breve</p>
                  </div>
                </CardContent>
              </Card>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
