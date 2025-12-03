# Design, Style, and Feel Guidelines
## Praia do Norte Unified Platform

**Version:** 1.0
**Based on:** `PLANO_DESENVOLVIMENTO.md` & `concept.txt`

This document outlines the design philosophy, visual identity, and user experience guidelines for the unified platform merging **Praia do Norte**, **Carsurf**, and **Nazaré Qualifica**.

---

## 1. Core Design Philosophy

### "The Wave that Connects"
The design strategy focuses on **Praia do Norte** as the central gravitational element. The global recognition of the "Big Waves" brand serves as the entry point to introduce users to the supporting infrastructure (Carsurf) and the managing municipal entity (Nazaré Qualifica).

*   **Primary Vibe:** Immersive, Impactful, Oceanic.
*   **Secondary Vibe:** Institutional, Professional, Service-Oriented.
*   **Key Challenge:** Balancing diverse content (E-commerce, Municipal Services, High-Performance Sports) without clutter, ensuring the "Praia do Norte" identity remains dominant while respectfully integrating the municipal ecosystem.

---

## 2. User Experience (UX) Strategy

### Cross-Pollination Flow
The interface must subtly guide users between entities without friction.
*   **The "Discovery" Loop:** A user booking a Carsurf gym session should be exposed to Praia do Norte merchandising (e.g., "Gear up for your training").
*   **The "Institutional" Bridge:** A user looking for parking (Nazaré Qualifica) should easily discover the Forte de S. Miguel Arcanjo (PN) as a destination.

### Navigation Structure
*   **Unified Header:** A single navigation bar that distinctively segments the three areas but keeps them accessible.
*   **Contextual Footers:** While the footer is global, it should feature columns dedicated to each entity to reinforce the organizational structure.

---

## 3. Color Palette & Visual Hierarchy

The color system uses a distinct hierarchy to separate the three entities while maintaining visual harmony.

### Primary Brand: Praia do Norte (The Core)
Used for main actions, headers, and the "soul" of the site.
- **Ocean Blue (Primary):** `#0066cc` (Blue 500) - *Main Buttons, Links, Active States*
- **Deep Ocean (Dark):** `#003366` (Blue 900) - *Footer, Dark Backgrounds*
- **Surf Spray (Light):** `#e6f3ff` (Blue 50) - *Background Accents*

### Secondary Brand: Nazaré Qualifica (The Manager)
Used for institutional information, services, and parking.
- **Institutional Orange:** `#ffa500` (Orange 500) - *Highlights, Secondary Actions*
- **Burnt Orange:** `#cc6600` (Orange 900) - *Text Accents*
- **Soft Orange:** `#fff4e6` (Orange 50) - *Service Cards Backgrounds*

### Tertiary Brand: Carsurf (The Facility)
Used for the High-Performance Center, gym, and athlete services.
- **Performance Green:** `#00cc66` (Green 500) - *Success States, Sport-related accents*
- **Forest Green:** `#008844` (Green 900) - *Dark Text*
- **Mint:** `#e6fff5` (Green 50) - *Facility Backgrounds*

### Neutrals
- **Background:** `White` / `Slate-50` (Light sections)
- **Text:** `Slate-900` (Headings), `Slate-500` (Body text)
- **Footer:** `Slate-900` (Dark Theme)

---

## 4. Typography System (Google Fonts)

We have selected a pairing from **Google Fonts** that balances the high-impact nature of extreme sports with the readability required for institutional services and e-commerce.

### Primary/Headings: **Montserrat**
*   **Google Font URL:** `https://fonts.google.com/specimen/Montserrat`
*   **Why:** Montserrat is a geometric sans-serif with a wide range of weights. It captures the modern, urban, and powerful essence of the "Praia do Norte" brand. Its uppercase usage in bold weights is perfect for impactful "Big Wave" headlines.
*   **Usage:**
    *   **Page Titles (Hero):** `Montserrat ExtraBold (800)` or `Black (900)` - *For maximum impact.*
    *   **Section Headers:** `Montserrat Bold (700)` - *Clean and authoritative.*
    *   **UI Labels/Buttons:** `Montserrat SemiBold (600)` - *Clear and legible.*

### Secondary/Body: **Inter**
*   **Google Font URL:** `https://fonts.google.com/specimen/Inter`
*   **Why:** Inter is a variable font explicitly designed for computer screens. It offers the best possible legibility for dense information (institutional texts, product descriptions, user policies) and pairs seamlessly with the geometric structure of Montserrat.
*   **Usage:**
    *   **Body Text:** `Inter Regular (400)` - *For articles, descriptions, and general content.*
    *   **Navigation/Metadata:** `Inter Medium (500)` - *For menu items and meta tags.*
    *   **Captions:** `Inter Regular (400)` - *Small text.*

### Implementation Note
Import the fonts in `src/app/layout.tsx` or via `next/font/google`:

```typescript
import { Inter, Montserrat } from 'next/font/google'

const montserrat = Montserrat({ 
  subsets: ['latin'],
  variable: '--font-montserrat',
  display: 'swap',
})

const inter = Inter({ 
  subsets: ['latin'],
  variable: '--font-inter',
  display: 'swap',
})
```

---

## 5. UI Framework & Components

The interface is built on **Next.js 15**, **Tailwind CSS**, and **shadcn/ui**.

### Component Style
*   **Shape:** Rounded corners (`rounded-md` approx 6px-8px) to feel modern but approachable.
*   **Depth:** Subtle borders (`border-slate-200`) and soft shadows on hover.
*   **Motion:** Fast transitions (`duration-200`) on hover states.

### Key Interface Elements

#### 1. The "Immersive" Header
*   **Behavior:** Sticky top.
*   **Style:** `backdrop-blur` with semi-transparent background (`bg-background/95`).
*   **Interaction:** Dropdown menus (`NavigationMenu`) that organize the three entities' deep links clearly.

#### 2. Hero Sections
*   **Imagery:** Full-width, high-resolution photos of giant waves or surf culture.
*   **Overlay:** Dark gradients (`from-black/70 to-black/30`) to ensure white text is readable.
*   **Typography:** Large display text (`text-5xl` to `text-7xl`) using **Montserrat** to convey the power of the waves.

#### 3. Product & Content Cards
*   **Design:** Clean white cards with borders.
*   **Hover:** Lift effect (`-translate-y-1`) with subtle shadow.
*   **Structure:** Image top, Title (Montserrat), Meta info (Inter), Action button (bottom).

#### 4. Forms & Inputs (Security Focus)
*   **Validation:** Real-time inline validation (Zod) to prevent errors before submission.
*   **Security Visuals:** Explicit indicators of secure connections (padlock icons) near payment and login fields to reassure users.

---

## 6. Imagery Guidelines

*   **Hero Images:** Must be high-impact, emotional, and dark enough to support white text overlays.
*   **Product Images:** Clean, well-lit, ideally on neutral backgrounds or consistent lifestyle settings.
*   **Facility Images:** Wide-angle shots showing space and light (Carsurf/NQ).
*   **Icons:** **Lucide React** icons. Thin stroke (1.5px or 2px), consistent size.

---

## 7. Accessibility & Localization

*   **Languages:** PT (Primary) / EN (Secondary).
*   **Layout:** Flexible UI components that adapt to varying text lengths (e.g., Portuguese words are often longer than English).
*   **Contrast:** High contrast ratios required, especially for the Orange and Green accent colors on white backgrounds.
