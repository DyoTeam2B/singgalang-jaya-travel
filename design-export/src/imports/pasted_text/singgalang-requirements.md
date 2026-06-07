Project:
Singgalang Jaya Travel System

IMPORTANT:

This project already has an existing design.

Do NOT redesign from scratch.
Do NOT change the current design style.
Do NOT change the existing color palette.
Do NOT change typography.
Do NOT change layout hierarchy.
Do NOT remove existing pages.

Only update the current design to match the latest business requirements below.

==================================================
LATEST BUSINESS REQUIREMENTS
==================================================

1. FIXED TRAVEL FARE

Remove any distance-based fare calculation.

Travel fare is fixed:

Rp150.000 per passenger

Examples:

1 passenger = Rp150.000
2 passengers = Rp300.000
3 passengers = Rp450.000

Leaflet map is NOT used for fare calculation.

Leaflet is only used for:
- Pickup location
- Destination location
- Driver navigation

==================================================
2. PAYMENT RULE
==================================================

Use:

DP = Rp50.000 per booking

Remaining payment is paid directly to driver during the trip.

Update all booking summary pages to use this rule.

Remove:
- Dynamic fare calculation
- Distance pricing
- Percentage DP calculation

==================================================
3. DRIVER AND VEHICLE
==================================================

Vehicle is attached to Driver.

Remove standalone Fleet/Armada management page.

Driver data now contains:

- Driver Name
- Phone Number
- Email
- Password

Vehicle Information:
- Vehicle Name
- License Plate
- Capacity

Default:
Toyota Avanza
Capacity 5 passengers

Replace all separate Armada references with Driver Vehicle Information.

==================================================
4. SCHEDULE MANAGEMENT
==================================================

Add new Admin menu:

Jadwal

Admin can:

- Create Schedule
- Edit Schedule
- Disable Schedule

Schedule fields:

- Route
- Departure Date
- Shift
- Departure Time
- Capacity

Schedule Status:

- Active
- Full
- Inactive

Landing page schedule section must display data from schedules.

==================================================
5. BOOKING FLOW
==================================================

Keep this flow:

Booking Form
↓
Review Booking
↓
Payment
↓
Upload Proof
↓
Booking Status

Never skip any step.

==================================================
6. BOOKING FORM
==================================================

Fields:

- Full Name
- Phone Number
- Schedule
- Passenger Count
- Pickup Location (Leaflet)
- Destination Location (Leaflet)

Remove any fare estimation based on distance.

Fare = 150.000 × Passenger Count

==================================================
7. REVIEW BOOKING
==================================================

Display:

- Booking ID
- Customer Name
- Schedule
- Route
- Pickup Location
- Destination Location
- Passenger Count
- Total Fare
- DP Amount
- Remaining Payment

==================================================
8. BOOKING STATUS
==================================================

Booking Timeline:

Booking Created
↓
Waiting Payment
↓
Waiting Verification
↓
Confirmed
↓
Assigned to Trip
↓
On Trip
↓
Completed

Optional:
Cancelled

==================================================
9. ADMIN SIDEBAR
==================================================

Update sidebar order:

- Dashboard
- Booking
- Payment
- Schedule
- Trip
- Driver
- Reports

Remove:
- Armada

Desktop:
Fixed sidebar

Mobile:
Hamburger drawer

==================================================
10. ADMIN PROFILE
==================================================

Use top navbar right profile dropdown.

Remove profile from sidebar.

Dropdown:

- Profile
- Change Password
- Logout

==================================================
11. TRIP MANAGEMENT
==================================================

Trip must be created from Schedule.

Create Trip:

Fields:

- Schedule
- Driver

Vehicle is automatically taken from selected driver.

Trip cannot be created without driver.

Trip Card:

- Trip ID
- Driver
- Vehicle
- Schedule
- Shift
- Capacity
- Passenger Count
- Status

Actions:

- Add Passenger
- View Manifest
- Edit Trip
- Set Ready
- Cancel Trip

==================================================
12. TRIP HISTORY
==================================================

Separate:

Active Trips

and

Trip History

Completed trips must appear in Trip History.

==================================================
13. DRIVER DASHBOARD
==================================================

Keep current design.

Improve operational flow.

Driver sees immediately:

- Active Trip
- Passenger List
- Leaflet Map

No need to open Manifest first.

==================================================
14. DRIVER FLOW
==================================================

Trip Ready
↓
Start Trip
↓
Pickup Mode
↓
Pick Up All Passengers
↓
Delivery Mode
↓
Drop Off All Passengers
↓
Complete Trip

==================================================
15. PASSENGER CARD
==================================================

Display:

- Passenger Name
- Phone Number
- Pickup Location
- Destination Location
- Total Fare
- DP Paid
- Remaining Payment
- Pickup Status
- Delivery Status

Actions:

- Contact Passenger
- Navigate
- Mark Picked Up
- Mark Dropped Off

==================================================
16. LEAFLET MAP
==================================================

Customer:
- Select Pickup Location
- Select Destination Location

Driver:
Pickup Mode:
- Show Pickup Markers

Delivery Mode:
- Show Destination Markers

Selected passenger should focus map location.

==================================================
17. CHARTER
==================================================

Keep Charter section.

Do not integrate with booking flow.

Charter only shows:

"Hubungi Admin"

via WhatsApp.

==================================================
18. RESPONSIVE
==================================================

Desktop:
>= 1024px

Tablet:
768px - 1023px

Mobile:
< 768px

Requirements:

- Responsive sidebar
- Responsive tables
- Responsive forms
- Responsive cards

==================================================
19. DESIGN SYSTEM
==================================================

Keep existing design system.

Use:

- Poppins Font
- Navy Blue Palette
- White Background
- Rounded Cards
- Soft Shadows
- Traveloka Inspired Style
- Clean Modern UI
- Professional Transportation Management Appearance

==================================================
FINAL INSTRUCTION
==================================================

Do not redesign.

Do not change visual style.

Do not change page flow.

Do not add new business features.

Only synchronize the existing Figma design with these latest business requirements while maintaining consistency across Customer, Admin, and Driver interfaces.