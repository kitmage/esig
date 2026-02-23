# WP E-Signature Dashboard Title Filter Shortcode

This standalone plugin adds a shortcode that wraps WP E-Signature's existing `[esig-doc-dashboard]` output and filters cards by title text.

## Shortcode

```text
[esig-doc-dashboard-filtered status="signed" title_keyword="Nexus"]
```

## Attributes

- `status` (optional): passed to `[esig-doc-dashboard]` as-is (e.g. `required`, `optional`, `signed`).
- `title_keyword` (optional): case-insensitive text match against `.esig-ac-title`.

If `title_keyword` is empty, output is identical to `[esig-doc-dashboard]`.

## Requirement

- WP E-Signature Access Control plugin must be active (provides `[esig-doc-dashboard]`).
