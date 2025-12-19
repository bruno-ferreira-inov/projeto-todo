<template>
    <div class="relative inline-block">
        <button @click="toggle" class="flex items-center justify-between gap-2 min-w-28 rounded-full bg-gray-100 px-4 py-2 text-sm text-gray-700
             hover:bg-gray-200 focus:outline-none">
            <span>{{ modelValue }}</span>
            <svg class="h-4 w-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div v-if="open" @click.outside="open = false"
            class="absolute left-1/2 -translate-x-1/2 mt-1 w-28 rounded-xl bg-gray-100 border border-gray-400 shadow-lg p-1 z-50">
            <button v-for="option in options" :key="option" @click="select(option)" class="w-full text-left rounded-lg px-3 py-2 text-sm text-gray-700
               hover:bg-gray-200">
                {{ option }}
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
    options: Array,
    modelValue: String
})

const emit = defineEmits(['update:modelValue'])

const open = ref(false)

const toggle = () => {
    open.value = !open.value
}

const select = (option) => {
    emit('update:modelValue', option)
    open.value = false
}
</script>
