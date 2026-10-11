<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Cozy Café — Real-Time Order Status Dashboard</title>

    <!-- Google Fonts: Playfair Display for branding & Inter/Instrument Sans for UI -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:600,700|instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF3E8] text-[#302A26] min-h-screen flex flex-col antialiased selection:bg-[#C97852] selection:text-white">

    <!-- Top Mobile Navigation Bar -->
    <header class="lg:hidden bg-[#302A26] text-[#FAF3E8] px-4 py-3 border-b border-[#302A26] flex items-center justify-between sticky top-0 z-40 shadow-md">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-[#C97852] flex items-center justify-center text-white shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 8h1a4 4 0 1 1 0 8h-1"></path>
                    <path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"></path>
                    <line x1="6" y1="2" x2="6" y2="4"></line>
                    <line x1="10" y1="2" x2="10" y2="4"></line>
                    <line x1="14" y1="2" x2="14" y2="4"></line>
                </svg>
            </div>
            <div>
                <h1 class="brand-serif text-lg font-bold tracking-tight text-[#FAF3E8]">Cozy Café</h1>
                <p class="text-[10px] text-[#D6CBC2] -mt-0.5">Real-Time Kitchen Display</p>
            </div>
        </div>

        <button onclick="openNewOrderModal()" class="px-3 py-1.5 bg-[#6F4E37] text-[#FAF3E8] text-xs font-semibold rounded-lg hover:bg-[#5A3F2C] transition-colors shadow flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>New Order</span>
        </button>
    </header>

    <div class="flex-1 flex overflow-hidden">
        <!-- Sidebar Navigation -->
        <aside class="sidebar hidden lg:flex lg:flex-col w-64 border-r border-[#302A26] p-6 justify-between select-none shrink-0 shadow-lg">
            <div>
                <!-- Brand Identity -->
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-[#C97852] flex items-center justify-center text-white shadow-md ring-2 ring-[#E8DCCB]/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 8h1a4 4 0 1 1 0 8h-1"></path>
                            <path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"></path>
                            <line x1="6" y1="2" x2="6" y2="4"></line>
                            <line x1="10" y1="2" x2="10" y2="4"></line>
                            <line x1="14" y1="2" x2="14" y2="4"></line>
                        </svg>
                    </div>
                    <div>
                        <h2 class="brand-serif text-xl font-bold tracking-tight text-[#FAF3E8]">Cozy Café</h2>
                        <p class="text-xs text-[#D6CBC2] italic font-serif">"Good Coffee. Great Moments."</p>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="space-y-1.5">
                    <a href="#dashboard" class="sidebar-link active flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#C97852]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                            <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                            <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                            <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="#orders" class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#D6CBC2]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Orders List</span>
                        <span class="ml-auto text-xs px-2 py-0.5 rounded-full bg-white/10 text-[#FAF3E8]">
                            {{ $stats['total'] }}
                        </span>
                    </a>
                </nav>

                <!-- Café Themed Decorative Detail -->
                <div class="mt-8 p-4 rounded-xl bg-white/5 border border-white/10">
                    <div class="flex items-center gap-2 text-xs font-semibold text-[#C97852] uppercase tracking-wider mb-1">
                        <span>☕ Today's Roast</span>
                    </div>
                    <p class="text-xs text-[#E8DCCB] leading-relaxed">
                        Single-Origin Benguet Arabica with subtle notes of dark chocolate and spiced honey.
                    </p>
                    <div class="mt-3 pt-2 border-t border-white/5 flex items-center justify-between text-[11px] text-[#D6CBC2]">
                        <span>Barista Counter</span>
                        <span class="text-emerald-400 font-medium">● Open</span>
                    </div>
                </div>
            </div>

            <!-- Sidebar Footer / Course Info -->
            <div class="pt-6 border-t border-white/10 text-xs text-[#D6CBC2] space-y-1">
                <p class="font-semibold text-white">IPT 102 — Lab Activity 7</p>
                <p class="text-[11px] text-stone-400">Laravel Reverb & Echo WebSocket</p>
                <form action="{{ route('orders.reset') }}" method="POST" class="pt-2">
                    @csrf
                    <button type="submit" onclick="return confirm('Reset orders to the 5 initial sample records?')" class="w-full text-left text-[11px] text-[#C97852] hover:text-[#FAF3E8] underline cursor-pointer">
                        ↺ Reset Demo Orders
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6">

                <!-- Header Section -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#E8DED2]">
                    <div>
                        <div class="flex items-center gap-3">
                            <h1 class="brand-serif text-2xl sm:text-3xl font-bold text-[#302A26]">Order Dashboard</h1>
                            
                            <!-- WebSocket Connection Indicator -->
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-[#E8DED2] shadow-xs">
                                <span id="connection-dot" class="pulse-dot connecting" title="WebSocket Status"></span>
                                <span id="connection-label" class="text-xs font-semibold text-amber-800">Connecting...</span>
                            </div>
                        </div>
                        <p class="text-sm text-[#85786D] mt-1">
                            Manage today's orders in real time. Connected staff browsers synchronize automatically.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="hidden md:flex flex-col text-right">
                            <span class="text-xs font-semibold text-[#85786D] uppercase tracking-wider">Today</span>
                            <span class="text-xs font-medium text-[#302A26]">{{ now()->format('l, F j, Y') }}</span>
                        </div>

                        <!-- Reset Button for Classroom Demonstration -->
                        <form action="{{ route('orders.reset') }}" method="POST">
                            @csrf
                            <button type="submit" title="Reset sample orders for quick demo" class="px-3.5 py-2 bg-white text-[#6F4E37] text-xs font-medium rounded-lg border border-[#E8DED2] hover:bg-[#FAF3E8] hover:border-[#6F4E37] transition shadow-xs flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                    <path d="M3 3v5h5"></path>
                                </svg>
                                <span>Reset Data</span>
                            </button>
                        </form>

                        <!-- Primary Action: New Order -->
                        <button onclick="openNewOrderModal()" class="px-4 py-2 bg-[#6F4E37] text-white text-sm font-semibold rounded-lg hover:bg-[#5A3F2C] transition-all shadow hover:shadow-md flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>New Order</span>
                        </button>
                    </div>
                </div>

                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-lg flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2">
                            <span>✓</span>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold">&times;</button>
                    </div>
                @endif
                @if (session('info'))
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3 rounded-lg flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2">
                            <span>ℹ</span>
                            <span>{{ session('info') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-amber-600 hover:text-amber-900 font-bold">&times;</button>
                    </div>
                @endif

                <!-- Section: Summary Cards -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Total Orders -->
                    <div class="bg-white rounded-xl p-5 border border-[#E8DED2] shadow-xs hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-[#85786D]">Total Orders</span>
                            <div class="w-8 h-8 rounded-lg bg-[#FAF3E8] flex items-center justify-center text-[#6F4E37]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-2Z"></path>
                                    <path d="M3 6h18"></path>
                                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                                </svg>
                            </div>
                        </div>
                        <p id="stat-total" class="brand-serif text-3xl font-bold text-[#302A26] mt-2">
                            {{ $stats['total'] }}
                        </p>
                        <span class="text-[11px] text-[#85786D] mt-1 block">All registered kitchen tickets</span>
                    </div>

                    <!-- Pending Orders -->
                    <div class="bg-white rounded-xl p-5 border border-[#E8DED2] shadow-xs hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-[#92400E]">Pending</span>
                            <div class="w-8 h-8 rounded-lg bg-[#FEF3C7] flex items-center justify-center text-[#92400E]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                        </div>
                        <p id="stat-pending" class="brand-serif text-3xl font-bold text-[#92400E] mt-2">
                            {{ $stats['pending'] }}
                        </p>
                        <span class="text-[11px] text-[#85786D] mt-1 block">Awaiting barista preparation</span>
                    </div>

                    <!-- Preparing Orders -->
                    <div class="bg-white rounded-xl p-5 border border-[#E8DED2] shadow-xs hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-[#0369A1]">Preparing</span>
                            <div class="w-8 h-8 rounded-lg bg-[#E0F2FE] flex items-center justify-center text-[#0369A1]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2v2"></path>
                                    <path d="M12 20v2"></path>
                                    <path d="m4.93 4.93 1.41 1.41"></path>
                                    <path d="m17.66 17.66 1.41 1.41"></path>
                                    <path d="M2 12h2"></path>
                                    <path d="M20 12h2"></path>
                                    <path d="m6.34 17.66-1.41 1.41"></path>
                                    <path d="m19.07 4.93-1.41 1.41"></path>
                                </svg>
                            </div>
                        </div>
                        <p id="stat-preparing" class="brand-serif text-3xl font-bold text-[#0369A1] mt-2">
                            {{ $stats['preparing'] }}
                        </p>
                        <span class="text-[11px] text-[#85786D] mt-1 block">Currently being brewed / assembled</span>
                    </div>

                    <!-- Ready Orders -->
                    <div class="bg-white rounded-xl p-5 border border-[#E8DED2] shadow-xs hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-[#15803D]">Ready</span>
                            <div class="w-8 h-8 rounded-lg bg-[#DCFCE7] flex items-center justify-center text-[#15803D]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                            </div>
                        </div>
                        <p id="stat-ready" class="brand-serif text-3xl font-bold text-[#15803D] mt-2">
                            {{ $stats['ready'] }}
                        </p>
                        <span class="text-[11px] text-[#85786D] mt-1 block">Ready for customer pickup</span>
                    </div>
                </div>

                <!-- Section: Orders Table & Interactive List -->
                <div class="bg-white rounded-xl border border-[#E8DED2] shadow-xs overflow-hidden">
                    <div class="p-5 border-b border-[#E8DED2] flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h2 class="brand-serif text-lg font-bold text-[#302A26]">Live Orders Queue</h2>
                            <p class="text-xs text-[#85786D]">Real-time synchronization active across all connected screens</p>
                        </div>
                        <div class="text-xs text-[#85786D] flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>WebSockets channel: <strong class="text-[#302A26]">orders</strong></span>
                        </div>
                    </div>

                    <!-- Desktop Orders Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-[#E8DED2]">
                            <thead class="bg-[#FAF3E8]/60 text-left text-xs font-semibold text-[#85786D] uppercase tracking-wider">
                                <tr>
                                    <th scope="col" class="px-6 py-3.5">Order ID</th>
                                    <th scope="col" class="px-6 py-3.5">Customer Name</th>
                                    <th scope="col" class="px-6 py-3.5">Status</th>
                                    <th scope="col" class="px-6 py-3.5">Date Created</th>
                                    <th scope="col" class="px-6 py-3.5">Last Updated</th>
                                    <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="orders-table-body" class="divide-y divide-[#E8DED2] bg-white">
                                @forelse ($orders as $order)
                                    <tr id="order-row-{{ $order->id }}" class="hover:bg-[#FAF3E8]/40 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[#302A26]">
                                            #{{ $order->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#302A26]">
                                            {{ $order->customer_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="badge-status order-status-badge {{ $order->status_badge_class }}">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-[#85786D]">
                                            {{ $order->created_at->format('M d, Y h:i A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-[#85786D] order-updated-at">
                                            {{ $order->updated_at->format('M d, Y h:i A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            <div class="inline-flex items-center gap-2">
                                                <select 
                                                    data-order-id="{{ $order->id }}" 
                                                    data-current-status="{{ $order->status }}"
                                                    class="order-status-select text-xs font-medium rounded-md border border-[#E8DED2] bg-white px-3 py-1.5 text-[#302A26] shadow-xs hover:border-[#6F4E37] focus:border-[#6F4E37] focus:ring-1 focus:ring-[#6F4E37] focus:outline-hidden transition-colors cursor-pointer"
                                                    onchange="handleStatusChange(this)">
                                                    @foreach ($validStatuses as $st)
                                                        <option value="{{ $st }}" @selected($order->status === $st)>{{ $st }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- Handled in empty state -->
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Orders Card List -->
                    <div id="orders-mobile-list" class="md:hidden p-4 space-y-3">
                        @foreach ($orders as $order)
                            <div id="order-card-{{ $order->id }}" class="bg-[#FAF3E8]/30 rounded-xl p-4 border border-[#E8DED2] space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-[#302A26]">#{{ $order->id }}</span>
                                        <span class="text-sm font-semibold text-[#302A26]">{{ $order->customer_name }}</span>
                                    </div>
                                    <span class="badge-status order-status-badge {{ $order->status_badge_class }}">
                                        {{ $order->status }}
                                    </span>
                                </div>
                                <div class="text-xs text-[#85786D] flex justify-between">
                                    <span>Created: {{ $order->created_at->format('h:i A') }}</span>
                                    <span>Updated: <span class="order-updated-at">{{ $order->updated_at->format('h:i A') }}</span></span>
                                </div>
                                <div class="pt-2 border-t border-[#E8DED2]">
                                    <label class="block text-xs font-medium text-[#85786D] mb-1">Update Status:</label>
                                    <select 
                                        data-order-id="{{ $order->id }}" 
                                        data-current-status="{{ $order->status }}"
                                        class="order-status-select w-full text-xs font-medium rounded-md border border-[#E8DED2] bg-white px-3 py-2 text-[#302A26] shadow-xs focus:border-[#6F4E37] focus:ring-1 focus:ring-[#6F4E37] focus:outline-hidden"
                                        onchange="handleStatusChange(this)">
                                        @foreach ($validStatuses as $st)
                                            <option value="{{ $st }}" @selected($order->status === $st)>{{ $st }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Empty State -->
                    <div id="empty-state" class="text-center py-12 px-4 {{ $orders->count() > 0 ? 'hidden' : '' }}">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-[#FAF3E8] border border-[#E8DCCB] flex items-center justify-center text-[#6F4E37] text-2xl">
                            ☕
                        </div>
                        <h3 class="brand-serif text-lg font-bold text-[#302A26]">No Orders Found</h3>
                        <p class="text-sm text-[#85786D] max-w-sm mx-auto mt-1 mb-4">
                            There are currently no orders in the queue. Create the first order or reset the demo data.
                        </p>
                        <div class="flex items-center justify-center gap-3">
                            <button onclick="openNewOrderModal()" class="px-4 py-2 bg-[#6F4E37] text-white text-xs font-semibold rounded-lg hover:bg-[#5A3F2C] transition">
                                Create First Order
                            </button>
                            <form action="{{ route('orders.reset') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-white text-[#6F4E37] border border-[#E8DED2] text-xs font-medium rounded-lg hover:bg-[#FAF3E8] transition">
                                    Load Sample Orders
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Demonstration Instructions Banner -->
                <div class="bg-white rounded-xl p-5 border border-[#E8DED2] shadow-xs">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-[#C97852]/10 text-[#C97852] flex items-center justify-center shrink-0 mt-0.5 font-bold">
                            💡
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-sm font-bold text-[#302A26]">Real-Time Demonstration Guide (Lab Activity 7)</h4>
                            <p class="text-xs text-[#85786D] leading-relaxed">
                                <strong>Browser A:</strong> Change any order status (e.g. from <span class="text-amber-800 font-semibold">Pending</span> to <span class="text-sky-800 font-semibold">Preparing</span>).<br>
                                <strong>Browser B:</strong> Observe that the status badge, dropdown, updated timestamp, and summary cards reflect the change in real-time via <strong>Laravel Reverb</strong> and <strong>Laravel Echo</strong> without refreshing the page!
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- New Order Modal -->
    <div id="new-order-modal" class="fixed inset-0 z-50 bg-[#302A26]/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full border border-[#E8DED2] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="p-6 bg-[#FAF3E8] border-b border-[#E8DED2] flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#6F4E37] text-white flex items-center justify-center">
                        ☕
                    </div>
                    <div>
                        <h3 class="brand-serif text-lg font-bold text-[#302A26]">Place New Order</h3>
                        <p class="text-xs text-[#85786D]">Creates a customer ticket with initial Pending status</p>
                    </div>
                </div>
                <button onclick="closeNewOrderModal()" class="text-[#85786D] hover:text-[#302A26] text-xl font-bold p-1">&times;</button>
            </div>

            <form onsubmit="submitNewOrder(event)" class="p-6 space-y-4">
                <div>
                    <label for="customer_name_input" class="block text-xs font-semibold text-[#302A26] uppercase tracking-wider mb-1.5">
                        Customer Name *
                    </label>
                    <input 
                        type="text" 
                        id="customer_name_input" 
                        name="customer_name" 
                        required 
                        placeholder="e.g. Juan Dela Cruz" 
                        class="w-full text-sm px-3.5 py-2.5 rounded-lg border border-[#E8DED2] bg-white text-[#302A26] placeholder-[#85786D]/60 focus:border-[#6F4E37] focus:ring-1 focus:ring-[#6F4E37] focus:outline-hidden"
                    />
                    <p class="text-[11px] text-[#85786D] mt-1">Order will be initialized as <span class="font-semibold text-amber-800">Pending</span>.</p>
                </div>

                <div class="pt-4 border-t border-[#E8DED2] flex items-center justify-end gap-3">
                    <button type="button" onclick="closeNewOrderModal()" class="px-4 py-2 text-xs font-semibold text-[#85786D] hover:text-[#302A26] rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit" id="submit_order_btn" class="px-5 py-2 bg-[#6F4E37] text-white text-xs font-semibold rounded-lg hover:bg-[#5A3F2C] transition shadow">
                        Create Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="toast-container"></div>

</body>
</html>
