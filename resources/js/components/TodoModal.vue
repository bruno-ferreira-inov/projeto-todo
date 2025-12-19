<script setup>
import { defineProps, defineEmits, watch, ref, onMounted, onBeforeUnmount, computed } from 'vue';

const props = defineProps({
    todo: {
        type: Object,
        default: null,
    }
})

const emit = defineEmits(['close', 'save', 'delete', 'updated'])

const form = ref({
    title: '',
    description: '',
    priority: 5,
    completed: false,
    completion_date: null,
})

const original = ref(null)

const isEdit = computed(() => !!props.todo)

const hasChanges = computed(() =>
    JSON.stringify(form.value) !== original.value
)

watch(
    () => props.todo,
    (todo) => {
        if (todo) {
            form.value = { ...todo }
            original.value = JSON.stringify(form.value)
        } else {
            form.value = {
                title: '',
                description: '',
                priority: 5,
                completed: false,
                completion_date: null,
            }
            original.value = JSON.stringify(form.value)
        }
    },
    { immediate: true }
)

const handleKeydown = (e) => {
    if (e.key === 'Escape') {
        emit('close')
    }

    if ((e.metaKey || e.ctrlKey) && e.key === 'Enter') {
        save()
    }
}

onMounted(() => {
    window.addEventListener('keydown', handleKeydown)
})

onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleKeydown)
})

</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">

            <!-- Header -->
            <div class="border-b w-124 justify-center px-6 py-4">
                <div class="flex justify-between">

                    <h2 class="text-2xl font-semibold">
                        {{ isEdit ? 'Edit Todo' : 'Create Todo' }}
                    </h2>

                    <!-- Completed -->
                    <label class="flex justify-end items-center gap-2 text-base font-medium cursor-pointer">
                        <input type="checkbox" v-model="form.completed" class="h-4 w-4 rounded border" />
                        Completed
                    </label>
                </div>
            </div>

            <!-- Body -->
            <div class="px-6 py-4 space-y-4">

                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Title
                    </label>
                    <input type="text" v-model="form.title"
                        class="w-full rounded-lg border px-3 py-2 text-base font-medium focus:outline-none focus:ring-2" />
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Description
                    </label>
                    <textarea v-model="form.description" rows="3"
                        class="w-full rounded-lg border px-3 py-2 text-base resize-none focus:outline-none focus:ring-2" />
                </div>

                <!-- Priority + Completion -->
                <div class="flex items-center justify-between gap-4">

                    <!-- Priority -->
                    <div>
                        <label class="flex items-center gap-1 text-sm font-medium mb-1 group">
                            Priority

                            <!-- Info icon -->
                            <span class="relative inline-flex items-center justify-center w-4 h-4 rounded-full
                   text-gray-500 hover:text-gray-700 cursor-pointer">
                                ℹ

                                <!-- Tooltip -->
                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2
                       hidden group-hover:block
                       whitespace-nowrap rounded-md bg-gray-800 px-2 py-1
                       text-[12px] text-white shadow-lg">
                                    Priority goes from 1 (highest) → 5 (lowest)
                                </span>
                            </span>
                        </label>
                        <input type="number" v-model.number="form.priority" min="1" max="5"
                            class="w-24 rounded-lg border px-3 py-2 text-base focus:outline-none focus:ring-2" />
                    </div>

                    <!-- Completion Date -->
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Deadline
                        </label>
                        <input type="date" v-model="form.completion_date"
                            class="rounded-lg border px-3 py-2 text-base focus:outline-none focus:ring-2" />
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-2 border-t px-6 py-4">
                <button v-if="isEdit" @click="emit('delete', form)" class="text-base text-red-600">
                    Delete
                </button>

                <div class="flex gap-2 ml-auto">
                    <button @click="emit('close')" class="rounded-lg px-4 py-2 text-base font-medium">
                        Cancel
                    </button>
                    <button :disabled="!hasChanges" @click="emit('save', form)"
                        class="bg-gray-600 text-white px-4 py-2 rounded disabled:opacity-40">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
