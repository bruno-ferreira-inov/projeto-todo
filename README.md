# Todo Board Application

## Overview

A task management application built with Laravel and Vue 3.
Tasks are grouped dynamically based on deadlines and can be filtered based on completion state.

## Architecture

#### Tech Stack

-   Laravel 12
-   Vue 3 (Composition API)
-   Tailwind CSS
-   SQLite

The application follows a client-server architecture.

-   **Backend (Laravel)** acts as a REST API:

    -   Handles data persistence
    -   Performs validation

-   **Frontend (Vue 3)** handles:
    -   UI Rendering
    -   State Management
    -   Client-side filtering and grouping

The communication between both parts is done with JSON-based HTTP requests.

#### Project Structure

-   `app/Http/Controllers/TodoController.php` – API logic and validation
-   `resources/js/components/TodoBoard.vue` – main board state
-   `resources/js/components/TodoModal.vue` – create/edit modal

## Features

Within the app, a user can:

-   Create, edit, delete To-dos (Tasks)
-   Assign priorities and deadlines to tasks
-   Mark tasks as completed or pending
-   View tasks grouped in deadline-based columns:
    -   Overdue
    -   Today
    -   Next 3 Days
    -   Next 7 Days
    -   Later
    -   No Deadline
-   Filter tasks to show completed, pending, or all tasks.

#### Implementation

##### To-dos CRUD Operation:

-   To-dos are stored in a SQLite database.
-   A Laravel controller handles validation and persistence.
-   All create and update actions return JSON responses, which are used to update the frontend state..

##### Deadline-Based Grouping:

-   To-dos are grouped on the client using `dayJs`.
-   Grouping is computed reactively using Vue's `computed` properties.
-   This allows for tasks to move between columns as time passes without user input.

##### Modal Reuse

-   A single Modal component is used for both creating and editing To-dos.
-   The modal determines its mode based on the presence of a To-do prop.
-   Keyboard shortcuts are supported for accessibility:
    -   Escape to close the modal
    -   Ctrl/Cmd + Enter to submit changes

## Key Design Decisions

-   All filtering is handled client-side
-   Date grouping uses day-based comparisons
