# ☕ Cozy Café — Real-Time Event Dashboard: System Documentation & Submission Report

**Course:** IPT 102 – Integrative Programming and Technologies 2  
**Module:** Module 7 – Real-Time Integrative Programming  
**Activity:** Lab Activity 7 – Building a Real-Time Event Dashboard Using WebSockets  
**Submitted By:** Kyle Gacula & Partner  

---

## 1. Project Overview & Objective

The **Cozy Café Real-Time Order Status Dashboard** is a full-stack web application designed to demonstrate the power of event-driven, real-time web architecture. In a fast-paced restaurant or café environment, staff need immediate visibility into order statuses (e.g., from *Pending* to *Preparing* to *Ready*). 

Traditional web applications rely on manual page refreshes or periodic polling (repeated HTTP requests), which causes delays, wastes server bandwidth, and degrades user experience. This project solves that problem by implementing **bidirectional, full-duplex WebSockets**, allowing instant status propagation across all connected browser clients with zero page reloads.

---

## 2. Technologies & Tools Used

| Layer / Role | Technology | Description |
|---|---|---|
| **Backend Framework** | Laravel 11/12 (PHP 8.3) | Handles business logic, database ORM, REST routing, and event dispatching. |
| **Database** | MySQL 8.4 (via Laragon) | Persists order information, customer records, and status timestamps. |
| **ORM** | Laravel Eloquent | Manages relational data mapping and order lifecycle state. |
| **WebSocket Server** | Laravel Reverb (v1.12) | First-party, high-performance WebSocket server running on port `8080`. |
| **Frontend Real-Time Client** | Laravel Echo | JavaScript library subscribing to WebSocket channels and handling incoming events. |
| **WebSocket Transport** | Pusher-js | Underlying client protocol library compatible with Laravel Reverb. |
| **Frontend Styling** | Tailwind CSS + Custom CSS | Responsive Cozy Café visual identity with warm café aesthetics. |
| **Asset Bundler** | Vite | Compiles modern JavaScript and CSS modules. |

---

## 3. How the Real-Time Event Dashboard Works (Architecture Flow)

The integration between standard HTTP requests and WebSockets follows this step-by-step lifecycle:

```
[User Action in Browser A]
         │
         ▼ (HTTP PATCH /orders/{id})
[Laravel Router & OrderController]
         │
         ▼ (Updates MySQL record)
[Database Update Confirmed]
         │
         ▼ (Fires OrderStatusUpdated event)
[Laravel Broadcasting System]
         │
         ▼ (Pushes event payload via socket)
[Laravel Reverb WebSocket Server (Port 8080)]
         │
         ├───────────────────────────────┐
         ▼ (WebSocket Frame)             ▼ (WebSocket Frame)
[Browser A (Echo Listener)]     [Browser B (Echo Listener)]
         │                               │
         ▼                               ▼
[Instant DOM Badge Update]      [Instant DOM Badge Update]
[Stat Counter Recalculation]    [Stat Counter Recalculation]
[Toast Notification Appears]    [Toast Notification Appears]
```

### Key Components Explained:
1. **HTTP Action**: When a barista changes an order status from *Pending* to *Preparing* in Browser A, an asynchronous AJAX `fetch` request (`PATCH /orders/{id}`) is sent to the backend.
2. **Controller Validation & Persistence**: `OrderController` validates that the new status is valid (`Pending`, `Preparing`, `Ready`, or `Completed`). It updates the database record in MySQL.
3. **Event Dispatching**: Only after the database update succeeds, Laravel dispatches the `OrderStatusUpdated` event. This event implements `ShouldBroadcastNow`, broadcasting immediately without requiring an asynchronous queue worker.
4. **Broadcast Payload**: The event packages crucial payload data:
   - Order ID (`id`)
   - Customer Name (`customer_name`)
   - Updated Status (`status`)
   - Formatted Timestamp (`updated_at_formatted`)
   - Live aggregated statistics (`total`, `pending`, `preparing`, `ready`)
5. **Reverb WebSocket Transmission**: Reverb broadcasts this payload over the public `orders` channel to all open socket connections.
6. **Laravel Echo Client Execution**:
   - In Browser B (and Browser A), Echo listens for `.order.status.updated`.
   - The DOM element `#order-row-{id}` is located, the status badge dynamically switches classes and text, the timestamp updates, and the summary cards increment/decrement immediately.
   - A warm café toast notification pops up: *"Order #1 (Juan Dela Cruz) is now Preparing."*

---

## 4. Verification & Testing

- **Automated Feature Tests**: 8 tests covering order creation, validation, database persistence, and event dispatching passed (`php artisan test`).
- **Connection Health Indicator**: An active visual indicator in the header monitors WebSocket connection states (`Live`, `Connecting...`, `Disconnected`).
- **Multi-Client Synchronization**: Tested concurrently in multiple browser windows with instantaneous synchronization.

---

## 5. Submission Details

- **Student:** Kyle Gacula
- **Partner:** [Insert Partner's Full Name Here]
- **Repository:** https://github.com/kyleG18/cozy-cafe
