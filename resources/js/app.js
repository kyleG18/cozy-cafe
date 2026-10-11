import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Determine Reverb connection configuration from environment variables or sensible fallbacks
const reverbKey = import.meta.env.VITE_REVERB_APP_KEY || 'cozycafeappkey12345';
const reverbHost = import.meta.env.VITE_REVERB_HOST || window.location.hostname || '127.0.0.1';
const reverbPort = import.meta.env.VITE_REVERB_PORT ? parseInt(import.meta.env.VITE_REVERB_PORT) : 8080;
const reverbScheme = import.meta.env.VITE_REVERB_SCHEME || 'http';

// Initialize Laravel Echo client connected to Reverb WebSocket server
const echo = new Echo({
    broadcaster: 'reverb',
    key: reverbKey,
    wsHost: reverbHost,
    wsPort: reverbPort,
    wssPort: reverbPort,
    forceTLS: reverbScheme === 'https',
    enabledTransports: ['ws', 'wss'],
});

window.Echo = echo;

// Status styling helper
function getBadgeClass(status) {
    switch (status) {
        case 'Pending':
            return 'badge-pending';
        case 'Preparing':
            return 'badge-preparing';
        case 'Ready':
            return 'badge-ready';
        case 'Completed':
            return 'badge-completed';
        default:
            return 'badge-pending';
    }
}

// Toast notification helper
window.showToast = function (message, icon = '☕') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = 'toast-item';

    const iconSpan = document.createElement('span');
    iconSpan.className = 'text-lg select-none';
    iconSpan.textContent = icon;

    const textSpan = document.createElement('span');
    textSpan.className = 'text-sm font-medium flex-1';
    textSpan.textContent = message;

    const closeBtn = document.createElement('button');
    closeBtn.className = 'text-stone-400 hover:text-white text-xs px-1 select-none';
    closeBtn.innerHTML = '&times;';
    closeBtn.onclick = () => {
        toast.classList.add('hiding');
        setTimeout(() => toast.remove(), 300);
    };

    toast.appendChild(iconSpan);
    toast.appendChild(textSpan);
    toast.appendChild(closeBtn);
    container.appendChild(toast);

    setTimeout(() => {
        if (toast.parentElement) {
            toast.classList.add('hiding');
            setTimeout(() => toast.remove(), 300);
        }
    }, 4000);
};

// Real-Time Connection Indicator updater
function updateConnectionStatus(state) {
    const dot = document.getElementById('connection-dot');
    const label = document.getElementById('connection-label');
    if (!dot || !label) return;

    dot.className = 'pulse-dot';

    if (state === 'connected') {
        dot.classList.add('live');
        label.textContent = 'Live';
        label.className = 'text-xs font-semibold text-emerald-800';
    } else if (state === 'connecting') {
        dot.classList.add('connecting');
        label.textContent = 'Connecting...';
        label.className = 'text-xs font-semibold text-amber-800';
    } else {
        dot.classList.add('disconnected');
        label.textContent = 'Disconnected';
        label.className = 'text-xs font-semibold text-rose-700';
    }
}

// Setup connection listener on Pusher client
if (echo.connector && echo.connector.pusher) {
    const pusher = echo.connector.pusher;
    updateConnectionStatus(pusher.connection.state);

    pusher.connection.bind('state_change', (states) => {
        updateConnectionStatus(states.current);
    });

    pusher.connection.bind('connected', () => updateConnectionStatus('connected'));
    pusher.connection.bind('connecting', () => updateConnectionStatus('connecting'));
    pusher.connection.bind('disconnected', () => updateConnectionStatus('disconnected'));
    pusher.connection.bind('failed', () => updateConnectionStatus('disconnected'));
    pusher.connection.bind('unavailable', () => updateConnectionStatus('disconnected'));
}

// Update summary metrics cards
function updateSummaryStats(stats) {
    if (!stats) return;
    const totalEl = document.getElementById('stat-total');
    const pendingEl = document.getElementById('stat-pending');
    const preparingEl = document.getElementById('stat-preparing');
    const readyEl = document.getElementById('stat-ready');

    if (totalEl && stats.total !== undefined) totalEl.textContent = stats.total;
    if (pendingEl && stats.pending !== undefined) pendingEl.textContent = stats.pending;
    if (preparingEl && stats.preparing !== undefined) preparingEl.textContent = stats.preparing;
    if (readyEl && stats.ready !== undefined) readyEl.textContent = stats.ready;
}

