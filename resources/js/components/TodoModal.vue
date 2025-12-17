<script setup>
import { defineProps, defineEmits, watch, ref } from 'vue';

const props = defineProps({
    todo: {
        type: Object,
        required: true
    }
})

const emit = defineEmits(['close', 'updated'])

const title = ref(props.todo.title)
const description = ref(props.todo.description)
const priority = ref(props.todo.priority)
const completed = ref(props.todo.completed)
const completionDate = ref(props.todo.completion_date)

watch(
    () => props.todo,
    (newTodo) => {
        title.value = newTodo.title;
        description.value = newTodo.description;
        priority.value = newTodo.priority;
        completed.value = newTodo.completed;
        completionDate.value = newTodo.completion_date
    },
    { immediate: true }
)

const save = async () => {
    const result = await fetch(`/todos/${props.todo.id}/update`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name=csrf-token]')
                .content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            title: title.value,
            priority: priority.value,
            description: description.value,
            completed: completed.value,
            completion_date: completionDate.value,
        })
    })

    const updatedTodo = await result.json()
    emit('updated', updatedTodo)
    emit('close')
}

</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="w-full max-w-md rounded-xl bg-white p-6">
            <label class="block text-sm font-semibold mb-1">Title</label>
            <input type="text" class="text-lg font-bold mb-2 border " v-model="title" />

            <label class="block font-semibold mt-4">Description</label>
            <input type="text" class="text-lg mb-2 border" v-model="description" />

            <label class="block text-sm font-semibold mb-1">Priority</label>
            <input type="number" v-model.number="priority" min="1" max="5" class="w-24 border mb-2" />

            <label class="flex items-center gap-2 mb-4 w-36 border">
                <input type="checkbox" v-model="completed" />
                Completed
            </label>

            <label class="flex items-center gap-2 mb-4 border">
                <input type="date" v-model="completionDate" />
            </label>

            <div class="flex justify-end gap-2">
                <button @click="emit('close')">Cancel</button>
                <button @click="save" class="bg-indigo-600 text-white px-4 py-2 rounded">
                    Save
                </button>
            </div>
        </div>
    </div>
</template>
