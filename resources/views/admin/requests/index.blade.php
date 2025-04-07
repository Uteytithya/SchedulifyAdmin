@extends('layouts.layout')

@section('content')
<div class="container mx-auto p-6 mt-5">
    
    <span class="text-md text-gray-500 flex gap-1">
        <p class="font-bold">Request ></p>
        <p>List</p>
    </span>

    <div class="flex justify-between items-center mb-6 mt-2">
        <h1 class="text-3xl font-semibold">Request List</h1>
    </div>

    <div id="detailed-pricing" class="w-full overflow-x-auto">
        <div class="min-w-max">
            <div class="bg-white shadow-md rounded-lg">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-200">
                        <tr class="border-b border-gray-300">
                            <th class="w-1/5 px-4 py-3 text-left">Name</th>
                            <th class="w-1/5 px-4 py-3 text-left">Course</th>
                            <th class="w-1/5 px-4 py-3 text-left">Start - End Time</th>
                            <th class="w-1/5 px-4 py-3 text-left">Request at</th>
                            <th class="w-1/5 px-16 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                        <tr class="border-b border-gray-200">
                            <td class="w-1/5 px-4 py-3">{{ $request->user->name ?? 'N/A' }}</td>
                            <td class="w-1/5 px-4 py-3">{{ $request->course->name ?? 'N/A' }}</td>
                            <td class="w-1/5 px-4 py-3">
                                {{ \Carbon\Carbon::parse($request->new_start_time)->format('h:i A') }} - 
                                {{ \Carbon\Carbon::parse($request->new_end_time)->format('h:i A') }}
                            </td>
                            <td class="w-1/5 px-4 py-3">
                                {{ \Carbon\Carbon::parse($request->requested_date)->format('D d, h:i A') }}
                            </td>
                            
                            <td class="w-1/5 px-4 py-3 text-center">
                                @if ($request->status == 'pending')
                                    <form action="{{ route('admin.requests_update', $request->id) }}" method="POST" class="flex gap-2 justify-center">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" name="status" value="approved" class="bg-green-600 text-white px-4 py-1 rounded hover:bg-green-700">Approve</button>
                                        <button type="submit" name="status" value="denied" class="bg-red-600 text-white px-4 py-1 rounded hover:bg-red-700">Deny</button>
                                    </form>
                                @else
                                    <span class="
                                        @if ($request->status == 'approved') bg-green-100 text-green-800 px-3 py-1 rounded-md 
                                        @elseif ($request->status == 'denied') bg-red-100 text-red-800 px-3 py-1 rounded-md 
                                        @endif
                                    ">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
