// Open-Meteo Marine API for Nazaré, Portugal
// Coordinates: 39.6017° N, 9.0686° W (Praia do Norte)

const NAZARE_LATITUDE = 39.6017;
const NAZARE_LONGITUDE = -9.0686;

export interface MarineForecastHourly {
  time: string[];
  wave_height: number[];
  wave_period: number[];
  wave_direction: number[];
  wind_wave_height: number[];
  swell_wave_height: number[];
  swell_wave_direction: number[];
  swell_wave_period: number[];
  ocean_current_velocity: number[];
  ocean_current_direction: number[];
  sea_surface_temperature: number[];
}

export interface MarineForecastDaily {
  time: string[];
  wave_height_max: number[];
  wave_period_max: number[];
  wave_direction_dominant: number[];
}

export interface MarineForecastResponse {
  latitude: number;
  longitude: number;
  timezone: string;
  hourly: MarineForecastHourly;
  daily: MarineForecastDaily;
  hourly_units: {
    wave_height: string;
    wave_period: string;
    wave_direction: string;
  };
  daily_units: {
    wave_height_max: string;
    wave_period_max: string;
    wave_direction_dominant: string;
  };
}

export interface WeatherResponse {
  hourly: {
    time: string[];
    wind_speed_10m: number[];
    wind_direction_10m: number[];
    wind_gusts_10m: number[];
    temperature_2m: number[];
  };
}

export interface CurrentConditions {
  // Wave data
  waveHeight: number;
  wavePeriod: number;
  waveDirection: number;
  swellHeight: number;
  swellDirection: number;
  swellPeriod: number;
  // Wind data
  windSpeed: number;
  windDirection: number;
  windGusts: number;
  // Temperature
  airTemperature: number;
  waterTemperature: number;
  // Currents
  currentVelocity: number;
  currentDirection: number;
  // Meta
  timestamp: string;
}

export interface DailyForecast {
  date: string;
  maxWaveHeight: number;
  maxWavePeriod: number;
  dominantDirection: number;
}

export interface ProcessedForecast {
  current: CurrentConditions;
  daily: DailyForecast[];
  lastUpdated: string;
}

/**
 * Fetch marine forecast data from Open-Meteo API
 * Free API, no key required
 */
export async function getMarineForecast(): Promise<MarineForecastResponse> {
  const params = new URLSearchParams({
    latitude: NAZARE_LATITUDE.toString(),
    longitude: NAZARE_LONGITUDE.toString(),
    hourly: 'wave_height,wave_period,wave_direction,wind_wave_height,swell_wave_height,swell_wave_direction,swell_wave_period,ocean_current_velocity,ocean_current_direction,sea_surface_temperature',
    daily: 'wave_height_max,wave_period_max,wave_direction_dominant',
    timezone: 'Europe/Lisbon',
    forecast_days: '7',
  });

  const response = await fetch(
    `https://marine-api.open-meteo.com/v1/marine?${params.toString()}`,
    { next: { revalidate: 3600 } } // Cache for 1 hour
  );

  if (!response.ok) {
    throw new Error(`Marine API Error: ${response.status}`);
  }

  return response.json();
}

/**
 * Fetch weather data (wind, temperature) from Open-Meteo API
 */
export async function getWeatherForecast(): Promise<WeatherResponse> {
  const params = new URLSearchParams({
    latitude: NAZARE_LATITUDE.toString(),
    longitude: NAZARE_LONGITUDE.toString(),
    hourly: 'wind_speed_10m,wind_direction_10m,wind_gusts_10m,temperature_2m',
    timezone: 'Europe/Lisbon',
    forecast_days: '7',
  });

  const response = await fetch(
    `https://api.open-meteo.com/v1/forecast?${params.toString()}`,
    { next: { revalidate: 3600 } } // Cache for 1 hour
  );

  if (!response.ok) {
    throw new Error(`Weather API Error: ${response.status}`);
  }

  return response.json();
}

/**
 * Fetch both marine and weather data
 */
export async function getFullForecast(): Promise<{ marine: MarineForecastResponse; weather: WeatherResponse }> {
  const [marine, weather] = await Promise.all([
    getMarineForecast(),
    getWeatherForecast(),
  ]);

  return { marine, weather };
}

