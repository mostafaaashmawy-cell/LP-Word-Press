<?php
/**
 * Template Name: City Gate Luxury Landing Page (Irtkaz)
 * Template Post Type: page
 */

// Avoid header/footer bleed for clean, high-converting standalone canvas
$agency_name = get_option('irtkaz_agency_name', 'Irtkaz');
$default_project = get_option('irtkaz_default_project', 'City Gate New Cairo');
$whatsapp_num = get_option('irtkaz_whatsapp_number', '+201033373331');
$clean_wa_num = preg_replace('/[^0-9]/', '', $whatsapp_num);
$privacy_url = get_option('irtkaz_privacy_url', '#');
$terms_url = get_option('irtkaz_terms_url', '#');

$img_base = IRTKAZ_CRM_URL . 'assets/images/';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
  
  <!-- Google Fonts: Editorial Serif + Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Cormorant+Garamond:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <?php wp_head(); ?>
  
  <style>
    /* Ensure background hero image references plugin assets */
    .hero-section {
      background-image: url('<?php echo esc_url($img_base . 'hero-masterview.jpg'); ?>') !important;
    }
  </style>
</head>
<body <?php body_class('irtkaz-landing-body'); ?>>

  <!-- ==========================================================================
       HERO SECTION (OFFICIAL QATARI DIAR LOGO XL, NO NAVBAR, CLEAN FORM)
       ========================================================================== -->
  <section class="hero-section" id="hero">
    <div class="hero-overlay"></div>
    <div class="container hero-content">

      <!-- LOGOS & BRANDING: OFFICIAL DEVELOPER LOGO + AGENCY BADGE -->
      <div class="hero-top-branding">
        <img src="<?php echo esc_url($img_base . 'qatari-diar-official.png'); ?>" alt="Qatari Diar Egypt Official Logo" class="hero-logo-xl" width="140" height="140" fetchpriority="high">
        <div class="hero-sub-branding">By <span><?php echo esc_html($agency_name); ?></span></div>
      </div>

      <div class="hero-grid">
        <!-- Hero Left Column: Editorial & Value Proposition -->
        <div class="hero-text-block">
          <div class="hero-badge-group">
            <div class="developer-badge">
              <span class="dot"></span>
              NEW RELEASE &bull; NEW CAIRO
            </div>
            <div class="hero-price-badge">
              Starting from 9M EGP
            </div>
          </div>

          <h1 class="hero-title">
            An Integrated City in the Heart of New Cairo
          </h1>

          <p class="hero-subtitle">
            5% Down Payment | Installments Up to 10 Years
          </p>

          <p class="hero-desc">
            Spanning 8.5 million square meters by Qatari Diar, featuring an 18-hole championship golf course, lush expansive parks, and luxury living spaces.
          </p>

          <div class="hero-pills">
            <div class="pill-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
              8.5 Million M² City
            </div>
            <div class="pill-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path></svg>
              18-Hole Championship Golf
            </div>
            <div class="pill-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
              1M M² Ring Park (80% Green)
            </div>
            <div class="pill-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
              5 Mins to AUC New Cairo
            </div>
          </div>
        </div>

        <!-- Hero Right Column: Frosted Glass Lead Capture Form -->
        <div class="hero-form-col">
          <?php 
          echo irtkaz_lead_form_shortcode([
            'project'     => $default_project,
            'agency'      => $agency_name,
            'privacy_url' => $privacy_url,
            'terms_url'   => $terms_url,
          ]); 
          ?>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       SECTION 2: CITY GATE INVENTORY, PRICING & PAYMENT
       ========================================================================== -->
  <section class="section-padding inventory-section" id="inventory">
    <div class="container">
      
      <!-- Section Header with Official City Gate Logo -->
      <div class="section-header">
        <div class="inventory-header-brand">
          <img src="<?php echo esc_url($img_base . 'citygate-official.png'); ?>" alt="City Gate New Cairo Official Logo" class="inventory-logo-citygate" width="240" height="66">
          <span class="section-eyebrow">Project Inventory &amp; Starting Pricing</span>
        </div>
        <h2 class="section-title">City Gate Inventory, Pricing &amp; Payment</h2>
        <p class="section-description">
          Select from City Gate's premier residential collections. Featuring verified starting prices, tailored payment installments, and delivery schedules crafted for discerning buyers.
        </p>
      </div>

      <!-- Categories Grid -->
      <div class="inventory-categories-grid">
        
        <!-- CATEGORY 1: LUXURY APARTMENTS -->
        <article class="inventory-category-card">
          <div class="category-banner-box">
            <img src="<?php echo esc_url($img_base . 'apartment-interior.jpg'); ?>" alt="Luxury Apartments at City Gate New Cairo" loading="lazy" width="600" height="280">
            <div class="category-finishing-badge">FULLY FINISHED &bull; 10 YEARS INSTALLMENTS</div>
          </div>
          <div class="category-body">
            <h3 class="category-title">CATEGORY 1: LUXURY APARTMENTS</h3>
            <p class="category-desc">
              Elegantly appointed fully-finished residences designed for contemporary urban living. Featuring floor-to-ceiling glass and panoramic green vistas.
            </p>

            <!-- Itemized Unit Breakdown List -->
            <div class="unit-breakdown-container">
              <div class="unit-breakdown-item">
                <div class="unit-info-left">
                  <div class="unit-name-title">2 Bedrooms</div>
                  <div class="unit-specs-detail">From 107 SQM Built-up Area</div>
                </div>
                <div class="unit-price-right">
                  <div class="unit-price-label">Starting From</div>
                  <div class="unit-price-value">11.2M EGP</div>
                </div>
              </div>

              <div class="unit-breakdown-item">
                <div class="unit-info-left">
                  <div class="unit-name-title">3 Bedrooms</div>
                  <div class="unit-specs-detail">From 138 SQM Built-up Area</div>
                </div>
                <div class="unit-price-right">
                  <div class="unit-price-label">Starting From</div>
                  <div class="unit-price-value">14.6M EGP</div>
                </div>
              </div>

              <div class="unit-breakdown-item">
                <div class="unit-info-left">
                  <div class="unit-name-title">3 Bedrooms + Maid</div>
                  <div class="unit-specs-detail">160 SQM Built-up Area</div>
                </div>
                <div class="unit-price-right">
                  <div class="unit-price-label">Starting From</div>
                  <div class="unit-price-value">17.4M EGP</div>
                </div>
              </div>
            </div>

            <!-- Payment Terms Box -->
            <div class="payment-terms-box">
              <div class="payment-terms-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                Payment Terms
              </div>
              <div class="payment-terms-content">
                5% Down Payment &bull; 5% after 3 months &bull; 10 Years Equal Installments &bull; Delivery in 4 Years
              </div>
            </div>

            <!-- WhatsApp Green Button -->
            <?php 
            $apt_wa = 'https://wa.me/' . $clean_wa_num . '?text=' . rawurlencode('Hello ' . $agency_name . ', I am inquiring about Luxury Apartments at City Gate (Starting from 11.2M EGP, 10 Years Installments). Please send me unit availability and payment breakdown.');
            ?>
            <a href="<?php echo esc_url($apt_wa); ?>" class="btn-whatsapp-cta" target="_blank" rel="noopener noreferrer">
              <svg viewBox="0 0 448 512" aria-hidden="true" width="18" height="18" fill="currentColor"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
              CONTACT US
            </a>
          </div>
        </article>

        <!-- CATEGORY 2: JADE HILLS SIGNATURE VILLAS -->
        <article class="inventory-category-card">
          <div class="category-banner-box">
            <img src="<?php echo esc_url($img_base . 'villas-exterior.jpg'); ?>" alt="Jade Hills Signature Villas at City Gate New Cairo" loading="lazy" width="600" height="280">
            <div class="category-finishing-badge">CORE &amp; SHELL &bull; 8 YEARS INSTALLMENTS</div>
          </div>
          <div class="category-body">
            <h3 class="category-title">CATEGORY 2: JADE HILLS SIGNATURE VILLAS</h3>
            <p class="category-desc">
              Grand private estates overlooking the championship golf course and vast parks, combining Mediterranean grandeur with bespoke exterior architecture.
            </p>

            <!-- Itemized Unit Breakdown List -->
            <div class="unit-breakdown-container">
              <div class="unit-breakdown-item">
                <div class="unit-info-left">
                  <div class="unit-name-title">Standalone Villas (V36 / V43)</div>
                  <div class="unit-specs-detail">BUA 356m² &bull; Land 524m² | Golf &amp; Park Views</div>
                </div>
                <div class="unit-price-right">
                  <div class="unit-price-label">Starting From</div>
                  <div class="unit-price-value">68.9M EGP</div>
                </div>
              </div>

              <div class="unit-breakdown-item">
                <div class="unit-info-left">
                  <div class="unit-name-title">One-Story Golf Villas (GV51)</div>
                  <div class="unit-specs-detail">BUA 565m² &bull; Land up to 931m²</div>
                </div>
                <div class="unit-price-right">
                  <div class="unit-price-label">Starting From</div>
                  <div class="unit-price-value">117.4M EGP</div>
                </div>
              </div>

              <div class="unit-breakdown-item">
                <div class="unit-info-left">
                  <div class="unit-name-title">Signature Master Villas (GV73)</div>
                  <div class="unit-specs-detail">BUA 714m² &bull; Land 1,325m² | 1st Row Golf</div>
                </div>
                <div class="unit-price-right">
                  <div class="unit-price-label">Starting From</div>
                  <div class="unit-price-value">182.4M EGP</div>
                </div>
              </div>
            </div>

            <!-- Payment Terms Box -->
            <div class="payment-terms-box">
              <div class="payment-terms-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                Payment Terms
              </div>
              <div class="payment-terms-content">
                5% Down Payment &bull; 5% after 3 months &bull; 8 Years Equal Installments &bull; Delivery in 4 Years
              </div>
            </div>

            <!-- WhatsApp Green Button -->
            <?php 
            $villa_wa = 'https://wa.me/' . $clean_wa_num . '?text=' . rawurlencode('Hello ' . $agency_name . ', I am inquiring about Jade Hills Signature Villas at City Gate (Starting from 68.9M EGP, 8 Years Installments). Please send me villa options and payment plans.');
            ?>
            <a href="<?php echo esc_url($villa_wa); ?>" class="btn-whatsapp-cta" target="_blank" rel="noopener noreferrer">
              <svg viewBox="0 0 448 512" aria-hidden="true" width="18" height="18" fill="currentColor"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
              CONTACT US
            </a>
          </div>
        </article>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       SECTION 3: AMENITIES (CLEAN COMPACT ICON GRID)
       ========================================================================== -->
  <section class="section-padding amenities-section" id="amenities">
    <div class="container">
      <div class="section-header">
        <span class="section-eyebrow">Master Community Amenities</span>
        <h2 class="section-title">Everything You Need In One Place</h2>
        <p class="section-description">
          A meticulously planned community offering championship sporting, premier education, medical excellence, corporate hubs, and recreation.
        </p>
      </div>

      <div class="amenities-compact-grid">
        <div class="amenity-compact-card">
          <div class="amenity-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path></svg>
          </div>
          <div class="amenity-info">
            <h3>18-Hole Golf</h3>
            <p>Championship USGA standards.</p>
          </div>
        </div>

        <div class="amenity-compact-card">
          <div class="amenity-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
          </div>
          <div class="amenity-info">
            <h3>Golf Clubhouse</h3>
            <p>Pool, spa, gym &amp; lounge.</p>
          </div>
        </div>

        <div class="amenity-compact-card">
          <div class="amenity-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
          </div>
          <div class="amenity-info">
            <h3>1M M² Ring Park</h3>
            <p>80% green parkland.</p>
          </div>
        </div>

        <div class="amenity-compact-card">
          <div class="amenity-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="m4.93 4.93 4.24 4.24"></path><path d="m14.83 9.17 4.24-4.24"></path><path d="m14.83 14.83 4.24 4.24"></path><path d="m9.17 14.83-4.24 4.24"></path></svg>
          </div>
          <div class="amenity-info">
            <h3>Sports Club</h3>
            <p>Elite athletic facilities.</p>
          </div>
        </div>

        <div class="amenity-compact-card">
          <div class="amenity-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
          </div>
          <div class="amenity-info">
            <h3>Business Park</h3>
            <p>720K M² corporate hub.</p>
          </div>
        </div>

        <div class="amenity-compact-card">
          <div class="amenity-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><path d="M3 6h18"></path><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
          </div>
          <div class="amenity-info">
            <h3>Commercial Town</h3>
            <p>300K M² retail &amp; dining.</p>
          </div>
        </div>

        <div class="amenity-compact-card">
          <div class="amenity-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2z"></path><path d="M9 10h6"></path><path d="M12 7v6"></path></svg>
          </div>
          <div class="amenity-info">
            <h3>Luxury Hotels</h3>
            <p>4 international retreats.</p>
          </div>
        </div>

        <div class="amenity-compact-card">
          <div class="amenity-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
          </div>
          <div class="amenity-info">
            <h3>Medical Hospital</h3>
            <p>300-bed international care.</p>
          </div>
        </div>

        <div class="amenity-compact-card">
          <div class="amenity-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
          </div>
          <div class="amenity-info">
            <h3>Premier Schools</h3>
            <p>International campuses.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       SECTION 4: STRATEGIC ACCESSIBILITY / LOCATION
       ========================================================================== -->
  <section class="section-padding location-section" id="location">
    <div class="container">
      <div class="section-header">
        <span class="section-eyebrow">Strategic Accessibility</span>
        <h2 class="section-title">At The Heart of New Cairo</h2>
        <p class="section-description">
          Located 30 km southeast of Cairo, City Gate stands poised as the gateway to Egypt’s New Administrative Capital. Direct access ensures seamless connection across all major arteries.
        </p>
      </div>

      <div class="location-grid">
        <div class="location-points-list">
          <div class="location-point-item">
            <div class="loc-icon-box">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            </div>
            <div class="loc-point-info">
              <h4>5 Minutes to AUC</h4>
              <p>Just a 5-minute drive to The American University in Cairo and premier educational academies.</p>
            </div>
          </div>

          <div class="location-point-item">
            <div class="loc-icon-box">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>
            </div>
            <div class="loc-point-info">
              <h4>30 Minutes to Cairo International Airport</h4>
              <p>Direct highway connections for effortless domestic and international journeys.</p>
            </div>
          </div>

          <div class="location-point-item">
            <div class="loc-icon-box">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
            </div>
            <div class="loc-point-info">
              <h4>Gateway to the New Administrative Capital</h4>
              <p>Positioned along Sheikh Mohammed Bin Zayed road leading directly into the New Capital.</p>
            </div>
          </div>

          <div class="location-point-item">
            <div class="loc-icon-box">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
            </div>
            <div class="loc-point-info">
              <h4>Direct Major Road Links</h4>
              <p>Effortless access to Road 90, Ring Road, Cairo-Suez Highway, and Ain Sokhna Highway.</p>
            </div>
          </div>
        </div>

        <div class="location-visual-card">
          <img src="<?php echo esc_url($img_base . 'golf-championship.jpg'); ?>" alt="City Gate New Cairo Golf Setting" loading="lazy" width="600" height="400">
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       SECTION 5: ABOUT DEVELOPER (QATARI DIAR EGYPT)
       ========================================================================== -->
  <section class="developer-section" id="about">
    <div class="container">
      <div class="dev-box">
        <div class="dev-logo-wrap">
          <img src="<?php echo esc_url($img_base . 'qatari-diar-official.png'); ?>" alt="Qatari Diar Egypt Official Logo" width="160" height="160" loading="lazy">
        </div>
        <h2 class="dev-title">About Qatari Diar Egypt</h2>
        <p class="dev-desc">
          Through continuous innovation and visionary leadership, Qatari Diar Egypt has secured an iconic position at the forefront of international real estate development. With a strategic land bank exceeding 40 million square meters, Qatari Diar creates transformative master communities that embrace the highest international standards of architectural excellence, sustainability, and enduring luxury.
        </p>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       SECTION 6: WHY ADVISORY THROUGH IRTKAZ
       ========================================================================== -->
  <section class="why-advisory-section" id="why-us">
    <div class="container">
      <div class="why-advisory-box">
        <span class="why-advisory-eyebrow">Strategic Real Estate Consultancy</span>
        <h2 class="why-advisory-title">Why Diar Qatari through <?php echo esc_html($agency_name); ?>?</h2>
        
        <div class="why-advisory-text-block">
          <p class="why-advisory-lead">
            We provide 100% unbiased consultations to ensure Diar Qatari is the absolute best match for your investment goals compared to the entire market.
          </p>
          <p class="why-advisory-sub">
            Our experts guide you step-by-step to secure prime units and tailor payment plans that perfectly fit your budget.
          </p>
        </div>

        <div class="why-pillars-grid">
          <div class="why-pillar-item">
            <div class="why-pillar-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            </div>
            <div class="why-pillar-text">
              <h4>100% Unbiased Advisory</h4>
              <p>Objective market comparative analysis to protect and maximize your capital.</p>
            </div>
          </div>

          <div class="why-pillar-item">
            <div class="why-pillar-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <div class="why-pillar-text">
              <h4>Priority Unit Allocation</h4>
              <p>Direct intermediary access to premium first-row golf and park inventories.</p>
            </div>
          </div>

          <div class="why-pillar-item">
            <div class="why-pillar-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
            </div>
            <div class="why-pillar-text">
              <h4>Tailored Financial Structuring</h4>
              <p>Customizing 8 to 10 year payment schedules aligned with your cash flow.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       FOOTER SECTION
       ========================================================================== -->
  <footer class="site-footer">
    <div class="container footer-inner">
      <div class="footer-branding">
        <img src="<?php echo esc_url($img_base . 'qatari-diar-official.png'); ?>" alt="Qatari Diar Egypt Official Logo" class="footer-logo" width="120" height="120" loading="lazy">
        <div class="footer-sub-branding">By <span><?php echo esc_html($agency_name); ?></span></div>
      </div>

      <div class="footer-disclaimer-box">
        <p class="disclaimer-text">
          By submitting, you agree to receive communications from <?php echo esc_html($agency_name); ?> regarding real estate projects. This landing page is operated by <?php echo esc_html($agency_name); ?>, an independent real estate consultancy, and is not the official website of the developer. Prices and availability are subject to change.
        </p>

        <div class="footer-legal-links">
          <a href="<?php echo esc_url($privacy_url); ?>" class="footer-legal-link">Privacy Policy</a>
          <span class="accessibility-separator">•</span>
          <a href="<?php echo esc_url($terms_url); ?>" class="footer-legal-link">Terms &amp; Conditions</a>
        </div>
      </div>

      <div class="footer-signature">
        &copy; <?php echo date('Y'); ?> <?php echo esc_html($agency_name); ?> &mdash; Strategic Partners
      </div>
    </div>
  </footer>

  <!-- Floating WhatsApp Button -->
  <?php echo irtkaz_whatsapp_button_shortcode(['phone' => $whatsapp_num]); ?>

  <?php wp_footer(); ?>
</body>
</html>
