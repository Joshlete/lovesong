# Campaign System Build Tracker 🎯

**Branch:** `feature/campaign-tracking`  
**Goal:** Build campaign landing pages with UTM tracking

---

## 📊 Progress Overview

- [ ] Phase 1: Campaign Routing Foundation
- [ ] Phase 2: UTM Parameter System
- [ ] Phase 3: Campaign Management
- [ ] Phase 4: Enhanced Tracking (Optional)

---

## Phase 1 — Campaign Routing Foundation

Why this matters
- Create clean, shareable URLs for marketing (e.g., `/tiktok/viral-songs`).
- Ensure logged-in users skip marketing pages and go straight to the app.

What you will build
- Campaign URLs: `/tiktok` and `/tiktok/{campaign}`
- A campaign-aware landing page template
- Smart behavior for `/` (home) to redirect logged-in users

Acceptance criteria
- [ ] `/tiktok/viral-songs`, `/tiktok/love-songs`, `/tiktok/birthday` show different content
- [ ] Logged-in users visiting `/` go to the dashboard
- [ ] Landing pages render quickly on mobile and desktop
- [ ] The existing pixel still loads on these pages (no extra work yet)

Pitfalls to avoid
- Mixing campaign content into the app layout; keep landing pages separate and simple.

---

## Phase 2: UTM Parameter System  
**Goal: Capture and preserve UTM data**

Plain-English explanation
- UTM parameters are short labels in a link that tell you where a visitor came from. Example: `?utm_source=tiktok&utm_campaign=viral_songs&utm_content=video1`.
- You use them to answer: “Which ad brought this person?”

What matters most (only these three to start)
- `utm_source` → where the user came from (tiktok)
- `utm_campaign` → which campaign (viral_songs)
- `utm_content` → which ad/creative (video1)

What you will build
- A simple way to capture UTMs when someone lands
- Store UTMs in session so they survive navigation
- Attach UTMs to key actions (signup, song request, purchase)

Retention policy (short and practical)
- Capture on first page → write to session immediately.
- Strip UTMs from the URL for a clean look (do not rely on the query string).
- Keep two views in session:
  - first_touch: first UTMs seen this session (do not overwrite).
  - last_touch: most recent UTMs if the user arrives from another campaign.
- On conversion (signup/purchase), save both first_touch and last_touch with the record, then you may clear session.
- Optional: set a short cookie (7–30 days) to link returning visits; otherwise session expiry is fine.

Acceptance criteria
- [ ] UTM values are captured on first page load
- [ ] UTMs persist through the funnel (don’t disappear after login)
- [ ] UTMs are saved alongside conversions (so you can see which ad worked)

Pitfalls to avoid
- Don’t try to track everything. Start with 3 UTMs and expand later.

---

## Phase 3 — Campaign Management

Why this matters
- You need a repeatable way to add/turn off campaigns without touching many files.

What you will build
- A small campaign registry/config (list of valid campaign slugs and their settings)
- Content blocks/partials per campaign (hero, CTA, testimonials)
- Graceful behavior for unknown/inactive campaigns (fallback)

Acceptance criteria
- [ ] Adding a new campaign is one edit to a single place (the registry)
- [ ] Each campaign shows its specific content
- [ ] Unknown campaigns do not 404; they redirect or show a safe default

Pitfalls to avoid
- Hardcoding campaign logic across multiple views/controllers.

---

## Phase 4 — Enhanced Tracking (Optional)

Why this is optional now
- The pixel already loads. Enhanced tracking pays off only after the foundation and UTMs are solid.

What you will add
- Pass campaign/UTM context into TikTok events
- Track funnel steps with clarity: ViewContent → InitiateCheckout → AddToCart → Purchase

Acceptance criteria
- [ ] TikTok Events Manager shows events with campaign context
- [ ] You can attribute purchases back to campaign/ad
- [ ] No noisy console logs in production

Pitfalls to avoid
- Over-instrumenting before UTMs are reliable.

---

## Quick Reference (keep handy)

Example campaign URLs
- `/tiktok/viral-songs` → Viral-focused content
- `/tiktok/love-songs` → Romantic/Valentine content
- `/tiktok/birthday` → Birthday surprise content

Minimal UTMs to use
- `utm_source=tiktok`  
- `utm_campaign=viral_songs`  
- `utm_content=video1`

Simple naming rules
- lowercase, hyphen-separated slugs: `viral-songs`, `love-songs`
- use readable campaign names: `viral_songs_summer24` (not `vs24_a1`)

---

Notes
- Current status: [update]
- Next step: [update]
- Issues/risks: [update]
- Completed: [update]

Last updated: [Date]
