import { useTranslations } from 'next-intl';
import { setRequestLocale } from 'next-intl/server';
import {
  Waves,
  ExternalLink,
  Clock,
  Wind,
  Compass,
  Timer,
  Thermometer,
  TrendingUp,
  ArrowUpRight,
  Gauge,
} from 'lucide-react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Breadcrumbs } from '@/components/ui/breadcrumbs';
import {
  getFullForecast,
  processForecast,
  degreesToCardinal,
  getWaveCondition,
  getWindType,
  getWindStrength,
  type ProcessedForecast,
} from '@/lib/api/forecast';

type Props = {
  params: Promise<{ locale: string }>;
};

async function fetchForecast(): Promise<ProcessedForecast | null> {
  try {
    const { marine, weather } = await getFullForecast();
    return processForecast(marine, weather);
  } catch (error) {
    console.error('Error fetching forecast:', error);
    return null;
  }
}

export default async function PrevisoesPage({ params }: Props) {
  const { locale } = await params;
  setRequestLocale(locale);

  const forecast = await fetchForecast();

  return (
    <PrevisoesContent
      locale={locale as 'pt' | 'en'}
      forecast={forecast}
    />
  );
}

interface PrevisoesContentProps {
  locale: 'pt' | 'en';
  forecast: ProcessedForecast | null;
}

