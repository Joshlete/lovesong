# Campaign System Project Tracker 🎯

## 📊 Project Status Overview

**Current Phase:** Foundation Setup  
**Started:** [Add Date]  
**Target Completion:** [Add Date]  
**Branch:** `feature/campaign-tracking`

### Quick Status
- [ ] Phase 1: Campaign Routing Foundation
- [ ] Phase 2: UTM Parameter Handling  
- [ ] Phase 3: Enhanced TikTok Pixel Tracking
- [ ] Phase 4: Campaign Management System
- [ ] Phase 5: Testing and Optimization

---

## 🎯 Project Goals

### Primary Objectives
- [ ] Create campaign-specific landing pages for TikTok ads
- [ ] Track campaign attribution through entire conversion funnel
- [ ] Enable TikTok algorithm optimization with clean conversion data
- [ ] Build scalable system for future campaign creation

### Success Metrics
- [ ] Campaign conversion rates measurable per TikTok campaign
- [ ] TikTok Events Manager showing campaign-specific data
- [ ] UTM attribution working through entire user journey
- [ ] Campaign URLs working: `/tiktok/viral-songs`, `/tiktok/love-songs`, etc.

---

## 📋 Detailed Progress Tracker

## Phase 1: Campaign Routing Foundation (Week 1)
**Goal: Get basic campaign URLs working**

### Day 1-2: Route Structure
- [ ] **Task:** Create campaign routes in `routes/web.php`
  - [ ] Add `/tiktok` route (general TikTok landing)
  - [ ] Add `/tiktok/{campaign}` route (specific campaigns)
  - [ ] Test routes work with basic campaigns
  - **Notes:** _[Add your notes here]_
  - **Blockers:** _[Any issues encountered]_

- [ ] **Task:** Create `LandingController` to handle campaign logic
  - [ ] Generate controller: `php artisan make:controller LandingController`
  - [ ] Add `index()` method for general landing
  - [ ] Add `tiktok()` method for campaign handling
  - **Notes:** _[Add your notes here]_

- [ ] **Task:** Set up basic campaign validation
  - [ ] Define allowed campaign names array
  - [ ] Add validation logic for campaign parameter
  - [ ] Create fallback for invalid campaigns
  - **Notes:** _[Add your notes here]_

### Day 3-4: Campaign Landing Page Template
- [ ] **Task:** Create `landing-tiktok.blade.php` template
  - [ ] Copy from existing `landing.blade.php`
  - [ ] Ensure TikTok pixel is inherited
  - [ ] Add campaign variable handling
  - **Notes:** _[Add your notes here]_

- [ ] **Task:** Add campaign-specific content blocks
  - [ ] Create conditional content for different campaigns
  - [ ] Test with: `viral-songs`, `love-songs`, `birthday`
  - [ ] Verify content displays correctly
  - **Notes:** _[Add your notes here]_

- [ ] **Task:** Test campaign URLs
  - [ ] Test: `yoursite.com/tiktok/viral-songs`
  - [ ] Test: `yoursite.com/tiktok/love-songs`
  - [ ] Test: `yoursite.com/tiktok/birthday`
  - [ ] Verify each shows campaign-specific content
  - **Notes:** _[Add your notes here]_

### Day 5: Main Page Smart Routing
- [ ] **Task:** Implement smart routing logic on `/` route
  - [ ] Logged in users → Dashboard redirect
  - [ ] UTM parameters → Campaign-aware content
  - [ ] Default → General landing page
  - **Notes:** _[Add your notes here]_

- [ ] **Task:** Test all routing scenarios
  - [ ] Test logged-in user redirect
  - [ ] Test UTM parameter detection
  - [ ] Test default landing page
  - **Notes:** _[Add your notes here]_

**Phase 1 Complete:** ✅ / ❌  
**Deliverable:** Basic campaign URLs working with proper content

---

## Phase 2: UTM Parameter Handling (Week 2)
**Goal: Capture and preserve UTM data through user journey**

### Day 1-2: UTM Capture System
- [ ] **Task:** Create UTM parameter capture system
  - [ ] Create service/helper for UTM handling
  - [ ] Store UTM data in session
  - [ ] Test UTM persistence across pages
  - **Notes:** _[Add your notes here]_

