<script setup>
import { onMounted, ref, computed } from 'vue';
import TodoModal from './TodoModal.vue';
import TodoColumn from './TodoColumn.vue';
import dayjs from 'dayjs';

const todos = ref([])
const selectedTodo = ref(null)
const showModal = ref(false)

const openCreate = () => {
    selectedTodo.value = null
    showModal.value = true
}

const openEdit = (todo) => {
    selectedTodo.value = todo
    showModal.value = true
}

const saveTodo = async (todo) => {
    const isEdit = !!selectedTodo.value

    const url = isEdit
        ? `/todos/${todo.id}/update`
        : `/todos`

    const method = isEdit ? 'PATCH' : 'POST'

    const result = await fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
        },
        body: JSON.stringify(todo),
    })

    const saved = await result.json()

    if (isEdit) {
        const i = todos.value.findIndex(t => t.id === saved.id)
        todos.value[i] = saved
    } else {
        todos.value.unshift(saved)
    }

    showModal.value = false
}

const fetchTodos = async () => {
    const result = await fetch('todos/list')
    todos.value = await result.json()
}


const statusFilter = ref('all')

const filteredTodos = computed(() => {
    return todos.value.filter(todo => {
        if (statusFilter.value === 'completed') {
            return todo.completed
        }

        if (statusFilter.value === 'pending') {
            return !todo.completed
        }
        return true
    })
})

const prioritySort = ref('none')
const sortedTodos = computed(() => {
    const list = [...filteredTodos.value]

    if (prioritySort.value === 'asc') {
        list.sort((a, b) => b.priority - a.priority)
    }

    if (prioritySort.value === 'desc') {
        list.sort((a, b) => a.priority - b.priority)
    }

    return list
})

const today = dayjs()
const groupedTodos = computed(() => ({
    overdue: sortedTodos.value.filter(t => dayjs(t.completion_date).isBefore(today, 'day')),
    today: sortedTodos.value.filter(t => dayjs(t.completion_date).isSame(today, 'day')),
    next3: sortedTodos.value.filter(t => dayjs(t.completion_date).isAfter(today) && dayjs(t.completion_date).diff(today, 'day') <= 3),
    next7: sortedTodos.value.filter(t => dayjs(t.completion_date).isAfter(today) && dayjs(t.completion_date).diff(today, 'day') >= 4 && dayjs(t.completion_date).diff(today, 'day') <= 7),
    later: sortedTodos.value.filter(t => dayjs(t.completion_date).diff(today, 'day') > 7),
    noDeadline: sortedTodos.value.filter(t => !t.completion_date),
}))

onMounted(fetchTodos)
</script>

<template>


    <div class="flex gap-4 m-2">
        <button @click="openCreate" class="mb-4 border rounded px-2 py-1">+ New Task</button>
        <!-- Status filter -->
        <select v-model="statusFilter" class="border rounded px-2 py-1">
            <option value="all">All</option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
        </select>

        <select v-model="prioritySort" class="border rounded px-2 py-1">
            <option value="none">No Priority Order</option>
            <option value="asc">Priority ↑ (Low → High)</option>
            <option value="desc">Priority ↓ (High → Low)</option>
        </select>
    </div>

    <div class="flex gap-4 overflow-x-auto p-4">
        <TodoColumn title="Overdue" :todos="groupedTodos.overdue" @edit="openEdit"></TodoColumn>
        <TodoColumn title="Today" :todos="groupedTodos.today" @edit="openEdit"></TodoColumn>
        <TodoColumn title="Next 3 Days" :todos="groupedTodos.next3" @edit="openEdit"></TodoColumn>
        <TodoColumn title="Next 7 Days" :todos="groupedTodos.next7" @edit="openEdit"></TodoColumn>
        <TodoColumn title="Later" :todos="groupedTodos.later" @edit="openEdit"></TodoColumn>
        <TodoColumn title="No current time limit" :todos="groupedTodos.noDeadline" @edit="openEdit"></TodoColumn>
    </div>

    <TodoModal v-if="showModal" :todo="selectedTodo" @save="saveTodo" @close="showModal = false" />
</template>
