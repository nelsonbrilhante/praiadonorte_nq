'use client';

import { useTranslations } from 'next-intl';
import { Waves, Wind, Compass, Timer } from 'lucide-react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { type ProcessedForecast, degreesToCardinal, getWaveCondition } from '@/lib/api/forecast';

interface MarineForecastCardProps {
  forecast: ProcessedForecast;
  locale: 'pt' | 'en';
}

export function MarineForecastCard({ forecast, locale }: MarineForecastCardProps) {
  const t = useTranslations('forecast');

  const { current } = forecast;

  return (
    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
      {/* Wave Height */}
      <Card>
        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
          <CardTitle className="text-sm font-medium">
            {t('marine.waveHeight')}
          </CardTitle>
          <Waves className="h-4 w-4 text-ocean" />
        </CardHeader>
        <CardContent>
          <div className="text-2xl font-bold text-ocean">
            {current.waveHeight.toFixed(1)} {t('units.meters')}
          </div>
          <p className="text-xs text-muted-foreground">
            {getWaveCondition(current.waveHeight, locale)}
          </p>
        </CardContent>
      </Card>

      {/* Wave Period */}
      <Card>
        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
          <CardTitle className="text-sm font-medium">
            {t('marine.wavePeriod')}
          </CardTitle>
          <Timer className="h-4 w-4 text-ocean" />
        </CardHeader>
        <CardContent>
          <div className="text-2xl font-bold">
            {current.wavePeriod.toFixed(0)} {t('units.seconds')}
          </div>
          <p className="text-xs text-muted-foreground">
            {locale === 'pt' ? 'Entre ondas' : 'Between waves'}
          </p>
        </CardContent>
      </Card>

      {/* Wave Direction */}
      <Card>
        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
          <CardTitle className="text-sm font-medium">
            {t('marine.waveDirection')}
          </CardTitle>
          <Compass className="h-4 w-4 text-ocean" />
        </CardHeader>
        <CardContent>
          <div className="text-2xl font-bold">
            {degreesToCardinal(current.waveDirection)}
          </div>
          <p className="text-xs text-muted-foreground">
            {current.waveDirection.toFixed(0)}°
          </p>
        </CardContent>
      </Card>

      {/* Swell Height */}
      <Card>
        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
          <CardTitle className="text-sm font-medium">
            {t('marine.swellHeight')}
          </CardTitle>
          <Wind className="h-4 w-4 text-ocean" />
        </CardHeader>
        <CardContent>
          <div className="text-2xl font-bold">
            {current.swellHeight.toFixed(1)} {t('units.meters')}
          </div>
          <p className="text-xs text-muted-foreground">
            {locale === 'pt' ? 'Ondulação' : 'Swell'}
          </p>
        </CardContent>
      </Card>
    </div>
  );
}

interface DailyForecastTableProps {
  forecast: ProcessedForecast;
  locale: 'pt' | 'en';
}

export function DailyForecastTable({ forecast, locale }: DailyForecastTableProps) {
  const t = useTranslations('forecast');

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString(locale === 'pt' ? 'pt-PT' : 'en-US', {
      weekday: 'short',
      day: 'numeric',
      month: 'short',
    });
  };

  return (
    <Card>
      <CardHeader>
        <CardTitle>{t('daily.title')}</CardTitle>
      </CardHeader>
      <CardContent>
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead>
              <tr className="border-b">
                <th className="pb-2 text-left font-medium">{t('daily.day')}</th>
                <th className="pb-2 text-center font-medium">{t('marine.waveHeight')}</th>
                <th className="pb-2 text-center font-medium">{t('marine.wavePeriod')}</th>
                <th className="pb-2 text-center font-medium">{t('marine.waveDirection')}</th>
              </tr>
            </thead>
            <tbody>
              {forecast.daily.map((day, index) => (
                <tr key={day.date} className={index < forecast.daily.length - 1 ? 'border-b' : ''}>
                  <td className="py-3 font-medium">{formatDate(day.date)}</td>
                  <td className="py-3 text-center">
                    <span className="font-semibold text-ocean">
                      {day.maxWaveHeight.toFixed(1)} {t('units.meters')}
                    </span>
                  </td>
                  <td className="py-3 text-center">
                    {day.maxWavePeriod.toFixed(0)} {t('units.seconds')}
                  </td>
                  <td className="py-3 text-center">
                    {degreesToCardinal(day.dominantDirection)} ({day.dominantDirection}°)
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </CardContent>
    </Card>
  );
}
