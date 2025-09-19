# Campaign Routing Strategy for LoveSong TikTok Tracking

## Overview
This document outlines the routing strategy for campaign-specific landing pages to optimize TikTok pixel tracking and conversion attribution.

## Core Principles

### 1. URL Structure Philosophy
- **Visible Campaign URLs**: All campaign URLs should be human-readable and visible in the address bar
- **SEO Friendly**: URLs should be descriptive and search-engine optimized
- **Shareable**: Users should be able to copy/share campaign links easily
- **Trackable**: Each URL should provide clear attribution data

### 2. User Flow Priority
1. **Logged-in users** → Skip sales content → Dashboard
2. **Campaign traffic** → Campaign-specific content → Conversion funnel
3. **Organic traffic** → General landing page → Broad appeal

## URL Structure Design

### Main Routes
```
/                           # Main landing page (smart routing)
/dashboard                  # Authenticated user dashboard
/tiktok                     # General TikTok landing page
/tiktok/{campaign}          # Specific campaign landing pages
```

### Campaign URL Examples
```
/tiktok/viral-songs         # Viral TikTok songs campaign
/tiktok/love-songs          # Valentine's/romantic songs
/tiktok/birthday            # Birthday song specials
/tiktok/holiday             # Holiday-themed campaigns
/tiktok/trending            # Trending music styles
/tiktok/summer2024          # Time-based campaigns
```

### UTM Parameter Integration
```
/tiktok/viral-songs?utm_source=tiktok&utm_campaign=summer2024&utm_content=video1&ad_set=18-25
```

## Main Landing Page (/) Logic

### Smart Routing Decision Tree
```
User visits "/" 
├── Is user logged in?
│   ├── YES → Redirect to /dashboard
│   └── NO → Continue to next check
├── Does URL have UTM parameters?
│   ├── YES → Show campaign-aware landing page with tracking
│   └── NO → Continue to next check
├── Is referrer from TikTok?
│   ├── YES → Show TikTok-optimized general landing page
│   └── NO → Show standard general landing page
```

### Campaign-Aware Main Page
When UTM parameters are present on "/", the main landing page should:
- Track the campaign context in TikTok pixel
- Show campaign-relevant hero content
- Adjust messaging to match campaign theme
- Preserve attribution through conversion funnel

## Campaign-Specific Pages (/tiktok/{campaign})

### Page Purpose
- **Dedicated conversion path** for specific TikTok campaigns
- **Message-match optimization** between TikTok ad and landing page
- **Clean attribution** with no cross-campaign contamination
- **A/B testing capability** for different campaign approaches

### Content Customization Per Campaign
```
/tiktok/viral-songs
├── Hero: "Get Your Song to Go Viral on TikTok"
├── Examples: Viral song success stories
├── CTA: "Make My Viral Hit"
└── Pricing: Viral package emphasis

/tiktok/love-songs
├── Hero: "Create the Perfect Love Song"
├── Examples: Romantic song testimonials
├── CTA: "Write Our Love Story"
└── Pricing: Romance package focus

/tiktok/birthday
├── Hero: "The Ultimate Birthday Surprise"
├── Examples: Birthday song reactions
├── CTA: "Create Birthday Magic"
└── Pricing: Birthday special offers
```

## TikTok Tracking Integration

### Event Tracking Strategy
```
ViewContent Event:
├── Main page (/) → Generic product group view
├── /tiktok → TikTok general interest
└── /tiktok/{campaign} → Campaign-specific content view

InitiateCheckout Event:
├── Triggered when user starts song request process
├── Includes campaign attribution data
└── Preserves source through funnel

AddToCart Event:
├── Triggered on payment page visit
├── Includes full campaign context
└── Pre-conversion signal for TikTok

Purchase Event:
├── Triggered on successful payment
├── Complete attribution data included
└── Conversion optimization signal
```

### Attribution Data Flow
```
TikTok Ad → Campaign Landing Page → Track ViewContent (with campaign)
↓
User Interest → Song Request Form → Track InitiateCheckout (with source)
↓
Payment Intent → Payment Page → Track AddToCart (with attribution)
↓
Payment Success → Conversion → Track Purchase (complete data)
```

## Technical Implementation Notes

### Route Parameters
- **{campaign}** should be validated against allowed campaign names
- **UTM parameters** should be captured and passed through conversion funnel
- **Session storage** may be needed to preserve attribution across auth flows

### Campaign Management
- **Active campaigns** should be easily configurable
- **Inactive campaigns** should redirect to general TikTok page or show "campaign ended" message
- **Default fallback** for unknown campaign names

### Analytics Integration
- **Google Analytics** should receive campaign data
- **TikTok Events Manager** should show clear campaign attribution
- **Internal analytics** should track campaign performance

## Campaign Lifecycle Management

### Campaign States
```
ACTIVE: Campaign is live and accepting traffic
PAUSED: Campaign temporarily disabled (show maintenance message)
ENDED: Campaign completed (redirect to general page or show "ended" message)
DRAFT: Campaign in development (admin-only access)
```

### Campaign Configuration
Each campaign should have:
- **Name/slug** for URL generation
- **Display title** for page content
- **Description** for meta tags and content
- **Status** (active/paused/ended/draft)
- **Start/end dates** for automatic lifecycle management
- **Custom content blocks** for personalization
- **Tracking parameters** for analytics

## Future Enhancements

### Advanced Features
- **Geo-targeting**: Different campaigns per country/region
- **Device targeting**: Mobile vs desktop campaign experiences
- **Time-based routing**: Different campaigns based on time of day/week
- **Personalization**: User behavior-based campaign recommendations

### A/B Testing Framework
- **Multiple variants** per campaign
- **Traffic splitting** for testing
- **Conversion tracking** per variant
- **Statistical significance** monitoring

### Dynamic Campaign Creation
- **Admin interface** for creating new campaigns
- **Template system** for rapid campaign deployment
- **Content management** for non-technical team members

## Success Metrics

### Key Performance Indicators
- **Campaign conversion rate** (visitors to customers)
- **Cost per acquisition** by campaign
- **TikTok pixel optimization** performance
- **User flow completion** rates
- **Cross-campaign performance** comparisons

### Tracking Requirements
- **Campaign attribution** through entire funnel
- **Source medium tracking** (organic vs paid)
- **Device and location** performance
- **Time-based performance** analysis

---

## Notes & Modifications

### Implementation Priority
1. **Phase 1**: Basic campaign routing (/tiktok/{campaign})
2. **Phase 2**: Smart main page routing with UTM handling
3. **Phase 3**: Advanced campaign management and A/B testing

### Team Considerations
- **Marketing team** needs easy campaign creation
- **Development team** needs maintainable code structure
- **Analytics team** needs clean attribution data

### Technical Debt Prevention
- **Consistent naming conventions** for campaigns
- **Centralized configuration** for campaign settings
- **Automated testing** for routing logic
- **Documentation updates** with each new campaign

---

*Last Updated: [Current Date]*
*Status: Draft - Ready for Review and Implementation*
