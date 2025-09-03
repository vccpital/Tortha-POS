<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <input type="hidden" name="status" value="active">

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <x-input-label for="phone_number" :value="__('Phone Number')" />
            <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number')" required />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

        <!-- User Type -->
        <div class="mt-4">
            <x-input-label for="usertype" :value="__('User Type')" />
            <select name="usertype" id="usertype" class="block mt-1 w-full" required>
                <option value="user" {{ old('usertype') == 'user' ? 'selected' : '' }}>User</option>
            </select>
            <x-input-error :messages="$errors->get('usertype')" class="mt-2" />
        </div>

        <!-- Store ID (Optional) -->
        <!-- Store ID (Only for non-admins) -->
<div class="mt-4" id="store-id-field">
    <x-input-label for="store_id" :value="__('Store ID')" />
    <x-text-input id="store_id" class="block mt-1 w-full" type="number" name="store_id" :value="old('store_id')" />
    <x-input-error :messages="$errors->get('store_id')" class="mt-2" />
</div>
<script>
    const usertypeSelect = document.getElementById('usertype');
    const storeField = document.getElementById('store-id-field');

    function toggleStoreField() {
        const type = usertypeSelect.value;
        if (type === 'user' || type === 'devadmin') {
            storeField.style.display = 'none';
        } else {
            storeField.style.display = 'block';
        }
    }

    usertypeSelect.addEventListener('change', toggleStoreField);
    document.addEventListener('DOMContentLoaded', toggleStoreField);
</script>


        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
