'use client';

import { useTranslations } from 'next-intl';
import { ExternalLink, Info } from 'lucide-react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';

const MONICAN_URL = 'https://monican.hidrografico.pt/previsao';
const MONICAN_FORECAST_IMAGE = 'https://monican.hidrografico.pt/images/previsao/hs_nazare.png';

export function MonicianEmbed() {
  const t = useTranslations('forecast');

  return (
    <Card className="overflow-hidden">
      <CardHeader>
        <div className="flex items-start justify-between">
          <div>
            <CardTitle className="flex items-center gap-2">
              {t('monican.title')}
              <Info className="h-4 w-4 text-muted-foreground" />
            </CardTitle>
            <CardDescription>{t('monican.description')}</CardDescription>
          </div>
          <Button variant="outline" size="sm" asChild>
            <a href={MONICAN_URL} target="_blank" rel="noopener noreferrer">
              {t('monican.viewFull')}
              <ExternalLink className="ml-2 h-4 w-4" />
            </a>
          </Button>
        </div>
      </CardHeader>
      <CardContent>
        <div className="space-y-4">
          {/* MONICAN Forecast Image */}
          <div className="relative aspect-video w-full overflow-hidden rounded-lg border bg-muted">
            <iframe
              src={MONICAN_URL}
              title="MONICAN Forecast"
              className="h-full w-full"
              sandbox="allow-scripts allow-same-origin"
              loading="lazy"
            />
          </div>

          {/* Attribution */}
          <div className="flex items-center justify-between rounded-lg bg-muted/50 p-3">
            <p className="text-xs text-muted-foreground">
              {t('monican.credit')}
            </p>
            <a
              href="https://www.hidrografico.pt"
              target="_blank"
              rel="noopener noreferrer"
              className="text-xs text-ocean hover:underline"
            >
              Instituto Hidrográfico
            </a>
          </div>
        </div>
      </CardContent>
    </Card>
  );
}

export function MonicianLink() {
  const t = useTranslations('forecast');

  return (
    <Card>
      <CardHeader>
        <CardTitle className="flex items-center gap-2">
          {t('monican.title')}
        </CardTitle>
        <CardDescription>{t('monican.description')}</CardDescription>
      </CardHeader>
      <CardContent>
        <div className="space-y-4">
          <p className="text-sm text-muted-foreground">
            {t('monican.info')}
          </p>
          <Button asChild className="w-full bg-ocean hover:bg-ocean-dark">
            <a href={MONICAN_URL} target="_blank" rel="noopener noreferrer">
              {t('monican.viewFull')}
              <ExternalLink className="ml-2 h-4 w-4" />
            </a>
          </Button>
          <p className="text-center text-xs text-muted-foreground">
            {t('monican.credit')}
          </p>
        </div>
      </CardContent>
    </Card>
  );
}
