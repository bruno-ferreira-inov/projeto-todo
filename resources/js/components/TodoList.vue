<script setup>
import { onMounted, ref } from 'vue';

const todos = ref([])
const title = ref('')

const fetchTodos = async() => {
    const result = await fetch('todos/list')
    todos.value = await result.json()
}

const addTodo = async () => {
    if(!title.value) return

    const result = await fetch('/todos', {
        method: 'POST',
        headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'X-CSRF-TOKEN': document
            .querySelector('meta[name=csrf-token]')
                .content,
        },
        body : JSON.stringify({title: title.value}),
    })

    todos.value.unshift(await result.json())
    title.value = ''
}

const toggleTodo = async(todo) => {
    const result = await fetch(`/todos/${todo.id}/toggle`,{
        method:'PATCH',
        headers : {
            'Accept' : 'application/json',
            'X-CSRF-TOKEN' : document
            .querySelector('meta[name=csrf-token]')
            .content,
        },
    })

    Object.assign(todo, await result.json())
}

onMounted(fetchTodos)
</script>

<template>
    <div class="w-full">
    <form @submit.prevent="addTodo">
        <input v-model="title" placeholder="New Todo"/>
        <button>Create Todo</button>
    </form>

    <ul>
        <li
        v-for="todo in todos"
        :key="todo.id"
        @click="toggleTodo(todo)"
        :style="{textDecoration: todo.completed ? 'line-through' : ''}">
    {{ todo.title }}</li>
    </ul>
</div>
</template>
