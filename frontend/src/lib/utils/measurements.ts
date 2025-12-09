/**
 * Measurement conversion utilities
 * Converts imperial measurements to metric (European system)
 */

// Conversion constants
const INCHES_TO_CM = 2.54;
const FEET_TO_CM = 30.48;

/**
 * Converts inches to centimeters
 * Handles formats like: "20", "20.5", "20"", "20.5""
 */
export function inchesToCm(value: string): string {
  // Remove the inch symbol and whitespace
  const cleaned = value.replace(/["″'']/g, '').trim();
  const inches = parseFloat(cleaned);

  if (isNaN(inches)) {
    return value; // Return original if can't parse
  }

  const cm = inches * INCHES_TO_CM;
  // Round to 1 decimal place
  return `${cm.toFixed(1)} cm`;
}

/**
 * Converts surfboard length from feet/inches format to centimeters
 * Handles formats like: "9'6"", "9'6", "9' 6"", "10'0""
 */
export function surfboardLengthToCm(value: string): string {
  // Pattern: feet'inches" (e.g., "9'6"", "9'6", "10'0")
  const feetInchesPattern = /(\d+)[''′][\s]?(\d+(?:\.\d+)?)?[""″]?/;
  const match = value.match(feetInchesPattern);

  if (match) {
    const feet = parseInt(match[1], 10);
    const inches = match[2] ? parseFloat(match[2]) : 0;
    const totalCm = (feet * FEET_TO_CM) + (inches * INCHES_TO_CM);
    // Round to nearest whole number for board length
    return `${Math.round(totalCm)} cm`;
  }

  // Try to parse as just inches (e.g., "72"")
  const inchesOnly = /^(\d+(?:\.\d+)?)[""″]?$/;
  const inchMatch = value.match(inchesOnly);

  if (inchMatch) {
    const inches = parseFloat(inchMatch[1]);
    const cm = inches * INCHES_TO_CM;
    return `${Math.round(cm)} cm`;
  }

  // If already in cm or unknown format, return as-is
  if (value.toLowerCase().includes('cm') || value.toLowerCase().includes('m')) {
    return value;
  }

  return value;
}

/**
 * Detects if a measurement is in imperial format
 */
export function isImperialMeasurement(value: string): boolean {
  // Check for feet/inches pattern or inch symbol
  return /[''′""″]/.test(value) || /\d+[''′]\d+/.test(value);
}

/**
 * Converts a spec value to metric if it appears to be imperial
 * Used for width, thickness, and other inch-based measurements
 */
export function convertSpecToMetric(key: string, value: string): string {
  const lowerKey = key.toLowerCase();

  // Keys that are typically in inches
  const inchKeys = ['width', 'thickness', 'largura', 'espessura'];

  // Keys that are typically in feet/inches (board length)
  const lengthKeys = ['length', 'comprimento'];

  // Keys that should not be converted (already metric or not measurements)
  const skipKeys = ['volume', 'tail', 'fins', 'material', 'rabeta', 'quilhas'];

  if (skipKeys.some(k => lowerKey.includes(k))) {
    return value;
  }

  if (lengthKeys.some(k => lowerKey.includes(k))) {
    return surfboardLengthToCm(value);
  }

  if (inchKeys.some(k => lowerKey.includes(k)) || isImperialMeasurement(value)) {
    return inchesToCm(value);
  }

  return value;
}
