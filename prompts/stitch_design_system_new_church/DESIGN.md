---
name: Structure & Flow
colors:
  surface: '#FFFFFF'
  surface-dim: '#d6dade'
  surface-bright: '#f6fafe'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f0f4f8'
  surface-container: '#eaeef2'
  surface-container-high: '#e4e9ed'
  surface-container-highest: '#dfe3e7'
  on-surface: '#171c1f'
  on-surface-variant: '#45474c'
  inverse-surface: '#2c3134'
  inverse-on-surface: '#edf1f5'
  outline: '#76777d'
  outline-variant: '#c6c6cd'
  surface-tint: '#565e71'
  primary: '#060e1e'
  on-primary: '#ffffff'
  primary-container: '#1c2434'
  on-primary-container: '#838b9f'
  inverse-primary: '#bec6dc'
  secondary: '#575e70'
  on-secondary: '#ffffff'
  secondary-container: '#d9dff5'
  on-secondary-container: '#5c6274'
  tertiary: '#190c00'
  on-tertiary: '#ffffff'
  tertiary-container: '#361f00'
  on-tertiary-container: '#c27c00'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dbe2f8'
  primary-fixed-dim: '#bec6dc'
  on-primary-fixed: '#131c2b'
  on-primary-fixed-variant: '#3f4758'
  secondary-fixed: '#dce2f7'
  secondary-fixed-dim: '#c0c6db'
  on-secondary-fixed: '#141b2b'
  on-secondary-fixed-variant: '#404758'
  tertiary-fixed: '#ffddb8'
  tertiary-fixed-dim: '#ffb95f'
  on-tertiary-fixed: '#2a1700'
  on-tertiary-fixed-variant: '#653e00'
  background: '#f6fafe'
  on-background: '#171c1f'
  surface-variant: '#dfe3e7'
  semantic-blue: '#3B82F6'
  semantic-green: '#10B981'
  semantic-purple: '#8B5CF6'
typography:
  headline-xl:
    fontFamily: Libre Franklin
    fontSize: 40px
    fontWeight: '700'
    lineHeight: 48px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Libre Franklin
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Libre Franklin
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  body-lg:
    fontFamily: Libre Franklin
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Libre Franklin
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  label-lg:
    fontFamily: Libre Franklin
    fontSize: 15px
    fontWeight: '600'
    lineHeight: 20px
    letterSpacing: 0.01em
  label-md:
    fontFamily: Libre Franklin
    fontSize: 13px
    fontWeight: '500'
    lineHeight: 18px
  button-label:
    fontFamily: Libre Franklin
    fontSize: 17px
    fontWeight: '600'
    lineHeight: 24px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2rem
  container-margin: 24px
  gutter: 16px
---

## Brand & Style

The design system is engineered for professional, data-driven environments where clarity and hierarchy are paramount. The brand personality is **Corporate & Modern**, prioritizing reliability and systematic precision over decorative flair. It bridges the gap between high-utility enterprise software and modern web aesthetics.

The visual style utilizes a **Minimalist** approach with a focus on "Structural Depth"—using solid background blocks and clean containers rather than complex gradients or skeuomorphism. This ensures a focused user experience, reducing cognitive load for complex workflows while maintaining an authoritative presence through bold typography and a deliberate use of high-contrast accents.

## Colors

The palette is anchored by deep, structural neutrals and a high-energy functional accent.

- **Primary & Structure (#1C2434):** Used for foundational elements like sidebar backgrounds and global navigation containers. It provides a heavy "weight" to the layout's periphery.
- **Secondary & Titles (#111827):** Reserved for high-level information architecture, including main page headings and section titles, ensuring maximum legibility.
- **Accent & Buttons (#F59E0B):** A vibrant amber used exclusively for primary actions, active navigation states, and high-priority highlights.
- **Background & Surface:** The main workspace uses a soft grey (#F1F5F9) to reduce eye strain, while interactive content rests on pure white (#FFFFFF) cards to establish clear containment.
- **Semantic Palette:** Standardized blue, green, and purple tokens are used for status indicators, informative badges, and specialized categorization.

## Typography

This design system exclusively utilizes **Libre Franklin**, a robust sans-serif that balances clarity with a professional tone. 

- **Headlines:** Use heavy weights (700) and tighter letter-spacing for large displays to create a strong visual anchor.
- **Body Text:** Optimized for long-form reading in dashboards, using a 1.5x line-height ratio for the standard `body-md` level.
- **Button Labels:** Set at 17px with a semi-bold weight to ensure interactive elements are immediately recognizable and legible against high-contrast backgrounds.
- **Mobile Scaling:** For mobile viewports, `headline-xl` should scale down to 32px and `headline-lg` to 28px to maintain screen efficiency.

## Layout & Spacing

The layout follows a **Fixed-Fluid Hybrid** model. Navigation and sidebars are fixed-width components, while the main workspace area fluidly adapts to the viewport.

- **Grid System:** A 12-column system is used for dashboard layouts with 16px gutters.
- **Component Spacing:** Consistent 8px (space-sm) increments drive the rhythm. Button internal spacing is standardized at 12px vertical and 24px horizontal for a balanced, spacious feel.
- **Breakpoints:** 
  - **Mobile (<768px):** Single column layout, 16px side margins.
  - **Tablet (768px - 1024px):** 6-column grid for cards, 20px margins.
  - **Desktop (>1024px):** 12-column grid, max-width 1440px for content containers, 24px margins.

## Elevation & Depth

This design system employs a **Tonal Layering** approach. Visual hierarchy is communicated through surface color shifts rather than aggressive shadows.

1.  **Level 0 (Floor):** The Main Workspace background (#F1F5F9).
2.  **Level 1 (Surface):** Cards and content modules (#FFFFFF). These use **low-contrast outlines** (1px solid #E2E8F0) instead of shadows to define their boundaries.
3.  **Level 2 (Interactive):** Hover states for cards may utilize a subtle, extra-diffused ambient shadow (0px 4px 12px rgba(28, 36, 52, 0.05)) to suggest "lift."
4.  **Overlays:** Modals and dropdowns use a medium-tinted backdrop blur to maintain context while isolating the foreground task.

## Shapes

The shape language is **Rounded**, favoring a modern and approachable look that softens the "hard" corporate color palette.

- **Standard Elements:** Inputs, cards, and small containers use a 0.5rem (8px) radius.
- **Buttons:** All buttons utilize a 20px pill-shape radius to differentiate them from static containers and emphasize their "clickability."
- **Icons:** Circular containers for icon-only buttons or status avatars should maintain a perfect 50% radius.

## Components

- **Buttons:** 
  - **Primary:** Background #F59E0B, Text #111827 (High Contrast). Pill-shaped (20px).
  - **Secondary:** Background #F1F5F9, Text #1C2434. 
  - **Tertiary:** Transparent background, Text #1C2434, underlined or ghost-bordered on hover.
- **Form Inputs:** 1px border (#E2E8F0), 8px roundedness. Active/Focus state uses a 2px stroke in Semantic Blue (#3B82F6).
- **Cards:** White background, 8px roundedness, 1px light grey border. Padding is fixed at 24px.
- **Status Badges:** Use semantic tokens (Blue/Green/Purple) with 10% opacity backgrounds and 100% opacity text for a "soft" status look.
- **Sidebars:** Use Primary #1C2434 as the fill. Active menu items use Accent #F59E0B as a left-hand "indicator" stroke or background highlight.
- **Checkboxes/Radios:** Use #3B82F6 for selected states; 4px roundedness for checkboxes.