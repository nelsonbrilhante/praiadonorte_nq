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
  content: I18nField | null;
  entity: 'praia-norte' | 'carsurf' | 'nazare-qualifica';
  seo_title: I18nField | null;
  seo_description: I18nField | null;
  published: boolean;
  created_at: string;
  updated_at: string;
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