// Handle OrderStatusUpdated event received via WebSocket
function handleOrderStatusUpdated(data) {
    console.log('[Cozy Café Echo] OrderStatusUpdated received:', data);

    const row = document.getElementById(`order-row-${data.id}`);
    const card = document.getElementById(`order-card-${data.id}`);

    // Update table row if present
    if (row) {
        const badge = row.querySelector('.order-status-badge');
        if (badge) {
            badge.textContent = data.status;
            badge.className = `badge-status order-status-badge ${getBadgeClass(data.status)}`;
        }

        const select = row.querySelector('.order-status-select');
        if (select && select.value !== data.status) {
            select.value = data.status;
            select.dataset.currentStatus = data.status;
        }

        const updatedEl = row.querySelector('.order-updated-at');
        if (updatedEl) {
            updatedEl.textContent = data.updated_at_formatted || 'Just now';
        }

        // Apply brief highlight animation
        row.classList.remove('highlight-row');
        void row.offsetWidth; // Force reflow
        row.classList.add('highlight-row');
    }

    // Update mobile card if present
    if (card) {
        const cardBadge = card.querySelector('.order-status-badge');
        if (cardBadge) {
            cardBadge.textContent = data.status;
            cardBadge.className = `badge-status order-status-badge ${getBadgeClass(data.status)}`;
        }

        const cardSelect = card.querySelector('.order-status-select');
        if (cardSelect && cardSelect.value !== data.status) {
            cardSelect.value = data.status;
            cardSelect.dataset.currentStatus = data.status;
        }

        const cardUpdatedEl = card.querySelector('.order-updated-at');
        if (cardUpdatedEl) {
            cardUpdatedEl.textContent = data.updated_at_formatted || 'Just now';
        }

        card.classList.remove('highlight-row');
        void card.offsetWidth;
        card.classList.add('highlight-row');
    }

    // Update summary counts
    if (data.stats) {
        updateSummaryStats(data.stats);
    }

    // Display Cozy Café toast notification
    window.showToast(`Order #${data.id} (${data.customer_name}) is now ${data.status}.`, '🔔');
}

// Build table row HTML for newly created order
function buildOrderRowHtml(data) {
    const badgeClass = getBadgeClass(data.status);
    const dateFormatted = data.created_at_formatted || 'Just now';
    const updatedFormatted = data.updated_at_formatted || 'Just now';

    return `
    <tr id="order-row-${data.id}" class="hover:bg-[#FAF3E8]/40 transition-colors border-b border-[#E8DED2]/80 highlight-row">
        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[#302A26]">
            #${data.id}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#302A26]">
            ${escapeHtml(data.customer_name)}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="badge-status order-status-badge ${badgeClass}">
                ${data.status}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-xs text-[#85786D]">
            ${dateFormatted}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-xs text-[#85786D] order-updated-at">
            ${updatedFormatted}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
            <div class="inline-flex items-center gap-2">
                <select 
                    data-order-id="${data.id}" 
                    data-current-status="${data.status}"
                    class="order-status-select text-xs font-medium rounded-md border border-[#E8DED2] bg-white px-3 py-1.5 text-[#302A26] shadow-sm focus:border-[#6F4E37] focus:ring-1 focus:ring-[#6F4E37] focus:outline-none"
                    onchange="handleStatusChange(this)">
                    <option value="Pending" ${data.status === 'Pending' ? 'selected' : ''}>Pending</option>
                    <option value="Preparing" ${data.status === 'Preparing' ? 'selected' : ''}>Preparing</option>
                    <option value="Ready" ${data.status === 'Ready' ? 'selected' : ''}>Ready</option>
                    <option value="Completed" ${data.status === 'Completed' ? 'selected' : ''}>Completed</option>
                </select>
            </div>
        </td>
    </tr>
    `;
}

// Build mobile card HTML for newly created order
function buildOrderCardHtml(data) {
    const badgeClass = getBadgeClass(data.status);
    const updatedFormatted = data.updated_at_formatted || 'Just now';

    return `
    <div id="order-card-${data.id}" class="bg-white rounded-xl p-4 border border-[#E8DED2] shadow-sm space-y-3 highlight-row">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold text-[#302A26]">#${data.id}</span>
                <span class="text-sm font-semibold text-[#302A26]">${escapeHtml(data.customer_name)}</span>
            </div>
            <span class="badge-status order-status-badge ${badgeClass}">
                ${data.status}
            </span>
        </div>
        <div class="text-xs text-[#85786D] flex justify-between">
            <span>Updated: <span class="order-updated-at">${updatedFormatted}</span></span>
        </div>
        <div class="pt-2 border-t border-[#E8DED2]">
            <label class="block text-xs font-medium text-[#85786D] mb-1">Update Status:</label>
            <select 
                data-order-id="${data.id}" 
                data-current-status="${data.status}"
                class="order-status-select w-full text-xs font-medium rounded-md border border-[#E8DED2] bg-white px-3 py-2 text-[#302A26] shadow-sm focus:border-[#6F4E37] focus:ring-1 focus:ring-[#6F4E37] focus:outline-none"
                onchange="handleStatusChange(this)">
                <option value="Pending" ${data.status === 'Pending' ? 'selected' : ''}>Pending</option>
                <option value="Preparing" ${data.status === 'Preparing' ? 'selected' : ''}>Preparing</option>
                <option value="Ready" ${data.status === 'Ready' ? 'selected' : ''}>Ready</option>
                <option value="Completed" ${data.status === 'Completed' ? 'selected' : ''}>Completed</option>
            </select>
        </div>
    </div>
    `;
}

