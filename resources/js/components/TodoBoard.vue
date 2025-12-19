<script setup>
import { onMounted, ref, computed } from 'vue';
import TodoModal from './TodoModal.vue';
import TodoColumn from './TodoColumn.vue';
import PillDropdown from './PillDropdown.vue';
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

const deleteTodo = async (todo) => {
    const result = confirm('Do you wish to delete this Task?')
    if (!result) return
    await fetch(`todos/${todo.id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
        },
    })
    todos.value = todos.value.filter(t => t.id === todo.id)

    fetchTodos();
    showModal.value = false
}

const fetchTodos = async () => {
    const result = await fetch('todos/list')
    todos.value = await result.json()
}


const statusFilter = ref('All')

const filteredTodos = computed(() => {
    return todos.value.filter(todo => {
        if (statusFilter.value === 'Completed') {
            return todo.completed
        }

        if (statusFilter.value === 'Pending') {
            return !todo.completed
        }
        return true
    })
})

const prioritySort = ref('No Priority')
const sortedTodos = computed(() => {
    const list = [...filteredTodos.value]

    if (prioritySort.value === 'Priority ↑') {
        list.sort((a, b) => b.priority - a.priority)
    }

    if (prioritySort.value === 'Priority ↓') {
        list.sort((a, b) => a.priority - b.priority)
    }

    return list
})

const today = dayjs().startOf('day')
const groupedTodos = computed(() => ({
    overdue: sortedTodos.value.filter(t => dayjs(t.completion_date).startOf('day').isBefore(today, 'day')),
    today: sortedTodos.value.filter(t => dayjs(t.completion_date).startOf('day').isSame(today, 'day')),
    next3: sortedTodos.value.filter(t => dayjs(t.completion_date).startOf('day').isAfter(today) && dayjs(t.completion_date).diff(today, 'day') <= 3),
    next7: sortedTodos.value.filter(t => dayjs(t.completion_date).startOf('day').isAfter(today) && dayjs(t.completion_date).diff(today, 'day') >= 4 && dayjs(t.completion_date).diff(today, 'day') <= 7),
    later: sortedTodos.value.filter(t => dayjs(t.completion_date).startOf('day').diff(today, 'day') > 7),
    noDeadline: sortedTodos.value.filter(t => !t.completion_date),
}))

onMounted(fetchTodos)
</script>

<template>
    <div class="gap-4 border-b-1 border-gray-300">
        <div class="flex justify-between py-2 px-2">
            <button @click="openCreate" class="group flex items-center gap-3 rounded-full bg-gray-100 px-4 py-2 ml-2
         text-sm font-medium text-gray-700
         hover:bg-gray-50 transition">

                New Task

                <span class="flex h-6 w-6 items-center justify-center rounded-full
           bg-gray-500 text-white text-sm
           group-hover:bg-gray-700 transition">
                    +
                </span>
            </button>
            <!-- Status filter -->
            <div class="justify-end px-8">
                <PillDropdown v-model="prioritySort" :options="['No Priority', 'Priority ↑', 'Priority ↓']"
                    class="mr-2" />
                <PillDropdown v-model="statusFilter" :options="['All', 'Pending', 'Completed']" />
            </div>
        </div>
    </div>

    <div class="w-full flex justify-center">

        <div class="flex items-start gap-4 overflow-x-auto px-6 py-4 max-w-full">
            <TodoColumn v-if="groupedTodos.overdue.length > 0" title="Overdue" :todos="groupedTodos.overdue"
                @edit="openEdit">
            </TodoColumn>
            <TodoColumn v-if="groupedTodos.today.length > 0" title="Today" :todos="groupedTodos.today" @edit="openEdit">
            </TodoColumn>
            <TodoColumn v-if="groupedTodos.next3.length > 0" title="Next 3 Days" :todos="groupedTodos.next3"
                @edit="openEdit"></TodoColumn>
            <TodoColumn v-if="groupedTodos.next7.length > 0" title="Next 7 Days" :todos="groupedTodos.next7"
                @edit="openEdit"></TodoColumn>
            <TodoColumn v-if="groupedTodos.later.length > 0" title="Later" :todos="groupedTodos.later" @edit="openEdit">
            </TodoColumn>
            <TodoColumn v-if="groupedTodos.noDeadline.length > 0" title="No Deadline" :todos="groupedTodos.noDeadline"
                @edit="openEdit"></TodoColumn>
        </div>
    </div>

    <TodoModal v-if="showModal" :todo="selectedTodo" @save="saveTodo" @close="showModal = false" @delete="deleteTodo" />
</template>
