import { setRequestLocale } from 'next-intl/server';
import { notFound } from 'next/navigation';
import {
  getPagina,
  type Locale,
  type CarsurfLandingContent,
  isCarsurfLandingContent,
} from '@/lib/api';
import { HeroSection } from '@/components/carsurf/HeroSection';
import { AboutSection } from '@/components/carsurf/AboutSection';
import { FacilitiesSection } from '@/components/carsurf/FacilitiesSection';
import { TeamSection } from '@/components/carsurf/TeamSection';
import { ContactSection } from '@/components/carsurf/ContactSection';

type Props = {
  params: Promise<{ locale: string }>;
};

async function fetchLandingPage() {
  try {
    return await getPagina('carsurf', 'landing');
  } catch (error) {
    console.error('Error fetching Carsurf landing page:', error);
    return null;
  }
}

export default async function CarsurfPage({ params }: Props) {
  const { locale } = await params;
  setRequestLocale(locale);

  const pagina = await fetchLandingPage();

  if (!pagina || !isCarsurfLandingContent(pagina.content)) {
    notFound();
  }

  const content = pagina.content as CarsurfLandingContent;
  const data = content[locale as Locale];

  return (
    <div className="flex flex-col">
      {/* Hero Section */}
      <HeroSection
        title={data.hero.title}
        subtitle={data.hero.subtitle}
        ctaPrimary={data.hero.cta_primary}
        ctaSecondary={data.hero.cta_secondary}
        locale={locale}
        videoUrl={pagina.video_url ? `${process.env.NEXT_PUBLIC_API_URL}/storage/${pagina.video_url}` : undefined}
        youtubeUrl={data.hero.youtube_url}
      />

      {/* About Section */}
      <AboutSection
        title={data.about.title}
        text={data.about.text}
        highlight={data.about.highlight}
      />

      {/* Facilities Section */}
      <FacilitiesSection facilities={data.facilities} />

      {/* Team Section */}
      <TeamSection team={data.team} />

      {/* Contact Section */}
      <ContactSection
        contact={data.contact}
        partnersText={data.partners.text}
      />
    </div>
  );
}
