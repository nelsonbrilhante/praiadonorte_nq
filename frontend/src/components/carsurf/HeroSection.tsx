import Link from 'next/link';
import { Button } from '@/components/ui/button';

interface HeroSectionProps {
  title: string;
  subtitle: string;
  ctaPrimary: string;
  ctaSecondary: string;
  locale: string;
  videoUrl?: string;
  youtubeUrl?: string;
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

export function HeroSection({
  title,
  subtitle,
  ctaPrimary,
  ctaSecondary,
  locale,
  videoUrl,
  youtubeUrl,
}: HeroSectionProps) {
  const youtubeVideoId = youtubeUrl ? getYouTubeVideoId(youtubeUrl) : null;
  const hasVideo = videoUrl || youtubeVideoId;

  return (
    <section className="relative min-h-[70vh] overflow-hidden bg-performance text-white md:min-h-[80vh]">
      {/* MP4 Video Background */}
      {videoUrl && !youtubeVideoId && (
        <video
          autoPlay
          muted
          loop
          playsInline
          className="absolute inset-0 h-full w-full object-cover"
        >
          <source src={videoUrl} type="video/mp4" />
        </video>
      )}

      {/* YouTube Video Background */}
      {youtubeVideoId && (
        <div className="absolute inset-0 overflow-hidden">
          <iframe
            src={`https://www.youtube.com/embed/${youtubeVideoId}?autoplay=1&mute=1&loop=1&playlist=${youtubeVideoId}&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1`}
            allow="autoplay; encrypted-media"
            allowFullScreen
            className="pointer-events-none absolute left-1/2 top-1/2 h-[150%] w-[150%] -translate-x-1/2 -translate-y-1/2"
            style={{ border: 'none' }}
          />
        </div>
      )}

      {/* Gradient Overlay */}
      <div className="absolute inset-0 bg-gradient-to-b from-performance/80 via-performance/60 to-performance/80" />

      {/* Fallback pattern (visible when no video) */}
      {!hasVideo && (
        <div className="absolute inset-0 opacity-10">
          <div
            className="h-full w-full"
            style={{
              backgroundImage: `url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")`,
            }}
          />
        </div>
      )}

      {/* Content */}
      <div className="container relative mx-auto flex min-h-[70vh] items-center justify-center px-4 py-24 text-center md:min-h-[80vh]">
        <div>
          <p className="mb-4 text-sm font-semibold uppercase tracking-widest text-white/80">
            Carsurf
          </p>
          <h1 className="mb-6 whitespace-pre-line text-4xl font-bold leading-tight drop-shadow-lg md:text-5xl lg:text-6xl">
            {title}
          </h1>
          <p className="mx-auto mb-10 max-w-2xl text-xl text-white/90 drop-shadow-md md:text-2xl">
            {subtitle}
          </p>
          <div className="flex flex-wrap justify-center gap-4">
            <Button
              asChild
              size="lg"
              className="bg-white text-performance hover:bg-white/90"
            >
              <Link href={`/${locale}/contacto`}>{ctaPrimary}</Link>
            </Button>
            <Button
              asChild
              size="lg"
              variant="outline"
              className="border-white text-white hover:bg-white/10"
            >
              <a href="#facilities">{ctaSecondary}</a>
            </Button>
          </div>
        </div>
      </div>
    </section>
  );
}
