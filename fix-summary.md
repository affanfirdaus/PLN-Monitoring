# FIX APPLIED - Search Row Overlap & Table Readability

## Problems Fixed

### Problem 1: Search Row Kepotong
**Root Cause**: padding-top: 0 di .fi-main  
**Fix**: Restored to calc(64px + 10px) = 74px total

### Problem 2: Teks Tabel Tidak Terbaca
**Root Cause**: Light background + light text  
**Fix**: Forced dark text (#0f172a) on light backgrounds

## CSS Added (Lines 1057-1110)

### FIX 1: Prevent Overlap
```css
body.fi-panel-admin-pelayanan {
  --pln-topbar-h: 64px;
}

body.fi-panel-admin-pelayanan .fi-main {
  padding-top: calc(var(--pln-topbar-h) + 10px) !important;
}

body.fi-panel-admin-pelayanan .pln-searchbar,
body.fi-panel-admin-pelayanan .fi-page-header,
body.fi-panel-admin-pelayanan .fi-breadcrumbs {
  margin-top: 10px !important;
}
```

### FIX 2: Table Readability
```css
body.fi-panel-admin-pelayanan .fi-ta th,
body.fi-panel-admin-pelayanan .fi-ta th * {
  color: #0f172a !important;
  opacity: 1 !important;
  font-weight: 600 !important;
}

body.fi-panel-admin-pelayanan .fi-ta td,
body.fi-panel-admin-pelayanan .fi-ta td * {
  color: #0f172a !important;
  opacity: 1 !important;
}
```

## Wrapper Selectors Used
- .pln-searchbar
- .fi-page-header
- .fi-breadcrumbs

## Testing
Ctrl+F5 → http://localhost:8000/internal/admin-pelayanan

Check:
- Search row tidak kepotong
- Table headers gelap dan jelas
- Table rows terbaca
