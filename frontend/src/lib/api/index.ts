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
  // Carsurf Landing types
  CarsurfLandingContent,
  CarsurfLandingSection,
  CarsurfFacility,
  CarsurfActivity,
  CarsurfTeamMember,
  // Homepage types
  HomepageContent,
  HomepageSection,
} from './types';

// Helper functions
export { getLocalizedField, isCarsurfLandingContent, isHomepageContent } from './types';

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
