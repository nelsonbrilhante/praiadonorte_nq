// API Response Types

export type Locale = 'pt' | 'en';

export interface I18nField {
  pt: string;
  en: string;
}

// Noticia
export interface Noticia {
  id: number;
  title: I18nField;
  slug: string;
  content: I18nField;
  excerpt: I18nField | null;
  cover_image: string | null;
  author: string | null;
  category: string | null;
  entity: 'praia-norte' | 'carsurf' | 'nazare-qualifica';
  tags: string[];
  featured: boolean;
  published_at: string;
  seo_title: I18nField | null;
  seo_description: I18nField | null;
  created_at: string;
  updated_at: string;
}

// Evento
export interface Evento {
  id: number;
  title: I18nField;
  slug: string;
  description: I18nField | null;
  start_date: string;
  end_date: string | null;
  location: string | null;
  entity: 'praia-norte' | 'carsurf' | 'nazare-qualifica';
  image: string | null;
  ticket_url: string | null;
  featured: boolean;
  created_at: string;
  updated_at: string;
}

// Surfer
export interface Surfer {
  id: number;
  name: string;
  slug: string;
  bio: I18nField | null;
  photo: string | null;
  nationality: string | null;
  achievements: Array<{ pt: string; en: string }>;
  social_media: Record<string, string> | null;
  featured: boolean;
  order: number;
  surfboards?: Surfboard[];
  created_at: string;
  updated_at: string;
}

// Surfboard
export interface Surfboard {
  id: number;
  surfer_id: number;
  brand: string;
  model: string | null;
  length: string | null;
  image: string | null;
  specs: Record<string, string> | null;
  order: number;
  created_at: string;
  updated_at: string;
}

// Pagina
export interface Pagina {
  id: number;
  title: I18nField;
  slug: string;
  content: I18nField | CarsurfLandingContent | HomepageContent | null;
  video_url: string | null;
  entity: 'praia-norte' | 'carsurf' | 'nazare-qualifica';
  seo_title: I18nField | null;
  seo_description: I18nField | null;
  published: boolean;
  created_at: string;
  updated_at: string;
}

// Carsurf Landing Page Content (structured)
export interface CarsurfLandingContent {
  pt: CarsurfLandingSection;
  en: CarsurfLandingSection;
}

export interface CarsurfLandingSection {
  hero: {
    title: string;
    subtitle: string;
    cta_primary: string;
    cta_secondary: string;
    youtube_url?: string;
  };
  about: {
    title: string;
    text: string;
    highlight: string;
  };
  facilities: CarsurfFacility[];
  activities: CarsurfActivity[];
  team: CarsurfTeamMember[];
  contact: {
    phone: string;
    email: string;
    hours: string;
    address: string;
    maps_url: string;
  };
  partners: {
    text: string;
  };
}

export interface CarsurfFacility {
  name: string;
  description: string;
  icon: string;
  price?: string;
  capacity?: string;
}

export interface CarsurfActivity {
  name: string;
  icon: string;
}

export interface CarsurfTeamMember {
  name: string;
  role: string;
  description: string;
  email: string;
}

// Homepage Content (Praia do Norte)
export interface HomepageContent {
  pt: HomepageSection;
  en: HomepageSection;
}

export interface HomepageSection {
  hero: {
    title: string;
    subtitle: string;
    cta_text: string;
    cta_url: string;
    youtube_url?: string;
  };
}

// Type guard for Homepage content
export function isHomepageContent(content: unknown): content is HomepageContent {
  if (!content || typeof content !== 'object') return false;
  const c = content as Record<string, unknown>;
  return (
    typeof c.pt === 'object' &&
    c.pt !== null &&
    'hero' in (c.pt as object) &&
    !('facilities' in (c.pt as object)) // Distinguir de CarsurfLandingContent
  );
}

// Type guard for Carsurf landing content
export function isCarsurfLandingContent(content: unknown): content is CarsurfLandingContent {
  if (!content || typeof content !== 'object') return false;
  const c = content as Record<string, unknown>;
  return (
    typeof c.pt === 'object' &&
    c.pt !== null &&
    'hero' in (c.pt as object) &&
    'facilities' in (c.pt as object)
  );
}

// Pagination
export interface PaginatedResponse<T> {
  current_page: number;
  data: T[];
  first_page_url: string;
  from: number | null;
  last_page: number;
  last_page_url: string;
  links: Array<{
    url: string | null;
    label: string;
    active: boolean;
  }>;
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number | null;
  total: number;
}

// Helper to get localized field
export function getLocalizedField(field: I18nField | null | undefined, locale: Locale): string {
  if (!field) return '';
  return field[locale] || field.pt || '';
}