// Handle OrderCreated event received via WebSocket
function handleOrderCreated(data) {
    console.log('[Cozy Café Echo] OrderCreated received:', data);

    // If order already exists in DOM, skip insertion
    if (document.getElementById(`order-row-${data.id}`)) {
        return;
    }

    // Hide empty state if present
    const emptyState = document.getElementById('empty-state');
    if (emptyState) {
        emptyState.classList.add('hidden');
    }

    const tableBody = document.getElementById('orders-table-body');
    if (tableBody) {
        tableBody.insertAdjacentHTML('afterbegin', buildOrderRowHtml(data));
    }

    const mobileList = document.getElementById('orders-mobile-list');
    if (mobileList) {
        mobileList.insertAdjacentHTML('afterbegin', buildOrderCardHtml(data));
    }

    if (data.stats) {
        updateSummaryStats(data.stats);
    }

    window.showToast(`New Order #${data.id} created for ${data.customer_name}.`, '✨');
}

function escapeHtml(string) {
    const div = document.createElement('div');
    div.textContent = string;
    return div.innerHTML;
}

// Subscribe to public 'orders' channel on Reverb
const channel = echo.channel('orders');

channel.listen('.order.status.updated', handleOrderStatusUpdated);
channel.listen('order.status.updated', handleOrderStatusUpdated);
channel.listen('.order.created', handleOrderCreated);
channel.listen('order.created', handleOrderCreated);

// Global function to handle status change via AJAX
window.handleStatusChange = async function (selectElement) {
    const orderId = selectElement.dataset.orderId;
    const newStatus = selectElement.value;
    const oldStatus = selectElement.dataset.currentStatus;

    if (newStatus === oldStatus) return;

    selectElement.disabled = true;
    const originalBorder = selectElement.style.borderColor;
    selectElement.style.borderColor = '#C97852';

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response = await fetch(`/orders/${orderId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
            },
            body: JSON.stringify({ status: newStatus }),
        });

        const result = await response.json();

        if (response.ok && result.success) {
            selectElement.dataset.currentStatus = newStatus;
            // Also update any matching counterpart (table row or mobile card)
            document.querySelectorAll(`.order-status-select[data-order-id="${orderId}"]`).forEach(el => {
                el.value = newStatus;
                el.dataset.currentStatus = newStatus;
            });
            // Update stats immediately if returned
            if (result.stats) {
                updateSummaryStats(result.stats);
            }
            window.showToast(`Order #${orderId} marked as ${newStatus}.`, '✓');
        } else {
            // Revert back
            selectElement.value = oldStatus;
            window.showToast(result.message || 'Failed to update order status.', '⚠️');
        }
    } catch (err) {
        console.error('Status update request failed:', err);
        selectElement.value = oldStatus;
        window.showToast('Network error updating order status.', '⚠️');
    } finally {
        selectElement.disabled = false;
        selectElement.style.borderColor = originalBorder;
    }
};

// Global function to handle New Order modal submission via AJAX
window.submitNewOrder = async function (event) {
    event.preventDefault();
    const form = event.target;
    const input = form.querySelector('#customer_name_input');
    const submitBtn = form.querySelector('#submit_order_btn');
    const customerName = input.value.trim();

    if (!customerName) {
        input.focus();
        return;
    }

    submitBtn.disabled = true;
    submitBtn.textContent = 'Creating...';

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response = await fetch('/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
            },
            body: JSON.stringify({ customer_name: customerName }),
        });

        const result = await response.json();

        if (response.ok && result.success) {
            input.value = '';
            window.closeNewOrderModal();
            window.showToast(`Order #${result.order.id} created for ${result.order.customer_name}!`, '✓');
            // If the local client has not yet received its own broadcast (or if toOthers was used), insert it
            if (!document.getElementById(`order-row-${result.order.id}`)) {
                handleOrderCreated({
                    ...result.order,
                    stats: result.stats,
                });
            }
        } else {
            const errorMsg = result.errors ? Object.values(result.errors).flat().join(' ') : (result.message || 'Could not create order.');
            window.showToast(errorMsg, '⚠️');
        }
    } catch (err) {
        console.error('Error creating order:', err);
        window.showToast('Network error creating order.', '⚠️');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Create Order';
    }
};

// Modal helpers
window.openNewOrderModal = function () {
    const modal = document.getElementById('new-order-modal');
    if (modal) {
        modal.classList.remove('hidden');
        const input = document.getElementById('customer_name_input');
        if (input) {
            setTimeout(() => input.focus(), 50);
        }
    }
};

window.closeNewOrderModal = function () {
    const modal = document.getElementById('new-order-modal');
    if (modal) {
        modal.classList.add('hidden');
    }
};

// Close modal on Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        window.closeNewOrderModal();
    }
});
