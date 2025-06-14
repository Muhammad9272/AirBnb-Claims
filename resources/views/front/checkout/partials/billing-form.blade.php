<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="col-span-2 md:col-span-1">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Full Name <span class="text-red-500">*</span>
        </label>
        <input type="text" name="billing_name" id="billing_name" required
               class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 
                      bg-white dark:bg-gray-800 text-gray-900 dark:text-white 
                      focus:ring-2 focus:ring-purple-500 focus:border-transparent"
               value="{{ old('billing_name') }}">
        <div class="text-red-500 text-xs mt-1 hidden error-message"></div>
    </div>

    <div class="col-span-2 md:col-span-1">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Email Address <span class="text-red-500">*</span>
        </label>
        <input type="email" name="billing_email" id="billing_email" required
               class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 
                      bg-white dark:bg-gray-800 text-gray-900 dark:text-white 
                      focus:ring-2 focus:ring-purple-500 focus:border-transparent"
               value="{{ old('billing_email') }}">
        <div class="text-red-500 text-xs mt-1 hidden error-message"></div>
    </div>

    <div class="col-span-2 md:col-span-1">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Phone Number <span class="text-red-500">*</span>
        </label>
        <input type="tel" name="billing_phone" id="billing_phone" required
               class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 
                      bg-white dark:bg-gray-800 text-gray-900 dark:text-white 
                      focus:ring-2 focus:ring-purple-500 focus:border-transparent"
               value="{{ old('billing_phone') }}">
        <div class="text-red-500 text-xs mt-1 hidden error-message"></div>
    </div>

    <div class="col-span-2">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Street Address <span class="text-red-500">*</span>
        </label>
        <input type="text" name="billing_address" id="billing_address" required
               class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 
                      bg-white dark:bg-gray-800 text-gray-900 dark:text-white 
                      focus:ring-2 focus:ring-purple-500 focus:border-transparent"
               value="{{ old('billing_address') }}">
        <div class="text-red-500 text-xs mt-1 hidden error-message"></div>
    </div>

    <div class="col-span-2 md:col-span-1">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            City <span class="text-red-500">*</span>
        </label>
        <input type="text" name="billing_city" id="billing_city" required
               class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 
                      bg-white dark:bg-gray-800 text-gray-900 dark:text-white 
                      focus:ring-2 focus:ring-purple-500 focus:border-transparent"
               value="{{ old('billing_city') }}">
        <div class="text-red-500 text-xs mt-1 hidden error-message"></div>
    </div>

    <div class="col-span-2 md:col-span-1">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            State/Province <span class="text-red-500">*</span>
        </label>
        <input type="text" name="billing_state" id="billing_state" required
               class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 
                      bg-white dark:bg-gray-800 text-gray-900 dark:text-white 
                      focus:ring-2 focus:ring-purple-500 focus:border-transparent"
               value="{{ old('billing_state') }}">
        <div class="text-red-500 text-xs mt-1 hidden error-message"></div>
    </div>

    <div class="col-span-2 md:col-span-1">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Postal Code <span class="text-red-500">*</span>
        </label>
        <input type="text" name="billing_zipcode" id="billing_zipcode" required
               class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 
                      bg-white dark:bg-gray-800 text-gray-900 dark:text-white 
                      focus:ring-2 focus:ring-purple-500 focus:border-transparent"
               value="{{ old('billing_zipcode') }}">
        <div class="text-red-500 text-xs mt-1 hidden error-message"></div>
    </div>

    <div class="col-span-2 md:col-span-1">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Country <span class="text-red-500">*</span>
        </label>
        <select name="billing_country" id="billing_country" required
                class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 
                       bg-white dark:bg-gray-800 text-gray-900 dark:text-white 
                       focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            <option value="">Select Country</option>
            @foreach(Helpers::getCountries() as $code => $name)
                <option value="{{ $code }}" {{ old('billing_country') == $code ? 'selected' : '' }}>
                    {{ $name }}
                </option>
            @endforeach
        </select>
        <div class="text-red-500 text-xs mt-1 hidden error-message"></div>
    </div>
</div>