/**
 * Process raw API data into a more usable format
 */
export function processForecast(
  marine: MarineForecastResponse,
  weather?: WeatherResponse
): ProcessedForecast {
  const now = new Date();
  const currentHourIndex = findCurrentHourIndex(marine.hourly.time, now);
  const weatherHourIndex = weather
    ? findCurrentHourIndex(weather.hourly.time, now)
    : 0;

  const current: CurrentConditions = {
    // Wave data
    waveHeight: marine.hourly.wave_height[currentHourIndex] ?? 0,
    wavePeriod: marine.hourly.wave_period[currentHourIndex] ?? 0,
    waveDirection: marine.hourly.wave_direction[currentHourIndex] ?? 0,
    swellHeight: marine.hourly.swell_wave_height[currentHourIndex] ?? 0,
    swellDirection: marine.hourly.swell_wave_direction?.[currentHourIndex] ?? 0,
    swellPeriod: marine.hourly.swell_wave_period?.[currentHourIndex] ?? 0,
    // Wind data
    windSpeed: weather?.hourly.wind_speed_10m[weatherHourIndex] ?? 0,
    windDirection: weather?.hourly.wind_direction_10m[weatherHourIndex] ?? 0,
    windGusts: weather?.hourly.wind_gusts_10m[weatherHourIndex] ?? 0,
    // Temperature
    airTemperature: weather?.hourly.temperature_2m[weatherHourIndex] ?? 0,
    waterTemperature: marine.hourly.sea_surface_temperature?.[currentHourIndex] ?? 0,
    // Currents
    currentVelocity: marine.hourly.ocean_current_velocity?.[currentHourIndex] ?? 0,
    currentDirection: marine.hourly.ocean_current_direction?.[currentHourIndex] ?? 0,
    // Meta
    timestamp: marine.hourly.time[currentHourIndex] ?? now.toISOString(),
  };

  const daily: DailyForecast[] = marine.daily.time.map((date, index) => ({
    date,
    maxWaveHeight: marine.daily.wave_height_max[index] ?? 0,
    maxWavePeriod: marine.daily.wave_period_max[index] ?? 0,
    dominantDirection: marine.daily.wave_direction_dominant[index] ?? 0,
  }));

  return {
    current,
    daily,
    lastUpdated: new Date().toISOString(),
  };
}

/**
 * Find the index of the current hour in the hourly data
 */
function findCurrentHourIndex(times: string[], now: Date): number {
  const currentTime = now.getTime();
  let closestIndex = 0;
  let closestDiff = Infinity;

  for (let i = 0; i < times.length; i++) {
    const time = new Date(times[i]).getTime();
    const diff = Math.abs(time - currentTime);
    if (diff < closestDiff) {
      closestDiff = diff;
      closestIndex = i;
    }
  }

  return closestIndex;
}

/**
 * Convert wave direction degrees to cardinal direction
 */
export function degreesToCardinal(degrees: number): string {
  const directions = ['N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW'];
  const index = Math.round(degrees / 22.5) % 16;
  return directions[index];
}

/**
 * Get wave condition description based on height
 */
export function getWaveCondition(height: number, locale: 'pt' | 'en' = 'pt'): string {
  const conditions = {
    pt: {
      calm: 'Calmo',
      small: 'Pequenas',
      moderate: 'Moderadas',
      large: 'Grandes',
      veryLarge: 'Muito Grandes',
      giant: 'Gigantes',
    },
    en: {
      calm: 'Calm',
      small: 'Small',
      moderate: 'Moderate',
      large: 'Large',
      veryLarge: 'Very Large',
      giant: 'Giant',
    },
  };

  const c = conditions[locale];

  if (height < 0.5) return c.calm;
  if (height < 1.5) return c.small;
  if (height < 3) return c.moderate;
  if (height < 6) return c.large;
  if (height < 10) return c.veryLarge;
  return c.giant;
}