- [ ] **Task:** Create helper functions for retrieving UTM data
  - [ ] `getUtmSource()`, `getUtmCampaign()`, etc.
  - [ ] `getAllUtmData()` helper
  - [ ] Test helpers return correct data
  - **Notes:** _[Add your notes here]_

- [ ] **Task:** Add UTM data to user registration/song request forms
  - [ ] Hidden fields in registration form
  - [ ] Include UTM data in song request model
  - [ ] Test data saves correctly
  - **Notes:** _[Add your notes here]_

### Day 3-4: Campaign Content Customization
- [ ] **Task:** Create campaign configuration system
  - [ ] Define campaign data structure
  - [ ] Create campaign config file/array
  - [ ] Test configuration loading
  - **Notes:** _[Add your notes here]_

- [ ] **Task:** Add campaign-specific content
  - [ ] Campaign-specific hero sections
  - [ ] Custom CTAs per campaign
  - [ ] Campaign testimonials/examples
  - **Notes:** _[Add your notes here]_

### Day 5: UTM Integration Testing
- [ ] **Task:** Test UTM parameter flow through entire funnel
  - [ ] Landing page → Registration → Song request → Payment
  - [ ] Verify UTM data preserved at each step
  - [ ] Test with different UTM combinations
  - **Notes:** _[Add your notes here]_

**Phase 2 Complete:** ✅ / ❌  
**Deliverable:** UTM data captured and used for personalization

---

## Phase 3: Enhanced TikTok Pixel Tracking (Week 3)
**Goal: Add campaign-specific tracking events**

### Day 1-2: Campaign-Aware ViewContent Events
- [ ] **Task:** Enhance landing page TikTok tracking with campaign data
  - [ ] Add campaign info to ViewContent events
  - [ ] Include UTM parameters in tracking
  - [ ] Test in browser console
  - **Notes:** _[Add your notes here]_

- [ ] **Task:** Test tracking in TikTok Events Manager
  - [ ] Visit campaign pages with pixel helper
  - [ ] Verify events appear in TikTok dashboard
  - [ ] Check campaign attribution data
  - **Notes:** _[Add your notes here]_

### Day 3-4: Conversion Funnel Tracking
- [ ] **Task:** Add InitiateCheckout tracking to song request creation
  - [ ] Use `<x-tik-tok-pixel>` component
  - [ ] Include campaign and UTM data
  - [ ] Test event fires correctly
  - **Notes:** _[Add your notes here]_

- [ ] **Task:** Add AddToCart tracking to payment page
  - [ ] Track when users reach payment page
  - [ ] Include song request value and campaign data
  - [ ] Test with real payment flow
  - **Notes:** _[Add your notes here]_

- [ ] **Task:** Enhance Purchase tracking with campaign attribution
  - [ ] Update payment success tracking
  - [ ] Include full campaign attribution chain
  - [ ] Test conversion events
  - **Notes:** _[Add your notes here]_

### Day 5: Advanced Event Parameters
- [ ] **Task:** Add custom event parameters
  - [ ] Campaign, utm_source, utm_campaign, etc.
  - [ ] Value tracking for conversion events
  - [ ] Content_id and content_name for attribution
  - **Notes:** _[Add your notes here]_

**Phase 3 Complete:** ✅ / ❌  
**Deliverable:** Full conversion funnel tracked with campaign attribution

---

## Phase 4: Campaign Management System (Week 4)
**Goal: Easy campaign creation and management**

### Day 1-2: Campaign Configuration
- [ ] **Task:** Create campaign configuration system
  - [ ] Database table or config file for campaigns
  - [ ] Campaign status (active/paused/ended)
  - [ ] Campaign metadata (title, description, etc.)
  - **Notes:** _[Add your notes here]_

### Day 3-4: Campaign Content Management
- [ ] **Task:** Create campaign content templates
  - [ ] Reusable campaign content blocks
  - [ ] Easy way to add new campaigns
  - [ ] Campaign-specific offers/pricing
  - **Notes:** _[Add your notes here]_

