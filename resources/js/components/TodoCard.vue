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
    <button @click="handleClick" :class="[
        'block w-full rounded-xl border-t border-gray-300 p-3 hover:bg-gray-300 transition-colors duration-200 ',
        todo.completed
            ? 'bg-gray-50 text-gray-400 opacity-70 hover:bg-gray-50'
            : 'text-gray-900 hover:bg-gray-100'
    ]">
        <div class="flex flex-col h-full min-h-[24px]">
            <div class="text-base font-medium" :class="todo.completed ? 'line-through text-gray-400' : 'text-gray-900'">
                {{ todo.title }}
            </div>
            <div class="flex justify-center text-sm" :class="todo.completed ? 'text-gray-400' : 'text-gray-500'">
                {{ todo.completion_date ?? 'No deadline' }}
            </div>
        </div>
    </button>
</template>
