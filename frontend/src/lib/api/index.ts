// API Types
export type {
  Locale,
  I18nField,
  Noticia,
  Evento,
  Surfer,
  Surfboard,
  Pagina,
  PaginatedResponse,
} from './types';

// Helper functions
export { getLocalizedField } from './types';

// API Client functions
export {
  // Noticias
  getNoticias,
  getLatestNoticias,
  getNoticiaBySlug,
  // Eventos
  getEventos,
  getUpcomingEventos,
  getEventoBySlug,
  // Surfers
  getSurfers,
  getSurferBySlug,
  // Paginas
  getPagina,
  // Search
  search,
} from './client';

// Search types
export type { SearchResult, SearchResponse } from './client';
