@extends('layouts.layout')
@section('content')
<div class="container mx-auto p-6 mt-5">
    <!-- Breadcrumb -->
    <span class="text-md text-gray-500 flex gap-1">
        <p class="font-bold">Request ></p>
        <p>List</p>
    </span>

    <!-- Page Title & Create Button -->
    <div class="flex justify-between items-center mb-6 mt-2">
        <h1 class="text-3xl font-semibold">Request List</h1>
    </div>

    <!-- Table -->
    <div id="detailed-pricing" class="w-full overflow-x-auto">
        <div class="min-w-max">
            <div class="bg-white shadow-md rounded-lg">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-200">
                        <tr class="border-b border-gray-300">
                            <th class="w-1/5 px-4 py-3 text-left">Name</th>
                            <th class="w-1/5 px-4 py-3 text-left">Course</th>
                            <th class="w-1/5 px-4 py-3 text-left">Start - End Time</th>
                            <th class="w-1/5 px-4 py-3 text-left">Date</th>
                            <th class="w-1/5 px-16 py-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                            <tr class="border-b border-gray-200">
                                <td class="w-1/5 px-4 py-3">{{ $request->user->name ?? 'N/A' }}</td>
                                <td class="w-1/5 px-4 py-3">{{ $request->course->name ?? 'N/A' }}</td>
                                <td class="w-1/5 px-4 py-3">
                                    {{ \Carbon\Carbon::parse($request->new_start_time)->format('h:i') }} - 
                                    {{ \Carbon\Carbon::parse($request->new_end_time)->format('h:i') }}
                                </td>
                                <td class="w-1/5 px-4 py-3">{{ $request->requested_date->format('Y-m-d') }}</td>
                                <td class="w-1/5 px-4 py-3 text-right space-x-2 flex">
                                    <button onclick="openModal('{{ $request->id }}', 'approve')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">Approve</button>
                                    <button onclick="openModal('{{ $request->id }}', 'reject')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">Reject</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div id="confirmationModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <h2 class="text-lg font-semibold mb-4" id="modalTitle">Confirm Action</h2>
        <form id="modalForm" method="POST" action="">
            @csrf
            <input type="hidden" name="request_id" id="modalRequestId">
            <input type="hidden" name="action_type" id="modalActionType">
            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
            <textarea name="message" id="modalMessage" rows="4" class="w-full border rounded px-3 py-2 mb-4" placeholder="Write your message..."></textarea>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModal()" class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Confirm</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(requestId, actionType) {
        document.getElementById('confirmationModal').classList.remove('hidden');
        document.getElementById('modalRequestId').value = requestId;
        document.getElementById('modalActionType').value = actionType;
        document.getElementById('modalTitle').textContent = actionType === 'approve' ? 'Approve Request' : 'Reject Request';
    }

    function closeModal() {
        document.getElementById('confirmationModal').classList.add('hidden');
        document.getElementById('modalForm').reset();
    }
</script>

@endsection
