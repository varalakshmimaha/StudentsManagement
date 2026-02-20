@extends('layouts.admin')

@section('title', 'Leads Kanban')

@section('content')
<div class="h-[calc(100vh-theme('spacing.16'))] flex flex-col overflow-hidden">
    <!-- Header with View Switcher -->
    <div class="px-6 py-4 border-b border-gray-200 bg-white flex-none">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <h3 class="text-gray-700 text-3xl font-medium">Leads</h3>
            
            <div class="flex items-center gap-4 mt-4 md:mt-0">
                <!-- View Switcher -->
                <div class="bg-gray-100 p-1 rounded-lg flex shadow-inner">
                    <a href="{{ route('leads.index') }}" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 rounded-md transition-colors">
                        List View
                    </a>
                    <a href="{{ route('leads.kanban') }}" class="px-4 py-2 text-sm font-medium bg-white text-blue-600 shadow rounded-md">
                        Kanban View
                    </a>
                </div>

                <a href="{{ route('leads.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 font-medium tracking-wide shadow-sm">
                    + Add Lead
                </a>
            </div>
        </div>
        
        <!-- Quick Filters (All / Assigned) -->
        <div class="mt-4 flex gap-4 text-sm border-b border-gray-100">
            <a href="{{ route('leads.kanban') }}" class="{{ !request('assigned') ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} pb-2 font-medium">
                All Leads
            </a>
            <a href="{{ route('leads.kanban', ['assigned' => 'me']) }}" class="{{ request('assigned') == 'me' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} pb-2 font-medium">
                Assigned to Me
            </a>
        </div>
    </div>

    <!-- Kanban Board Area -->
    <div class="flex-1 overflow-x-auto overflow-y-hidden bg-gray-50 p-6">
        <div class="inline-flex h-full gap-6 align-top">
            @foreach($statuses as $status)
                <!-- Status Column -->
                <div class="w-80 flex-shrink-0 flex flex-col h-full bg-gray-100 rounded-xl border border-gray-200 shadow-sm" data-status-slug="{{ Str::slug($status->name) }}">
                    <!-- Header -->
                    <div class="p-4 rounded-t-xl bg-{{ $status->color ?? 'gray' }}-50 border-b border-{{ $status->color ?? 'gray' }}-100 flex justify-between items-center sticky top-0 z-10">
                        <div class="flex items-center gap-2">
                             <div class="w-3 h-3 rounded-full bg-{{ $status->color ?? 'gray' }}-500"></div>
                             <h4 class="font-bold text-gray-700 uppercase tracking-wide text-xs">
                                {{ $status->name }}
                             </h4>
                        </div>
                        <span class="bg-white px-2 py-0.5 rounded-md text-xs font-bold text-gray-500 border border-gray-200 shadow-sm count-badge">
                            {{ isset($leadsByStatus[strtolower($status->name)]) ? $leadsByStatus[strtolower($status->name)]->count() : 0 }}
                        </span>
                    </div>

                    <!-- Cards Container -->
                    <div class="flex-1 overflow-y-auto p-3 space-y-3 kanban-column custom-scrollbar" data-status="{{ $status->name }}">
                        @if(isset($leadsByStatus[strtolower($status->name)]))
                            @foreach($leadsByStatus[strtolower($status->name)] as $lead)
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-grab hover:shadow-md transition-shadow group relative kanban-card" data-lead-id="{{ $lead->id }}">
                                    <!-- Card Content -->
                                    <div class="flex justify-between items-start mb-2">
                                        <h5 class="font-bold text-gray-800 text-sm leading-tight hover:text-blue-600">
                                            <a href="{{ route('leads.show', $lead->id) }}">{{ $lead->name }}</a>
                                        </h5>
                                        <button class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                        </button>
                                    </div>
                                    
                                    <div class="space-y-1.5 mb-3">
                                        <div class="flex items-center text-xs text-gray-600">
                                            <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            {{ $lead->phone }}
                                        </div>
                                        @if(isset($lead->interested_courses) && is_array($lead->interested_courses))
                                        <div class="flex items-center text-xs text-gray-600">
                                            <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                                            {{ implode(', ', array_slice($lead->interested_courses, 0, 1)) }}{{ count($lead->interested_courses) > 1 ? '...' : '' }}
                                        </div>
                                        @endif
                                        <div class="flex items-center text-xs text-gray-600">
                                            <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                            Source: {{ $lead->source ?? 'Unknown' }}
                                        </div>
                                        <div class="flex items-center text-xs text-gray-600">
                                            <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            Assigned: <span class="font-medium ml-1">{{ $lead->counsellor->name ?? 'None' }}</span>
                                        </div>
                                        @if($lead->next_followup_date)
                                        <div class="flex items-center text-xs {{ \Carbon\Carbon::parse($lead->next_followup_date)->isPast() ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                                            <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            Follow-up: {{ \Carbon\Carbon::parse($lead->next_followup_date)->format('d M Y') }}
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Action Icons -->
                                    <div class="flex justify-end gap-3 mt-3 pt-3 border-t border-gray-100 opacity-80 group-hover:opacity-100 transition-opacity">
                                        <a href="tel:{{ $lead->phone }}" class="text-gray-400 hover:text-green-500" title="Call">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        </a>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}" target="_blank" class="text-gray-400 hover:text-green-600" title="WhatsApp">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                        </a>
                                         <a href="{{ route('leads.show', $lead->id) }}" class="text-gray-400 hover:text-blue-500" title="View">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Convert Modal -->
