<x-layout>
    <div class="min-h-screen bg-gray-100 p-4">
        <div class="mx-auto max-w-7xl">
            <h1 class="text-2xl font-bold mb-4">My Tasks</h1>


            <div class="flex gap-4 overflow-x-auto pb-4">


                <!-- Overdue -->
                <div class="w-72 flex-shrink-0 bg-white rounded-xl shadow-sm">
                    <div class="px-4 py-3 border-b">
                        <h2 class="text-sm font-semibold text-red-600">Overdue</h2>
                    </div>
                    <div class="p-3 space-y-3">
                        <div class="rounded-lg border p-3">Missed deadline task</div>
                    </div>
                </div>


                <!-- Today -->
                <div class="w-72 flex-shrink-0 bg-white rounded-xl shadow-sm">
                    <div class="px-4 py-3 border-b">
                        <h2 class="text-sm font-semibold">Today</h2>
                    </div>
                    <div class="p-3 space-y-3">
                        <div class="rounded-lg border p-3">Finish report</div>
                        <div class="rounded-lg border p-3">Call client</div>
                    </div>
                </div>


                <!-- Next 3 Days -->
                <div class="w-72 flex-shrink-0 bg-white rounded-xl shadow-sm">
                    <div class="px-4 py-3 border-b">
                        <h2 class="text-sm font-semibold">Next 3 Days</h2>
                    </div>
                    <div class="p-3 space-y-3">
                        <div class="rounded-lg border p-3">Prepare slides</div>
                    </div>
                </div>


                <!-- No Deadline -->
                <div class="w-72 flex-shrink-0 bg-white rounded-xl shadow-sm">
                    <div class="px-4 py-3 border-b">
                        <h2 class="text-sm font-semibold">No Deadline</h2>
                    </div>
                    <div class="p-3 space-y-3">
                        <div class="rounded-lg border p-3">Clean inbox</div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</x-layout>