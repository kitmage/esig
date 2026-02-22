<?php
/**
 * Plugin Name: WP E-Signature Dashboard Title Filter Shortcode
 * Description: Adds a shortcode that mirrors [esig-doc-dashboard] and filters results by contract title keyword.
 * Version: 1.0.0
 * Author: Local Customization
 */

if (!defined('ABSPATH')) {
    exit;
}

class Esig_Doc_Dashboard_Title_Filter_Shortcode {

    const SHORTCODE = 'esig-doc-dashboard-filtered';

    public static function init() {
        add_shortcode(self::SHORTCODE, array(__CLASS__, 'render'));
    }

    public static function render($atts = array(), $content = null) {
        $atts = shortcode_atts(
            array(
                'status' => '',
                'title_keyword' => '',
            ),
            $atts,
            self::SHORTCODE
        );

        $status = sanitize_text_field($atts['status']);
        $title_keyword = sanitize_text_field($atts['title_keyword']);

        $base_shortcode = '[esig-doc-dashboard';
        if ($status !== '') {
            $base_shortcode .= ' status="' . esc_attr($status) . '"';
        }
        $base_shortcode .= ']';

        $output = do_shortcode($base_shortcode);

        if ($title_keyword === '' || trim($output) === '') {
            return $output;
        }

        return self::filter_dashboard_cards_by_title($output, $title_keyword);
    }

    private static function filter_dashboard_cards_by_title($html, $keyword) {
        if (!class_exists('DOMDocument')) {
            return $html;
        }

        $wrapped_html = '<div id="esig-dashboard-filter-root">' . $html . '</div>';

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($wrapped_html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $cards = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' esig-access-control-wrap ')]");

        if ($cards instanceof DOMNodeList) {
            $cards_to_remove = array();

            foreach ($cards as $card) {
                $title_node = $xpath->query(".//*[contains(concat(' ', normalize-space(@class), ' '), ' esig-ac-title ')]", $card)->item(0);
                $title = $title_node ? trim($title_node->textContent) : '';

                if ($title === '' || stripos($title, $keyword) === false) {
                    $cards_to_remove[] = $card;
                }
            }

            foreach ($cards_to_remove as $card) {
                if ($card->parentNode) {
                    $card->parentNode->removeChild($card);
                }
            }
        }

        $root = $dom->getElementById('esig-dashboard-filter-root');
        if (!$root) {
            return $html;
        }

        $filtered = '';
        foreach ($root->childNodes as $child) {
            $filtered .= $dom->saveHTML($child);
        }

        return $filtered;
    }
}

add_action('init', array('Esig_Doc_Dashboard_Title_Filter_Shortcode', 'init'));