<div id="convertModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Convert to Student?</h3>
            <button onclick="closeModal('convertModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6">
            <form id="convertForm">
                <input type="hidden" id="convert_lead_id">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Select Batch <span class="text-red-500">*</span></label>
                    <select id="convert_batch_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                        <option value="">Choose Batch...</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->name }} ({{ $batch->course->name ?? 'Course' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Joining Date <span class="text-red-500">*</span></label>
                    <input type="date" id="convert_joining_date" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required value="{{ date('Y-m-d') }}">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('convertModal')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md font-medium">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-bold shadow-sm">Confirm Conversion</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Lost Modal -->
<div id="lostModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Mark as Lost</h3>
            <button onclick="closeModal('lostModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6">
            <form id="lostForm">
                <input type="hidden" id="lost_lead_id">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Reason <span class="text-red-500">*</span></label>
                    <select id="lost_reason" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                        <option value="">Select Reason...</option>
                        <option value="Not Interested">Not Interested</option>
                        <option value="Price Issue">Price Issue</option>
                        <option value="Joined Other Institute">Joined Other Institute</option>
                        <option value="Location Issue">Location Issue</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea id="lost_notes" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Any additional details..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('lostModal')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md font-medium">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-bold shadow-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SortableJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const columns = document.querySelectorAll('.kanban-column');
        let draggedItem = null;
        let targetStatus = null;
        let sourceStatus = null;
        let currentLeadId = null;

        columns.forEach(column => {
            new Sortable(column, {
                group: 'leads', // Allow dragging between lists
                animation: 150,
                ghostClass: 'bg-blue-50',
                dragClass: 'opacity-50',
                onStart: function (evt) {
                    draggedItem = evt.item;
                    currentLeadId = draggedItem.getAttribute('data-lead-id');
                    sourceStatus = evt.from.getAttribute('data-status');
                },
                onEnd: function (evt) {
                    const itemEl = evt.item;
                    const newStatus = evt.to.getAttribute('data-status');
                    
                    if (sourceStatus === newStatus) return; // No change

                    targetStatus = newStatus;

                    // Check for special statuses
                    if (newStatus.toLowerCase() === 'converted') {
                        // Revert move visually until confirmed
                        // Actually SortableJS has already moved it.
                        // We can move it back if cancelled, or leave it and ask.
                        // Better UX: Open modal. If cancelled, move back.
                        openConvertModal(currentLeadId, evt);
                    } else if (newStatus.toLowerCase() === 'lost') {
                        openLostModal(currentLeadId, evt);
                    } else {
                        // Standard move
                        updateLeadStatus(currentLeadId, newStatus);
                    }
                }
            });
        });

        // Modal Logic
        window.openConvertModal = function(leadId, evt) {
            document.getElementById('convert_lead_id').value = leadId;
            document.getElementById('convertModal').classList.remove('hidden');
            // Store event to revert if needed
            window.currentSortableEvent = evt;
        }

        window.openLostModal = function(leadId, evt) {
            document.getElementById('lost_lead_id').value = leadId;
            document.getElementById('lostModal').classList.remove('hidden');
            window.currentSortableEvent = evt;
        }

        window.closeModal = function(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            // Revert move if cancelled
            if (window.currentSortableEvent) {
                const evt = window.currentSortableEvent;
                evt.from.appendChild(evt.item); // Move back to source
                window.currentSortableEvent = null;
                // Toast: Cancelled
            }
        }

        // Handle Forms
        document.getElementById('convertForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const leadId = document.getElementById('convert_lead_id').value;
            const batchId = document.getElementById('convert_batch_id').value;
            const joiningDate = document.getElementById('convert_joining_date').value;

            updateLeadStatus(leadId, 'Converted', {
                batch_id: batchId,
                joining_date: joiningDate
            });

            document.getElementById('convertModal').classList.add('hidden');
            window.currentSortableEvent = null; // Confirmed, no revert
        });

        document.getElementById('lostForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const leadId = document.getElementById('lost_lead_id').value;
            const reason = document.getElementById('lost_reason').value;
            const notes = document.getElementById('lost_notes').value;

            updateLeadStatus(leadId, 'Lost', {
                lost_reason: reason,
                lost_reason_notes: notes
            });

            document.getElementById('lostModal').classList.add('hidden');
            window.currentSortableEvent = null; // Confirmed
        });

        function updateLeadStatus(leadId, status, extraData = {}) {
            const data = {
                status: status.toLowerCase(), // Ensure lowercase for DB matches seeder
                _token: '{{ csrf_token() }}',
                ...extraData
            };

            const url = `/leads/${leadId}/status`; // We need to create this route

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('✅ ' + data.message);
                    updateCounts();
                } else {
                    showToast('❌ Error: ' + data.message);
                    // Revert if error?
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('❌ System Error');
            });
        }

        function showToast(message) {
            // Simple toast implementation
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-bounce';
            toast.innerText = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        function updateCounts() {
            // Recalculate counts based on DOM
            document.querySelectorAll('.kanban-column').forEach(column => {
                const count = column.children.length;
                const badge = column.parentElement.querySelector('.count-badge');
                if(badge) badge.innerText = count;
            });
        }
    });
</script>

<style>
    /* Custom Scrollbar for horizontal board and vertical columns */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(156, 163, 175, 0.5);
        border-radius: 20px;
    }
    .kanban-card:hover {
        transform: translateY(-2px);
    }
</style>
@endsection
