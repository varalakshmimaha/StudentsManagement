@extends('layouts.admin')

@section('title', 'Add New User')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Add New User</h3>
    
    <div class="mt-8">
        <form action="{{ route('users.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" id="name" type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" autofocus>
                    @error('name') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Mobile -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="mobile">
                        Mobile Number <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('mobile') border-red-500 @enderror" id="mobile" type="text" name="mobile" value="{{ old('mobile') }}" placeholder="9876543210" maxlength="10">
                    @error('mobile') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
                
                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="user@example.com">
                    @error('email') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Username -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                        Username
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('username') border-red-500 @enderror" id="username" type="text" name="username" value="{{ old('username') }}" placeholder="johndoe">
                    @error('username') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Role -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="role_id">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('role_id') border-red-500 @enderror" id="role_id" name="role_id">
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" id="status" name="status">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror" id="password" type="password" name="password" placeholder="Minimum 6 characters">
                    @error('password') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Branches -->
                <div class="col-span-1 md:col-span-2 mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Assign Branches
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        @foreach($branches as $branch)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="branches[]" value="{{ $branch->id }}" class="form-checkbox h-5 w-5 text-red-600" {{ in_array($branch->id, old('branches', [])) ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">{{ $branch->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('branches') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-between mt-8">
                <a href="{{ route('users.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                <div class="flex gap-2">
                    <button type="submit" name="save_and_new" value="1" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Save & New
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Save User
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
