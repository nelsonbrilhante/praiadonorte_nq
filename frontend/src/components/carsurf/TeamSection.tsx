import { Mail, UserCircle } from 'lucide-react';
import { Card, CardContent } from '@/components/ui/card';
import type { CarsurfTeamMember } from '@/lib/api';

interface TeamSectionProps {
  team: CarsurfTeamMember[];
}

export function TeamSection({ team }: TeamSectionProps) {
  return (
    <section className="py-16 md:py-24">
      <div className="container mx-auto px-4">
        <div className="mb-12 text-center">
          <h2 className="mb-4 text-3xl font-bold md:text-4xl">Equipa</h2>
          <p className="mx-auto max-w-2xl text-muted-foreground">
            Conheça os responsáveis pelo Carsurf
          </p>
        </div>

        <div className="mx-auto grid max-w-3xl gap-6 md:grid-cols-2">
          {team.map((member, index) => (
            <Card
              key={index}
              className="overflow-hidden transition-all hover:shadow-lg"
            >
              <CardContent className="p-6">
                <div className="mb-4 flex items-center gap-4">
                  <div className="flex h-16 w-16 items-center justify-center rounded-full bg-performance/10">
                    <UserCircle className="h-10 w-10 text-performance" />
                  </div>
                  <div>
                    <h3 className="text-lg font-semibold">{member.name}</h3>
                    <p className="text-sm font-medium text-performance">
                      {member.role}
                    </p>
                  </div>
                </div>
                <p className="mb-4 text-sm text-muted-foreground">
                  {member.description}
                </p>
                <a
                  href={`mailto:${member.email}`}
                  className="inline-flex items-center gap-2 text-sm text-performance hover:underline"
                >
                  <Mail className="h-4 w-4" />
                  {member.email}
                </a>
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </section>
  );
}