function PrevisoesContent({ locale, forecast }: PrevisoesContentProps) {
  const t = useTranslations('forecast');

  const formatDateTime = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleString(locale === 'pt' ? 'pt-PT' : 'en-US', {
      day: 'numeric',
      month: 'long',
      hour: '2-digit',
      minute: '2-digit',
    });
  };

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString(locale === 'pt' ? 'pt-PT' : 'en-US', {
      weekday: 'short',
      day: 'numeric',
      month: 'short',
    });
  };

  // Get wind type info for display
  const windInfo = forecast
    ? getWindType(forecast.current.windDirection, locale)
    : null;

  return (
    <div className="flex flex-col">
      {/* Breadcrumbs */}
      <div className="border-b bg-muted/30">
        <div className="container mx-auto px-4">
          <Breadcrumbs />
        </div>
      </div>

      {/* Hero */}
      <section className="bg-gradient-to-br from-ocean via-ocean-dark to-ocean-light py-16 text-white">
        <div className="container mx-auto px-4">
          <div className="flex items-center gap-3">
            <Waves className="h-10 w-10" />
            <div>
              <h1 className="text-4xl font-bold md:text-5xl">{t('title')}</h1>
              <p className="mt-2 text-xl opacity-90">{t('subtitle')}</p>
            </div>
          </div>
          {forecast && (
            <div className="mt-4 flex items-center gap-2 text-sm opacity-75">
              <Clock className="h-4 w-4" />
              {t('updated', { time: formatDateTime(forecast.lastUpdated) })}
            </div>
          )}
        </div>
      </section>

      {/* Current Conditions - 8 Cards */}
      <section className="py-12">
        <div className="container mx-auto px-4">
          <h2 className="mb-6 text-2xl font-bold">{t('marine.title')}</h2>

          {forecast ? (
            <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
              {/* Row 1: Primary Metrics - Wave Height & Swell (larger cards) */}

              {/* 1. Wave Height - PROMINENT */}
              <Card className="sm:col-span-2 border-ocean/30 bg-gradient-to-br from-ocean/5 to-ocean/10">
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-base font-semibold">
                    {t('marine.waveHeight')}
                  </CardTitle>
                  <Waves className="h-6 w-6 text-ocean" />
                </CardHeader>
                <CardContent>
                  <div className="text-4xl font-bold text-ocean">
                    {forecast.current.waveHeight.toFixed(1)} {t('units.meters')}
                  </div>
                  <p className="mt-1 text-sm text-muted-foreground">
                    {getWaveCondition(forecast.current.waveHeight, locale)}
                    {forecast.current.waveHeight >= 6 && ' 🌊'}
                  </p>
                </CardContent>
              </Card>

              {/* 2. Swell Height - PROMINENT */}
              <Card className="sm:col-span-2 border-ocean/30 bg-gradient-to-br from-ocean/5 to-ocean/10">
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-base font-semibold">
                    {t('marine.swellHeight')}
                  </CardTitle>
                  <TrendingUp className="h-6 w-6 text-ocean" />
                </CardHeader>
                <CardContent>
                  <div className="text-4xl font-bold text-ocean">
                    {forecast.current.swellHeight.toFixed(1)} {t('units.meters')}
                  </div>
                  <p className="mt-1 text-sm text-muted-foreground">
                    {forecast.current.swellPeriod > 0
                      ? `${forecast.current.swellPeriod.toFixed(0)}s ${degreesToCardinal(forecast.current.swellDirection)}`
                      : (locale === 'pt' ? 'Ondulação de fundo' : 'Ground swell')}
                  </p>
                </CardContent>
              </Card>

              {/* Row 2: Secondary Metrics */}

              {/* 3. Wave Period */}
              <Card>
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">
                    {t('marine.wavePeriod')}
                  </CardTitle>
                  <Timer className="h-4 w-4 text-ocean" />
                </CardHeader>
                <CardContent>
                  <div className="text-2xl font-bold">
                    {forecast.current.wavePeriod.toFixed(0)} {t('units.seconds')}
                  </div>
                  <p className="text-xs text-muted-foreground">
                    {forecast.current.wavePeriod >= 12
                      ? (locale === 'pt' ? 'Ondas de qualidade' : 'Quality waves')
                      : (locale === 'pt' ? 'Entre ondas' : 'Between waves')}
                  </p>
                </CardContent>
              </Card>

              {/* 4. Wave Direction */}
              <Card>
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">
                    {t('marine.waveDirection')}
                  </CardTitle>
                  <Compass className="h-4 w-4 text-ocean" />
                </CardHeader>
                <CardContent>
                  <div className="text-2xl font-bold">
                    {degreesToCardinal(forecast.current.waveDirection)}
                  </div>
                  <p className="text-xs text-muted-foreground">
                    {forecast.current.waveDirection.toFixed(0)}°
                  </p>
                </CardContent>
              </Card>

              {/* 5. Wind Speed */}
              <Card>
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">
                    {t('marine.windSpeed')}
                  </CardTitle>
                  <Wind className="h-4 w-4 text-ocean" />
                </CardHeader>
                <CardContent>
                  <div className="text-2xl font-bold">
                    {forecast.current.windSpeed.toFixed(0)} {t('units.kmh')}
                  </div>
                  <p className="text-xs text-muted-foreground">
                    {getWindStrength(forecast.current.windSpeed, locale)}
                  </p>
                </CardContent>
              </Card>

              {/* 6. Wind Direction */}
              <Card className={
                windInfo?.quality === 'good'
                  ? 'border-green-500/50 bg-green-50/50 dark:bg-green-950/20'
                  : windInfo?.quality === 'poor'
                    ? 'border-red-500/50 bg-red-50/50 dark:bg-red-950/20'
                    : ''
              }>
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">
                    {t('marine.windDirection')}
                  </CardTitle>
                  <ArrowUpRight
                    className={`h-4 w-4 ${
                      windInfo?.quality === 'good'
                        ? 'text-green-600'
                        : windInfo?.quality === 'poor'
                          ? 'text-red-600'
                          : 'text-ocean'
                    }`}
                    style={{ transform: `rotate(${forecast.current.windDirection}deg)` }}
                  />
                </CardHeader>
                <CardContent>
                  <div className={`text-2xl font-bold ${
                    windInfo?.quality === 'good'
                      ? 'text-green-600'
                      : windInfo?.quality === 'poor'
                        ? 'text-red-600'
                        : ''
                  }`}>
                    {degreesToCardinal(forecast.current.windDirection)}
                  </div>
                  <p className="text-xs text-muted-foreground">
                    {windInfo?.label} ({forecast.current.windDirection.toFixed(0)}°)
                  </p>
                </CardContent>
              </Card>

              {/* Row 3: Gusts & Temperature */}

              {/* 7. Wind Gusts */}
              <Card className="sm:col-span-1 lg:col-span-2">
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">
                    {t('marine.windGusts')}
                  </CardTitle>
                  <Gauge className="h-4 w-4 text-ocean" />
                </CardHeader>
                <CardContent>
                  <div className="text-2xl font-bold">
                    {forecast.current.windGusts.toFixed(0)} {t('units.kmh')}
                  </div>
                  <p className="text-xs text-muted-foreground">
                    {forecast.current.windGusts > 50
                      ? (locale === 'pt' ? 'Cuidado!' : 'Caution!')
                      : (locale === 'pt' ? 'Rajadas máximas' : 'Max gusts')}
                  </p>
                </CardContent>
              </Card>

              {/* 8. Water Temperature */}
              <Card className="sm:col-span-1 lg:col-span-2">
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">
                    {t('marine.waterTemperature')}
                  </CardTitle>
                  <Thermometer className="h-4 w-4 text-ocean" />
                </CardHeader>
                <CardContent>
                  <div className="text-2xl font-bold">
                    {forecast.current.waterTemperature.toFixed(0)}°C
                  </div>
                  <p className="text-xs text-muted-foreground">
                    {forecast.current.waterTemperature < 14
                      ? (locale === 'pt' ? 'Fato 5/4mm + botas' : '5/4mm + boots')
                      : forecast.current.waterTemperature < 17
                        ? (locale === 'pt' ? 'Fato 4/3mm' : '4/3mm wetsuit')
                        : forecast.current.waterTemperature < 20
                          ? (locale === 'pt' ? 'Fato 3/2mm' : '3/2mm wetsuit')
                          : (locale === 'pt' ? 'Fato curto/Lycra' : 'Shorty/Rashguard')}
                  </p>
                </CardContent>
              </Card>
            </div>
          ) : (
            <Card>
              <CardContent className="py-8 text-center">
                <p className="text-muted-foreground">{t('error')}</p>
              </CardContent>
            </Card>
          )}
        </div>
      </section>

      {/* 7-Day Forecast */}
      {forecast && (
        <section className="border-t bg-muted/30 py-12">
          <div className="container mx-auto px-4">
            <h2 className="mb-6 text-2xl font-bold">{t('daily.title')}</h2>
            <Card>
              <CardContent className="p-0">
                <div className="overflow-x-auto">
                  <table className="w-full text-sm">
                    <thead>
                      <tr className="border-b bg-muted/50">
                        <th className="p-4 text-left font-medium">{t('daily.day')}</th>
                        <th className="p-4 text-center font-medium">{t('marine.waveHeight')}</th>
                        <th className="p-4 text-center font-medium">{t('marine.wavePeriod')}</th>
                        <th className="p-4 text-center font-medium">{t('marine.waveDirection')}</th>
                      </tr>
                    </thead>
                    <tbody>
                      {forecast.daily.map((day, index) => (
                        <tr
                          key={day.date}
                          className={index < forecast.daily.length - 1 ? 'border-b' : ''}
                        >
                          <td className="p-4 font-medium">{formatDate(day.date)}</td>
                          <td className="p-4 text-center">
                            <span className="font-semibold text-ocean">
                              {day.maxWaveHeight.toFixed(1)} {t('units.meters')}
                            </span>
                          </td>
                          <td className="p-4 text-center">
                            {day.maxWavePeriod.toFixed(0)} {t('units.seconds')}
                          </td>
                          <td className="p-4 text-center">
                            {degreesToCardinal(day.dominantDirection)} ({day.dominantDirection}°)
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              </CardContent>
            </Card>
          </div>
        </section>
      )}

      {/* MONICAN Section */}
      <section className="py-12">
        <div className="container mx-auto px-4">
          <h2 className="mb-6 text-2xl font-bold">{t('monican.title')}</h2>
          <Card>
            <CardHeader>
              <CardTitle>{t('monican.subtitle')}</CardTitle>
              <CardDescription>{t('monican.description')}</CardDescription>
            </CardHeader>
            <CardContent>
              <div className="space-y-4">
                {/* MONICAN iframe */}
                <div className="relative aspect-[16/10] w-full overflow-hidden rounded-lg border bg-muted">
                  <iframe
                    src="https://monican.hidrografico.pt/previsao"
                    title="MONICAN - Previsão Agitação Marítima"
                    className="h-full w-full"
                    sandbox="allow-scripts allow-same-origin"
                    loading="lazy"
                  />
                </div>

                {/* Attribution & Link */}
                <div className="flex flex-col items-center justify-between gap-4 rounded-lg bg-muted/50 p-4 sm:flex-row">
                  <div>
                    <p className="text-sm font-medium">{t('monican.credit')}</p>
                    <p className="text-xs text-muted-foreground">
                      {locale === 'pt'
                        ? 'Sistema de previsão da agitação marítima para a costa portuguesa'
                        : 'Maritime wave forecast system for the Portuguese coast'}
                    </p>
                  </div>
                  <Button asChild variant="outline">
                    <a
                      href="https://monican.hidrografico.pt/previsao"
                      target="_blank"
                      rel="noopener noreferrer"
                    >
                      {t('monican.viewFull')}
                      <ExternalLink className="ml-2 h-4 w-4" />
                    </a>
                  </Button>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
      </section>

      {/* Webcams Section */}
      <section className="border-t bg-muted/30 py-12">
        <div className="container mx-auto px-4">
          <h2 className="mb-6 text-2xl font-bold">{t('webcams.title')}</h2>
          <div className="grid gap-6 md:grid-cols-2">
            {/* Praia do Norte Webcam */}
            <Card className="overflow-hidden">
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <span className="relative flex h-3 w-3">
                    <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                    <span className="relative inline-flex h-3 w-3 rounded-full bg-red-500"></span>
                  </span>
                  {t('webcams.praiaDoNorte')}
                </CardTitle>
                <CardDescription>
                  {locale === 'pt'
                    ? 'Vista ao vivo da Praia do Norte - Ondas Gigantes'
                    : 'Live view of Praia do Norte - Giant Waves'}
                </CardDescription>
              </CardHeader>
              <CardContent>
                <div className="relative aspect-video w-full overflow-hidden rounded-lg border bg-muted">
                  <div className="flex h-full flex-col items-center justify-center gap-4 p-4 text-center">
                    <Waves className="h-12 w-12 text-muted-foreground" />
                    <p className="text-sm text-muted-foreground">
                      {locale === 'pt'
                        ? 'Clique para ver a webcam em janela externa'
                        : 'Click to view webcam in external window'}
                    </p>
                    <Button asChild variant="outline">
                      <a
                        href="https://www.surfline.com/surf-report/praia-do-norte/584204214e65fad6a7709c4f"
                        target="_blank"
                        rel="noopener noreferrer"
                      >
                        {locale === 'pt' ? 'Ver Webcam' : 'View Webcam'}
                        <ExternalLink className="ml-2 h-4 w-4" />
                      </a>
                    </Button>
                  </div>
                </div>
              </CardContent>
            </Card>

            {/* Forte Webcam */}
            <Card className="overflow-hidden">
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <span className="relative flex h-3 w-3">
                    <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                    <span className="relative inline-flex h-3 w-3 rounded-full bg-red-500"></span>
                  </span>
                  {t('webcams.forte')}
                </CardTitle>
                <CardDescription>
                  {locale === 'pt'
                    ? 'Vista do Forte de São Miguel Arcanjo'
                    : 'View from Fort São Miguel Arcanjo'}
                </CardDescription>
              </CardHeader>
              <CardContent>
                <div className="relative aspect-video w-full overflow-hidden rounded-lg border bg-muted">
                  <div className="flex h-full flex-col items-center justify-center gap-4 p-4 text-center">
                    <Waves className="h-12 w-12 text-muted-foreground" />
                    <p className="text-sm text-muted-foreground">
                      {locale === 'pt'
                        ? 'Clique para ver a webcam em janela externa'
                        : 'Click to view webcam in external window'}
                    </p>
                    <Button asChild variant="outline">
                      <a
                        href="https://beachcam.meo.pt/livecams/nazare-norte/"
                        target="_blank"
                        rel="noopener noreferrer"
                      >
                        {locale === 'pt' ? 'Ver Webcam' : 'View Webcam'}
                        <ExternalLink className="ml-2 h-4 w-4" />
                      </a>
                    </Button>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>
        </div>
      </section>
    </div>
  );
}
