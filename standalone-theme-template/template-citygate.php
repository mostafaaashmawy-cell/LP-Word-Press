<?php
/**
 * Template Name: City Gate Landing Page (Standalone)
 * Description: Standalone theme page template matching https://city-gate.properties-s.com/
 */

// If the Irtkaz CRM plugin is active, load its canvas template directly
if (defined('IRTKAZ_CRM_PATH')) {
    include IRTKAZ_CRM_PATH . 'templates/template-landing-page.php';
    return;
}

// Fallback standalone rendering if plugin is not yet activated
get_header();
?>
<div class="irtkaz-standalone-wrap" style="text-align: center; padding: 60px 20px;">
    <h2>City Gate Landing Page</h2>
    <p>Please activate the <strong>Irtkaz Real Estate CRM & Landing Suite</strong> plugin to enjoy the full landing page and lead dashboard.</p>
</div>
<?php
get_footer();
