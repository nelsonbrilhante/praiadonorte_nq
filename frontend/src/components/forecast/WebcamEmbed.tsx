'use client';

import { useTranslations } from 'next-intl';
import { Video, ExternalLink, RefreshCw } from 'lucide-react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { useState } from 'react';

interface WebcamConfig {
  id: string;
  name: string;
  embedUrl?: string;
  externalUrl: string;
  description: string;
}

const WEBCAMS: WebcamConfig[] = [
  {
    id: 'praia-norte',
    name: 'Praia do Norte',
    embedUrl: 'https://www.surfline.com/surf-report/praia-do-norte/584204214e65fad6a7709c4f',
    externalUrl: 'https://www.surfline.com/surf-report/praia-do-norte/584204214e65fad6a7709c4f',
    description: 'Vista da Praia do Norte - Ondas Gigantes',
  },
  {
    id: 'forte',
    name: 'Forte de S\u00e3o Miguel Arcanjo',
    externalUrl: 'https://beachcam.meo.pt/livecams/nazare-norte/',
    description: 'Vista do Forte - Miradouro',
  },
];

export function WebcamSection() {
  const t = useTranslations('forecast');

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h2 className="text-2xl font-bold">{t('webcams.title')}</h2>
      </div>
      <div className="grid gap-4 md:grid-cols-2">
        {WEBCAMS.map((webcam) => (
          <WebcamCard key={webcam.id} webcam={webcam} />
        ))}
      </div>
    </div>
  );
}

interface WebcamCardProps {
  webcam: WebcamConfig;
}

function WebcamCard({ webcam }: WebcamCardProps) {
  const [refreshKey, setRefreshKey] = useState(0);

  const handleRefresh = () => {
    setRefreshKey((prev) => prev + 1);
  };

  return (
    <Card className="overflow-hidden">
      <CardHeader className="pb-2">
        <div className="flex items-start justify-between">
          <div>
            <CardTitle className="flex items-center gap-2 text-lg">
              <Video className="h-4 w-4 text-red-500" />
              {webcam.name}
            </CardTitle>
            <CardDescription>{webcam.description}</CardDescription>
          </div>
          <div className="flex gap-1">
            <Button variant="ghost" size="icon" onClick={handleRefresh}>
              <RefreshCw className="h-4 w-4" />
            </Button>
            <Button variant="ghost" size="icon" asChild>
              <a href={webcam.externalUrl} target="_blank" rel="noopener noreferrer">
                <ExternalLink className="h-4 w-4" />
              </a>
            </Button>
          </div>
        </div>
      </CardHeader>
      <CardContent>
        <div
          key={refreshKey}
          className="relative aspect-video w-full overflow-hidden rounded-lg border bg-muted"
        >
          {webcam.embedUrl ? (
            <iframe
              src={webcam.embedUrl}
              title={`Webcam ${webcam.name}`}
              className="h-full w-full"
              allow="autoplay"
              loading="lazy"
            />
          ) : (
            <div className="flex h-full flex-col items-center justify-center gap-4 p-4 text-center">
              <Video className="h-12 w-12 text-muted-foreground" />
              <div>
                <p className="font-medium">{webcam.name}</p>
                <p className="text-sm text-muted-foreground">
                  Clique para ver em janela externa
                </p>
              </div>
              <Button variant="outline" asChild>
                <a href={webcam.externalUrl} target="_blank" rel="noopener noreferrer">
                  Ver Webcam
                  <ExternalLink className="ml-2 h-4 w-4" />
                </a>
              </Button>
            </div>
          )}
        </div>
      </CardContent>
    </Card>
  );
}

export function WebcamLinks() {
  const t = useTranslations('forecast');

  return (
    <Card>
      <CardHeader>
        <CardTitle className="flex items-center gap-2">
          <Video className="h-5 w-5 text-red-500" />
          {t('webcams.title')}
        </CardTitle>
        <CardDescription>
          {t('webcams.description')}
        </CardDescription>
      </CardHeader>
      <CardContent>
        <div className="grid gap-3">
          {WEBCAMS.map((webcam) => (
            <a
              key={webcam.id}
              href={webcam.externalUrl}
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center justify-between rounded-lg border p-3 transition-colors hover:bg-muted"
            >
              <div>
                <p className="font-medium">{webcam.name}</p>
                <p className="text-sm text-muted-foreground">{webcam.description}</p>
              </div>
              <ExternalLink className="h-4 w-4 text-muted-foreground" />
            </a>
          ))}
        </div>
      </CardContent>
    </Card>
  );
}
