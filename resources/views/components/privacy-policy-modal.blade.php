<div>
    <div x-data="{ isOpen: false }">
        <!-- Button to open the modal -->
        <a href="#" @click.prevent="isOpen = true" class="text-blue-600 underline">Privacy Policy</a>

        <!-- Modal -->
        <div x-show="isOpen" class="fixed inset-0 flex items-center justify-center z-50">
            <div class="bg-white p-4 rounded shadow-lg">
                <h2 class="text-xl font-bold mb-4">Privacy Policy</h2>
                <p>
                    <!-- Add your privacy policy content here -->
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Fusce varius, quam nec tempor facilisis, nunc ex efficitur nisi,
                    eu efficitur nunc est sed arcu.
                </p>
                <button @click="isOpen = false" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
