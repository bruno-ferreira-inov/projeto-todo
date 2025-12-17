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
    <button @click="handleClick" class="block w-full rounded-xl border p-3 shadow-sm bg-white
    hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 mb-3">
        <div class="flex flex-col justify between h-full min-h-[24px]">
            <div class="text-sm font-semibold text-gray-900">
                {{ todo.title }}
            </div>
            <div class="flex justify-end text-xs text-gray-500 mt-2" v-if="todo.completion_date">
                {{ todo.completion_date }}
            </div>
        </div>
    </button>
</template>
