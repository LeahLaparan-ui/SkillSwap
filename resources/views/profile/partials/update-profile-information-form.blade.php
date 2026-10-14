<section>
    <header>
        <h2 class="text-lg font-medium text-beige-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-beige-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div x-data="{ preview: null }">
            <x-input-label for="avatar" :value="__('Profile photo')" />

            <div class="mt-2 flex items-center gap-4">
                <template x-if="preview">
                    <img :src="preview" alt="" class="h-24 w-24 rounded-full object-cover ring-1 ring-beige-200" />
                </template>
                <template x-if="!preview">
                    <x-avatar :user="$user" size="xl" />
                </template>

                <div class="flex-1">
                    <input id="avatar" name="avatar" type="file" accept="image/png,image/jpeg,image/webp"
                        @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                        class="block w-full text-sm text-beige-600 file:mr-4 file:rounded-md file:border-0 file:bg-sage-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-sage-500" />
                    <p class="mt-1 text-xs text-beige-500">{{ __('JPG, PNG or WebP. Max 2 MB.') }}</p>
                </div>
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />

            @if ($user->avatar)
                <label class="mt-3 inline-flex items-center gap-2 text-sm text-beige-600">
                    <input type="checkbox" name="remove_avatar" value="1" class="rounded border-beige-300 text-sage-600 focus:ring-sage-500" />
                    {{ __('Remove current photo') }}
                </label>
            @endif
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-beige-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-beige-600 hover:text-beige-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sage-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="university" :value="__('University / School')" />
            <x-text-input id="university" name="university" type="text" class="mt-1 block w-full" :value="old('university', $user->university)" autocomplete="organization" />
            <x-input-error class="mt-2" :messages="$errors->get('university')" />
        </div>

        <div>
            <x-input-label for="bio" :value="__('Short bio')" />
            <textarea id="bio" name="bio" rows="4"
                class="mt-1 block w-full rounded-md border-beige-300 shadow-sm focus:border-sage-500 focus:ring-sage-500"
                placeholder="Tell other students a bit about yourself and what you'd like to trade.">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-beige-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
