import {
  Home,
  Dumbbell,
  HeartPulse,
  Monitor,
  Archive,
  Sofa,
  ClipboardCheck,
  Utensils,
} from 'lucide-react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import type { CarsurfFacility } from '@/lib/api';

interface FacilitiesSectionProps {
  facilities: CarsurfFacility[];
}

const iconMap: Record<string, React.ComponentType<{ className?: string }>> = {
  home: Home,
  dumbbell: Dumbbell,
  'heart-pulse': HeartPulse,
  monitor: Monitor,
  archive: Archive,
  sofa: Sofa,
  'clipboard-check': ClipboardCheck,
  utensils: Utensils,
};

export function FacilitiesSection({ facilities }: FacilitiesSectionProps) {
  return (
    <section id="facilities" className="bg-muted/30 py-16 md:py-24">
      <div className="container mx-auto px-4">
        <div className="mb-12 text-center">
          <h2 className="mb-4 text-3xl font-bold md:text-4xl">
            Instalações
          </h2>
          <p className="mx-auto max-w-2xl text-muted-foreground">
            Tudo o que precisa num único local para treinar, descansar e evoluir
          </p>
        </div>

        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          {facilities.map((facility, index) => {
            const Icon = iconMap[facility.icon] || Home;
            return (
              <Card
                key={index}
                className="group transition-all hover:border-performance hover:shadow-lg"
              >
                <CardHeader className="pb-3">
                  <div className="mb-3 flex h-12 w-12 items-center justify-center rounded-lg bg-performance/10 text-performance transition-colors group-hover:bg-performance group-hover:text-white">
                    <Icon className="h-6 w-6" />
                  </div>
                  <CardTitle className="text-lg">{facility.name}</CardTitle>
                </CardHeader>
                <CardContent>
                  <p className="mb-3 text-sm text-muted-foreground">
                    {facility.description}
                  </p>
                  {(facility.price || facility.capacity) && (
                    <div className="flex flex-wrap gap-2">
                      {facility.price && (
                        <Badge variant="secondary" className="bg-performance/10 text-performance">
                          {facility.price}
                        </Badge>
                      )}
                      {facility.capacity && (
                        <Badge variant="outline">{facility.capacity}</Badge>
                      )}
                    </div>
                  )}
                </CardContent>
              </Card>
            );
          })}
        </div>
      </div>
    </section>
  );
}
