<?php
/**
 * Theme snippet: Override the WP E-Signature audit-trail logo image + alt text.
 *
 * Add this to your active theme's functions.php (or a custom site plugin).
 */
add_filter('the_content', function ($content) {
    // Only run when the audit-trail logo markup is present.
    if (strpos($content, '//aprv.me/audit-trail') === false) {
        return $content;
    }

    $custom_logo_src = 'https://staging.aspenbehavioral.com/wp-content/uploads/2026/02/Aspen.png';
    $custom_logo_alt = 'Legally signed.';

    // Replace src and alt specifically inside the aprv.me audit-trail logo link.
    $pattern = '~(<a\s+href="//aprv\.me/audit-trail"[^>]*>\s*<img[^>]*\bsrc=")([^"]*)("[^>]*\balt=")([^"]*)("[^>]*>\s*</a>)~i';

    return preg_replace($pattern, '$1' . $custom_logo_src . '$3' . $custom_logo_alt . '$5', $content);
}, 99);
