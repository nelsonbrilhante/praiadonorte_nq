import { Phone, Mail, Clock, MapPin } from 'lucide-react';
import { Button } from '@/components/ui/button';

interface ContactSectionProps {
  contact: {
    phone: string;
    email: string;
    hours: string;
    address: string;
    maps_url: string;
  };
  partnersText: string;
}

export function ContactSection({ contact, partnersText }: ContactSectionProps) {
  return (
    <section className="bg-gray-900 py-16 text-white md:py-24">
      <div className="container mx-auto px-4">
        <div className="mb-12 text-center">
          <h2 className="mb-4 text-3xl font-bold md:text-4xl">Contacto</h2>
          <p className="mx-auto max-w-2xl text-gray-400">
            Entre em contacto connosco para mais informações
          </p>
        </div>

        <div className="mx-auto grid max-w-4xl gap-8 md:grid-cols-2">
          {/* Contact Info */}
          <div className="space-y-6">
            <div className="flex items-start gap-4">
              <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-performance/20">
                <Phone className="h-5 w-5 text-performance" />
              </div>
              <div>
                <p className="text-sm text-gray-400">Telefone</p>
                <a
                  href={`tel:${contact.phone.replace(/\s/g, '')}`}
                  className="text-lg font-medium hover:text-performance"
                >
                  {contact.phone}
                </a>
              </div>
            </div>

            <div className="flex items-start gap-4">
              <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-performance/20">
                <Mail className="h-5 w-5 text-performance" />
              </div>
              <div>
                <p className="text-sm text-gray-400">Email</p>
                <a
                  href={`mailto:${contact.email}`}
                  className="text-lg font-medium hover:text-performance"
                >
                  {contact.email}
                </a>
              </div>
            </div>

            <div className="flex items-start gap-4">
              <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-performance/20">
                <Clock className="h-5 w-5 text-performance" />
              </div>
              <div>
                <p className="text-sm text-gray-400">Horário</p>
                <p className="text-lg font-medium">{contact.hours}</p>
              </div>
            </div>

            <div className="flex items-start gap-4">
              <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-performance/20">
                <MapPin className="h-5 w-5 text-performance" />
              </div>
              <div>
                <p className="text-sm text-gray-400">Localização</p>
                <p className="text-lg font-medium">{contact.address}</p>
              </div>
            </div>

            <Button
              asChild
              className="mt-4 w-full bg-performance hover:bg-performance/90"
            >
              <a
                href={contact.maps_url}
                target="_blank"
                rel="noopener noreferrer"
              >
                Ver no Google Maps
              </a>
            </Button>
          </div>

          {/* Map Embed or Partners Info */}
          <div className="flex flex-col justify-center">
            <div className="rounded-lg bg-gray-800 p-6">
              <p className="mb-4 text-center text-sm text-gray-400">
                Parceria
              </p>
              <p className="text-center text-gray-300">{partnersText}</p>
              <div className="mt-6 flex justify-center">
                <div className="rounded-lg bg-institutional/20 px-4 py-2">
                  <span className="font-semibold text-institutional">
                    Nazaré Qualifica E.M.
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
