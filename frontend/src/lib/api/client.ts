import type {
  Noticia,
  Evento,
  Surfer,
  Pagina,
  PaginatedResponse,
} from './types';

const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000';
const API_VERSION = 'v1';

async function fetchAPI<T>(
  endpoint: string,
  options: RequestInit = {}
): Promise<T> {
  const url = `${API_BASE_URL}/api/${API_VERSION}${endpoint}`;

  const response = await fetch(url, {
    ...options,
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...options.headers,
    },
    next: { revalidate: 60 }, // Cache for 60 seconds
  });

  if (!response.ok) {
    throw new Error(`API Error: ${response.status} ${response.statusText}`);
  }

  return response.json();
}

// ============================================
// NOTICIAS
// ============================================

interface NoticiasParams {
  entity?: string;
  category?: string;
  featured?: boolean;
  per_page?: number;
  page?: number;
}

export async function getNoticias(params: NoticiasParams = {}): Promise<PaginatedResponse<Noticia>> {
  const searchParams = new URLSearchParams();
  if (params.entity) searchParams.set('entity', params.entity);
  if (params.category) searchParams.set('category', params.category);
  if (params.featured !== undefined) searchParams.set('featured', params.featured ? '1' : '0');
  if (params.per_page) searchParams.set('per_page', params.per_page.toString());
  if (params.page) searchParams.set('page', params.page.toString());

  const query = searchParams.toString();
  return fetchAPI<PaginatedResponse<Noticia>>(`/noticias${query ? `?${query}` : ''}`);
}

export async function getLatestNoticias(limit: number = 5): Promise<Noticia[]> {
  return fetchAPI<Noticia[]>(`/noticias/latest?limit=${limit}`);
}

export async function getNoticiaBySlug(slug: string): Promise<Noticia> {
  return fetchAPI<Noticia>(`/noticias/${slug}`);
}

// ============================================
// EVENTOS
// ============================================

interface EventosParams {
  entity?: string;
  upcoming?: boolean;
  featured?: boolean;
  per_page?: number;
  page?: number;
}

export async function getEventos(params: EventosParams = {}): Promise<PaginatedResponse<Evento>> {
  const searchParams = new URLSearchParams();
  if (params.entity) searchParams.set('entity', params.entity);
  if (params.upcoming !== undefined) searchParams.set('upcoming', params.upcoming ? '1' : '0');
  if (params.featured !== undefined) searchParams.set('featured', params.featured ? '1' : '0');
  if (params.per_page) searchParams.set('per_page', params.per_page.toString());
  if (params.page) searchParams.set('page', params.page.toString());

  const query = searchParams.toString();
  return fetchAPI<PaginatedResponse<Evento>>(`/eventos${query ? `?${query}` : ''}`);
}

export async function getUpcomingEventos(limit: number = 5): Promise<Evento[]> {
  return fetchAPI<Evento[]>(`/eventos/upcoming?limit=${limit}`);
}

export async function getEventoBySlug(slug: string): Promise<Evento> {
  return fetchAPI<Evento>(`/eventos/${slug}`);
}

// ============================================
// SURFERS
// ============================================

interface SurfersParams {
  featured?: boolean;
  per_page?: number;
  page?: number;
}

export async function getSurfers(params: SurfersParams = {}): Promise<Surfer[]> {
  const searchParams = new URLSearchParams();
  if (params.featured !== undefined) searchParams.set('featured', params.featured ? '1' : '0');
  if (params.per_page) searchParams.set('per_page', params.per_page.toString());
  if (params.page) searchParams.set('page', params.page.toString());

  const query = searchParams.toString();
  return fetchAPI<Surfer[]>(`/surfers${query ? `?${query}` : ''}`);
}

export async function getSurferBySlug(slug: string): Promise<Surfer> {
  return fetchAPI<Surfer>(`/surfers/${slug}`);
}

// ============================================
// PAGINAS
// ============================================

export async function getPagina(entity: string, slug: string): Promise<Pagina> {
  return fetchAPI<Pagina>(`/paginas/${entity}/${slug}`);
}

// ============================================
// SEARCH
// ============================================

export interface SearchResult {
  type: 'noticia' | 'evento' | 'surfer' | 'pagina';
  id: number;
  slug: string;
  title: { pt: string; en: string } | string;
  excerpt?: { pt: string; en: string };
  entity?: string;
  nationality?: string;
  location?: string;
  featured?: boolean;
  url: string;
}

export interface SearchResponse {
  results: SearchResult[];
  total: number;
  query: string;
}

export async function search(query: string, limit: number = 10): Promise<SearchResponse> {
  const searchParams = new URLSearchParams();
  searchParams.set('q', query);
  searchParams.set('limit', limit.toString());

  return fetchAPI<SearchResponse>(`/search?${searchParams.toString()}`);
}
