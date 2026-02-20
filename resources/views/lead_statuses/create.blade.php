@extends('layouts.admin')

@section('title', 'Add Lead Status')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Add Lead Status</h3>
    
    <div class="mt-8">
        <form action="{{ route('lead_statuses.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 max-w-lg">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Status Name <span class="text-red-500">*</span>
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" id="name" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. New">
                @error('name') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="color">
                    Badge Color
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('color') border-red-500 @enderror" id="color" name="color">
                    <option value="gray" {{ old('color') == 'gray' ? 'selected' : '' }}>Gray</option>
                    <option value="blue" {{ old('color') == 'blue' ? 'selected' : '' }}>Blue</option>
                    <option value="red" {{ old('color') == 'red' ? 'selected' : '' }}>Red</option>
                    <option value="yellow" {{ old('color') == 'yellow' ? 'selected' : '' }}>Yellow</option>
                    <option value="green" {{ old('color') == 'green' ? 'selected' : '' }}>Green</option>
                    <option value="indigo" {{ old('color') == 'indigo' ? 'selected' : '' }}>Indigo</option>
                    <option value="purple" {{ old('color') == 'purple' ? 'selected' : '' }}>Purple</option>
                    <option value="pink" {{ old('color') == 'pink' ? 'selected' : '' }}>Pink</option>
                    <option value="teal" {{ old('color') == 'teal' ? 'selected' : '' }}>Teal</option>
                </select>
                @error('color') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="order">
                    Order
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('order') border-red-500 @enderror" id="order" type="number" name="order" value="{{ old('order', 0) }}">
                @error('order') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                    Status
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" id="status" name="status">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('lead_statuses.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Create Status
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
