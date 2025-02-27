<x-container>

    <x-heading
        :text="($language->verify->headline ?? 'No Heading')"
        :description="($language->register->subheadline ?? 'No Description')"
        :show_subheadline="($language->verify->show_subheadline ?? false)" />


    @if (session('resent'))
        <div class="flex items-start px-4 py-3 mb-5 text-sm text-white bg-green-500 rounded shadow" role="alert">
            <svg class="mr-2 w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>

            <p>{{config('language.verify.new_link_sent')}}</p>
        </div>
    @endif

    <div class="text-sm leading-6 text-gray-700 dark:text-gray-400 ">
        <p class="leading-4">
            {{config('language.verify.description')}}
            <a wire:click="resend" data-auth="verify-email-resend-link" class="text-gray-700 underline transition duration-150 ease-in-out cursor-pointer dark:text-gray-400 hover:text-gray-600 focus:outline-none focus:underline">
                {{config('language.verify.new_request_link')}}
            </a>
        </p>
    </div>

    <div class="mt-2 space-x-0.5 text-sm leading-5 text-center text-gray-600 translate-y-4 dark:text-gray-400">
        <span>{{config('language.verify.or')}}</span>
        <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-gray-500 underline cursor-pointer dark:text-gray-400 dark:hover:text-gray-300 hover:text-gray-800">
            {{config('language.verify.logout')}}
        </button>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

</x-container>
