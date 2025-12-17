<script setup>
import { onMounted, ref, computed } from 'vue';
import TodoBase from './TodoCard.vue';
import TodoModal from './TodoModal.vue';
import TodoColumn from './TodoColumn.vue';
import dayjs from 'dayjs';

const todos = ref([])
const selectedTodo = ref(null)
const title = ref('')

const fetchTodos = async () => {
    const result = await fetch('todos/list')
    todos.value = await result.json()
}

const addTodo = async () => {
    if (!title.value) return

    const result = await fetch('/todos', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name=csrf-token]')
                .content,
        },
        body: JSON.stringify({ title: title.value }),
    })

    todos.value.unshift(await result.json())
    title.value = ''
}

const openModal = (todo) => {
    selectedTodo.value = todo
}


const applyUpdate = (updatedTodo) => {
    const index = todos.value.findIndex(t => t.id === updatedTodo.id)
    todos.value[index] = updatedTodo
}

const today = dayjs()

const groupedTodos = computed(() => ({
    overdue: todos.value.filter(t => dayjs(t.completion_date).isBefore(today, 'day')),
    today: todos.value.filter(t => dayjs(t.completion_date).isSame(today, 'day')),
    next3: todos.value.filter(t => dayjs(t.completion_date).isAfter(today) && dayjs(t.completion_date).diff(today, 'day') <= 3),
    next7: todos.value.filter(t => dayjs(t.completion_date).isAfter(today) && dayjs(t.completion_date).diff(today, 'day') >= 4 && dayjs(t.completion_date).diff(today, 'day') <= 7),
    later: todos.value.filter(t => dayjs(t.completion_date).diff(today, 'day') > 7),
    noDeadline: todos.value.filter(t => !t.completion_date),
}))

onMounted(fetchTodos)
</script>

<template>
    <div class="flex gap-4 overflow-x-auto p-4">
        <TodoColumn title="Overdue" :todos="groupedTodos.overdue" @select="openModal"></TodoColumn>
        <TodoColumn title="Today" :todos="groupedTodos.today" @select="openModal"></TodoColumn>
        <TodoColumn title="Next 3 Days" :todos="groupedTodos.next3" @select="openModal"></TodoColumn>
        <TodoColumn title="Next 7 Days" :todos="groupedTodos.next7" @select="openModal"></TodoColumn>
        <TodoColumn title="Later" :todos="groupedTodos.later" @select="openModal"></TodoColumn>
        <TodoColumn title="No Urgency" :todos="groupedTodos.noDeadline" @select="openModal"></TodoColumn>
    </div>

    <div class="w-64">
        <form @submit.prevent="addTodo">
            <input v-model="title" placeholder="New Todo" />
            <button>Create Todo</button>
        </form>

        <ul>
            <TodoBase v-for="todo in todos" :key="todo.id" :todo="todo" @select="openModal" />
            <!--@select="openModal" -> What to trigger on the select event that is inside TodoBase-->
        </ul>

        <TodoModal v-if="selectedTodo" :todo="selectedTodo" @updated="applyUpdate" @close="selectedTodo = null" />
    </div>
</template>
