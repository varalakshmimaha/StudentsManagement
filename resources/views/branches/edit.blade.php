@extends('layouts.admin')

@section('title', 'Edit Branch')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Edit Branch: {{ $branch->name }}</h3>
    
    <div class="mt-8">
        <form action="{{ route('branches.update', $branch->id) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Branch Name -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Branch Name <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" id="name" type="text" name="name" value="{{ old('name', $branch->name) }}" placeholder="Main Branch">
                    @error('name') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Branch Code -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="code">
                        Branch Code
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('code') border-red-500 @enderror" id="code" type="text" name="code" value="{{ old('code', $branch->code) }}" placeholder="MB001">
                    @error('code') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" id="status" name="status">
                        <option value="active" {{ old('status', $branch->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $branch->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Address -->
                <div class="col-span-1 md:col-span-2 mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="address">
                        Address
                    </label>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('address') border-red-500 @enderror" id="address" name="address" rows="3" placeholder="123 Main Street, City, State, ZIP">{{ old('address', $branch->address) }}</textarea>
                    @error('address') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-between mt-8">
                <a href="{{ route('branches.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Branch
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
