Updated Requirements Summary
1. General Affiliate Program (Customer Referrals) - WALLET CREDIT SYSTEM
Major Change: Instead of percentage-based discounts, use a wallet credit system.
How it works:

When someone uses a referral link and purchases a subscription, the referrer gets a fixed percentage of that subscription amount credited to their wallet
Suggested: 10% of subscription value (e.g., $300 yearly plan = $30 credit)
Credits accumulate in a "Referral Wallet" visible in user dashboard
Credits can only be used on the website for:

Future subscription purchases
Additional modules/features (future expansion)


Cannot be withdrawn to bank account/card
Works for both monthly and yearly subscriptions

Admin configurable:

Referral reward percentage (e.g., 10%, 15%, etc.)
What credits can be used for


2. Creator/Partner Affiliate Program (Influencers)
Registration Flow:

Influencer registers on website (creates regular account - no subscription required)
Admin assigns "Partner/Affiliate" role from admin portal
System auto-generates unique affiliate code and sends to influencer's email
Influencer gets access to user dashboard (NOT admin portal) with:

Their unique affiliate link/code
Commission tracking
Earnings report



Commission Structure:

Influencer earns 10% commission on approved claims (not just submitted)
Commission applies for:

Limited time period (e.g., 1 month from subscription date), OR
Limited number of claims


Important: Commission is based on approved claims, and the 1-month cycle starts from customer's subscription date (not claim submission date)

Payment:

Manual payouts initially (admin handles payment outside platform)
Future: Can automate once flow is stable

Admin configurable:

Commission percentage
Time period or claim count limits


3. Lead Funnel (Pop-up Form)
Trigger: First-time website visitors
Fields:

Required: Name, Email
Optional: Phone number
Number of units (REMOVED to reduce friction)

Post-submission:

Store lead in database/CRM
Provide discount code (20-25%)

Admin features:

View/manage all leads
Configure discount percentage


4. Additional Feature Request
Evidence Portal Enhancement:

Currently supports: Files and images
Add video upload capability
Users can submit videos directly through portal (instead of sending to personal phone)


Technical Implementation Notes
✅ Use existing tables - store all settings in general_settings table
✅ Minimize new tables - work with current structure
✅ Wallet system - create wallet balance field in user table
✅ Keep code concise - fewer files, optimized code
✅ All percentages/amounts configurable from admin dashboard

Key Decisions from Meeting:

✅ Wallet credit system instead of direct discounts
✅ Credits are non-withdrawable, only usable on platform
✅ Influencers register themselves, admin assigns role
✅ Influencers access user dashboard (not admin)
✅ Manual payouts for influencers initially