### Day 5: Documentation
- [ ] **Task:** Create campaign creation guide
  - [ ] How to add new campaigns
  - [ ] UTM parameter conventions
  - [ ] Testing checklist
  - **Notes:** _[Add your notes here]_

**Phase 4 Complete:** ✅ / ❌  
**Deliverable:** Complete campaign management system

---

## Phase 5: Testing and Optimization (Week 5)
**Goal: Ensure everything works perfectly in production**

### Testing Checklist
- [ ] **Campaign URLs:** All campaign URLs load correctly
- [ ] **TikTok Pixel:** Pixel fires on all campaign pages
- [ ] **UTM Parameters:** UTM data captured and preserved
- [ ] **Conversion Tracking:** Full funnel tracking works
- [ ] **Mobile Experience:** Campaign pages work on mobile
- [ ] **Cross-Browser:** Works in Chrome, Safari, Firefox
- [ ] **Performance:** Page load times acceptable
- [ ] **Analytics:** Data appears in TikTok Events Manager

### Production Deployment
- [ ] **Staging:** Deploy to staging environment
- [ ] **Testing:** Run full test suite
- [ ] **Production:** Deploy to production
- [ ] **Monitoring:** Monitor TikTok Events Manager
- [ ] **Verification:** Verify analytics integration

**Phase 5 Complete:** ✅ / ❌  
**Deliverable:** Fully functional campaign system in production

---

## 🚀 Quick Reference

### Campaign URL Structure
```
Main page: yoursite.com/
TikTok general: yoursite.com/tiktok  
Viral songs: yoursite.com/tiktok/viral-songs
Love songs: yoursite.com/tiktok/love-songs
Birthday: yoursite.com/tiktok/birthday
```

### UTM Parameter Examples
```
utm_source=tiktok
utm_medium=video_ad
utm_campaign=viral_songs_summer2024
utm_content=jenny_dance_video
```

### TikTok Events to Track
```
ViewContent: Landing page visits
InitiateCheckout: Song request creation
AddToCart: Payment page visits  
Purchase: Successful payments
```

### Files to Create/Modify
```
routes/web.php (campaign routes)
app/Http/Controllers/LandingController.php (new)
resources/views/landing-tiktok.blade.php (new)
app/Services/UtmService.php (new, optional)
```

---

## 📝 Daily Notes & Progress

### [Date] - Day X
**What I worked on:**
- 

**What I completed:**
- 

**What's next:**
- 

**Issues/Blockers:**
- 

**Testing completed:**
- 

---

### [Date] - Day X
**What I worked on:**
- 

**What I completed:**
- 

**What's next:**
- 

**Issues/Blockers:**
- 

**Testing completed:**
- 

---

## 🎯 Key Decisions Made

### [Date] - Decision 1
**Decision:** 
**Rationale:** 
**Impact:** 

### [Date] - Decision 2
**Decision:** 
**Rationale:** 
**Impact:** 

---

## 📞 Resources & Links

### Documentation
- [Campaign Routing Strategy](./CAMPAIGN_ROUTING_STRATEGY.md)
- [Implementation Timeline](./IMPLEMENTATION_TIMELINE.md)

### External Resources
- TikTok Events Manager: [Link]
- TikTok Pixel Helper: [Chrome Extension]
- Google Analytics: [Link]

### Team Contacts
- Marketing: [Contact]
- Analytics: [Contact]

---

## 🏁 Project Completion

### Final Checklist
- [ ] All campaign URLs working in production
- [ ] TikTok pixel tracking campaign attribution correctly
- [ ] UTM parameters preserved through conversion funnel
- [ ] Marketing team trained on campaign creation
- [ ] Documentation updated and complete
- [ ] Analytics integration verified
- [ ] Performance monitoring in place

### Post-Launch Monitoring (First 30 Days)
- [ ] Week 1: Daily monitoring of TikTok Events Manager
- [ ] Week 2: Review campaign conversion rates
- [ ] Week 3: Optimize based on performance data
- [ ] Week 4: Document lessons learned and improvements

**Project Status:** 🚧 In Progress / ✅ Complete  
**Final Notes:** _[Add final project notes here]_

---

*Last Updated: [Current Date]*  
*Project Lead: [Your Name]*  
*Branch: feature/tiktok-pixel-campaigns*
