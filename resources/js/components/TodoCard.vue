<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    todo: {
        type: Object,
        required: true
    }
})

const emit = defineEmits(['select']) // Define what events are triggered from this component

const handleClick = () => {
    emit('select', props.todo)
}
</script>

<template>
    <button @click="handleClick" class="block w-full rounded-xl border-t border-gray-300 p-3
    hover:bg-gray-300 focus:outline-none focus:ring-1 focus:ring-indigo-500">

        <div class="flex flex-col h-full min-h-[24px]">
            <div class="text-base font-medium text-gray-900">
                {{ todo.title }}
            </div>
            <div class="flex justify-center text-sm text-gray-500">
                {{ todo.completion_date ?? 'No deadline' }}
            </div>
        </div>
    </button>
</template>
