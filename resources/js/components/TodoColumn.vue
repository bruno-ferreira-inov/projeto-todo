<script setup>
import { defineProps } from 'vue';
import TodoCard from './TodoCard.vue';

const props = defineProps({
    title: String,
    todos: Array,
})

const emit = defineEmits(['select'])
</script>

<template>
    <div class="min-w-[260px] bg-gray=100 rounded-xl p-4">
        <h2 class="font-semibold text-gray-700 mb-3 text-center">{{ title }}</h2>

        <div class="space-y-3">
            <div v-for="todo in todos" :key="todo.id" class="bg-white rounded-lg">
                <TodoCard :todo="todo" @select="emit('select', $event)"></TodoCard>
            </div>
        </div>
    </div>
</template>
