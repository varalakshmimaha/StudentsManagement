@extends('layouts.admin')

@section('title', 'Edit Lead Status')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Edit Lead Status: {{ $leadStatus->name }}</h3>
    
    <div class="mt-8">
        <form action="{{ route('lead_statuses.update', $leadStatus->id) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 max-w-lg">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Status Name <span class="text-red-500">*</span>
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" id="name" type="text" name="name" value="{{ old('name', $leadStatus->name) }}" placeholder="e.g. New">
                @error('name') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="color">
                    Badge Color
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('color') border-red-500 @enderror" id="color" name="color">
                    @foreach(['gray', 'blue', 'red', 'yellow', 'green', 'indigo', 'purple', 'pink', 'teal'] as $color)
                        <option value="{{ $color }}" {{ old('color', $leadStatus->color) == $color ? 'selected' : '' }}>{{ ucfirst($color) }}</option>
                    @endforeach
                </select>
                @error('color') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="order">
                    Order
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('order') border-red-500 @enderror" id="order" type="number" name="order" value="{{ old('order', $leadStatus->order) }}">
                @error('order') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                    Status
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" id="status" name="status">
                    <option value="active" {{ old('status', $leadStatus->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $leadStatus->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('lead_statuses.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
