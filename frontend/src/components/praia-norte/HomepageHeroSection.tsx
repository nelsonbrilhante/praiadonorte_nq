import Link from 'next/link';
import Image from 'next/image';
import { Button } from '@/components/ui/button';

interface HomepageHeroSectionProps {
  title: string;
  subtitle: string;
  ctaText: string;
  ctaUrl: string;
  locale: string;
  youtubeUrl?: string;
  fallbackImage?: string;
}

// Extract YouTube video ID from various URL formats
function getYouTubeVideoId(url: string): string | null {
  const patterns = [
    /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/,
    /^([a-zA-Z0-9_-]{11})$/,
  ];

  for (const pattern of patterns) {
    const match = url.match(pattern);
    if (match) return match[1];
  }
  return null;
}

export function HomepageHeroSection({
  title,
  subtitle,
  ctaText,
  ctaUrl,
  locale,
  youtubeUrl,
  fallbackImage = '/pn-ai-wave-hero.png',
}: HomepageHeroSectionProps) {
  const youtubeVideoId = youtubeUrl ? getYouTubeVideoId(youtubeUrl) : null;
  const hasVideo = !!youtubeVideoId;

  return (
    <section className="relative flex min-h-[70vh] flex-col justify-end overflow-hidden text-white">
      {/* YouTube Video Background */}
      {youtubeVideoId && (
        <div className="absolute inset-0 overflow-hidden">
          <iframe
            src={`https://www.youtube.com/embed/${youtubeVideoId}?autoplay=1&mute=1&loop=1&playlist=${youtubeVideoId}&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1&vq=hd1080`}
            allow="autoplay; encrypted-media"
            allowFullScreen
            className="pointer-events-none absolute left-1/2 top-1/2 h-[150%] w-[150%] -translate-x-1/2 -translate-y-1/2"
            style={{ border: 'none' }}
            title="Background video"
          />
        </div>
      )}

      {/* Fallback Image (when no video) */}
      {!hasVideo && (
        <Image
          src={fallbackImage}
          alt="Giant wave at Praia do Norte, Nazare"
          fill
          priority
          className="object-cover"
          sizes="100vw"
        />
      )}

      {/* Content - positioned at bottom */}
      <div className="container relative z-10 mx-auto px-4 pb-12 text-center">
        <h1 className="mb-4 text-5xl font-bold md:text-7xl">
          {title}
        </h1>
        <p className="mb-8 text-xl md:text-2xl">
          {subtitle}
        </p>
        <Button asChild size="lg" className="bg-white text-ocean hover:bg-white/90">
          <Link href={`/${locale}${ctaUrl}`}>{ctaText}</Link>
        </Button>
      </div>
    </section>
  );
}