/**
 * Determine wind type for Praia do Norte (faces West ~270°)
 * Offshore wind = from East (good for surfing)
 * Onshore wind = from West (bad for surfing)
 * Cross-shore = from North/South (variable)
 */
export function getWindType(
  windDirection: number,
  locale: 'pt' | 'en' = 'pt'
): { type: 'offshore' | 'onshore' | 'cross-offshore' | 'cross-onshore'; label: string; quality: 'good' | 'fair' | 'poor' } {
  // Praia do Norte faces West (~270°)
  // Offshore: E (45-135°) - wind blowing from land to sea
  // Onshore: W (225-315°) - wind blowing from sea to land
  // Cross-shore: N/S

  const labels = {
    pt: {
      offshore: 'Offshore (Terral)',
      onshore: 'Onshore (Nortada)',
      'cross-offshore': 'Cross-Offshore',
      'cross-onshore': 'Cross-Onshore',
    },
    en: {
      offshore: 'Offshore',
      onshore: 'Onshore',
      'cross-offshore': 'Cross-Offshore',
      'cross-onshore': 'Cross-Onshore',
    },
  };

  // Normalize direction to 0-360
  const dir = ((windDirection % 360) + 360) % 360;

  // Offshore: 45° - 135° (East)
  if (dir >= 45 && dir < 135) {
    return { type: 'offshore', label: labels[locale].offshore, quality: 'good' };
  }
  // Onshore: 225° - 315° (West)
  if (dir >= 225 && dir < 315) {
    return { type: 'onshore', label: labels[locale].onshore, quality: 'poor' };
  }
  // Cross-offshore: 135° - 225° (South) - slightly offshore component
  if (dir >= 135 && dir < 225) {
    return { type: 'cross-offshore', label: labels[locale]['cross-offshore'], quality: 'fair' };
  }
  // Cross-onshore: 315° - 45° (North) - slightly onshore component
  return { type: 'cross-onshore', label: labels[locale]['cross-onshore'], quality: 'fair' };
}

/**
 * Get wind strength description
 */
export function getWindStrength(speed: number, locale: 'pt' | 'en' = 'pt'): string {
  const strengths = {
    pt: {
      calm: 'Calmo',
      light: 'Fraco',
      moderate: 'Moderado',
      strong: 'Forte',
      veryStrong: 'Muito Forte',
    },
    en: {
      calm: 'Calm',
      light: 'Light',
      moderate: 'Moderate',
      strong: 'Strong',
      veryStrong: 'Very Strong',
    },
  };

  const s = strengths[locale];

  if (speed < 5) return s.calm;
  if (speed < 15) return s.light;
  if (speed < 30) return s.moderate;
  if (speed < 50) return s.strong;
  return s.veryStrong;
}

/**
 * Get surf quality rating based on conditions
 */
export function getSurfQuality(
  waveHeight: number,
  wavePeriod: number,
  windSpeed: number,
  windDirection: number,
  locale: 'pt' | 'en' = 'pt'
): { rating: 1 | 2 | 3 | 4 | 5; label: string } {
  const labels = {
    pt: ['Muito Fraco', 'Fraco', 'Razoável', 'Bom', 'Excelente'],
    en: ['Very Poor', 'Poor', 'Fair', 'Good', 'Excellent'],
  };

  let score = 0;

  // Wave height scoring (0-2 points)
  if (waveHeight >= 1 && waveHeight <= 3) score += 2;
  else if (waveHeight > 3 && waveHeight <= 6) score += 1.5;
  else if (waveHeight > 0.5) score += 1;

  // Wave period scoring (0-2 points)
  if (wavePeriod >= 12) score += 2;
  else if (wavePeriod >= 8) score += 1;

  // Wind scoring (0-1 point)
  const windType = getWindType(windDirection, locale);
  if (windType.quality === 'good' && windSpeed < 25) score += 1;
  else if (windType.quality === 'fair' && windSpeed < 20) score += 0.5;
  else if (windSpeed > 40) score -= 1;

  // Normalize to 1-5 rating
  const rating = Math.max(1, Math.min(5, Math.round(score))) as 1 | 2 | 3 | 4 | 5;

  return {
    rating,
    label: labels[locale][rating - 1],
  };
}
