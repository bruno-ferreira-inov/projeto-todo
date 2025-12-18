<script setup>
import { defineProps } from 'vue';
import TodoCard from './TodoCard.vue';

const props = defineProps({
    title: String,
    todos: Array,
})

const emit = defineEmits(['edit'])
</script>

<template>
    <div class="min-w-[260px] bg-gray=100 rounded-xl p-4">
        <h2 class="font-semibold text-gray-700 mb-3 text-center">{{ title }}</h2>

        <div class="space-y-3">
            <div class="bg-white rounded-lg">
                <TodoCard v-for="todo in todos" :key=todo.id :todo="todo" @click="$emit('edit', todo)" />
            </div>
        </div>
    </div>
</template>
