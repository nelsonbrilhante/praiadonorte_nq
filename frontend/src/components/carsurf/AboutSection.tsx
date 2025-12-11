interface AboutSectionProps {
  title: string;
  text: string;
  highlight: string;
}

export function AboutSection({ title, text, highlight }: AboutSectionProps) {
  return (
    <section className="py-16 md:py-24">
      <div className="container mx-auto px-4">
        <div className="mx-auto max-w-4xl">
          <div className="mb-8 flex items-center justify-center">
            <div className="h-1 w-12 bg-performance" />
            <span className="mx-4 text-sm font-semibold uppercase tracking-wider text-performance">
              {title}
            </span>
            <div className="h-1 w-12 bg-performance" />
          </div>

          <p className="mb-8 text-center text-lg leading-relaxed text-muted-foreground md:text-xl">
            {text}
          </p>

          <div className="rounded-lg border-l-4 border-performance bg-performance/5 p-6">
            <p className="text-center text-lg font-semibold text-performance md:text-xl">
              {highlight}
            </p>
          </div>
        </div>
      </div>
    </section>
  );
}
